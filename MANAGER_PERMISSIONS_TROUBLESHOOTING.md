# Manager Permissions Troubleshooting Guide

## Symptom
"You do not have enough permissions to access the location" when accessing admin modules as Manager.

---

## Step 1: Verify Current User Role

**Check your session/user data:**

```sql
SELECT u.id, u.username, u.group_id, g.name AS role
FROM users u
JOIN groups g ON u.group_id = g.id
WHERE u.id = [CURRENT_USER_ID];
```

**In CakePHP controller/debug:**
```php
// Temporarily add to any controller action
debug($this->Auth->user());
// Output should show: ['id'=>x, 'username'=>x, 'group_id'=>2, ...]
```

**Expected for Manager:** `group_id = 2`

---

## Step 2: Verify Manager Permissions in ACL Tables

**Check manager ARO record:**
```sql
SELECT a.id, a.parent_id, a.model, a.foreign_key, a.alias
FROM aros a
WHERE a.model = 'Group' AND a.foreign_key = 2;
```

**Check manager permissions (aros_acos):**
```sql
SELECT aa.*, ac.alias 
FROM aros_acos aa
JOIN acos ac ON aa.aco_id = ac.id
WHERE aa.aro_id = (
    SELECT id FROM aros WHERE model = 'Group' AND foreign_key = 2
)
ORDER BY ac.alias;
```

**Check specific permission flags:**
```sql
SELECT 
    ac.alias,
    aa._create, aa._read, aa._update, aa._delete
FROM aros_acos aa
JOIN acos ac ON aa.aco_id = ac.id
WHERE aa.aro_id = (
    SELECT id FROM aros WHERE model = 'Group' AND foreign_key = 2
)
ORDER BY ac.alias;
```

**Permission values:** `1` = allowed, `-1` = denied, `0` = not set/inherit

---

## Step 3: Check ACL Node Structure for Specific Controller/Action

**Find the ACO for the failing module:**
```sql
-- Example: check 'admin_index' for Designations
SELECT id, parent_id, alias, lft, rght 
FROM acos 
WHERE alias LIKE '%designation%' 
   OR alias LIKE '%Designations%'
ORDER BY lft;
```

**Check if manager has permission for that specific ACO:**
```sql
SELECT ac.lft, ac.rght, ac.alias, aa._create, aa._read, aa._update, aa._delete
FROM acos ac
LEFT JOIN aros_acos aa ON aa.aco_id = ac.id 
    AND aa.aro_id = (SELECT id FROM aros WHERE model='Group' AND foreign_key=2)
WHERE ac.lft BETWEEN (
    SELECT lft FROM acos WHERE alias = 'Designations' AND parent_id != 0
) AND (
    SELECT rght FROM acos WHERE alias = 'Designations' AND parent_id != 0
)
ORDER BY ac.lft;
```

---

## Step 4: Verify ARO/ACO Tree Integrity

**Check if ACO tree is correctly built:**
```sql
-- Count total ACO nodes
SELECT COUNT(*) FROM acos;

-- Check for orphaned nodes
SELECT ac.id, ac.alias, ac.parent_id, p.alias AS parent_alias
FROM acos ac
LEFT JOIN acos p ON ac.parent_id = p.id
WHERE ac.parent_id IS NOT NULL AND p.id IS NULL;

-- Check for circular references or gaps
SELECT alias, lft, rght FROM acos ORDER BY lft;
-- lft/rght should form proper nested intervals
```

**Rebuild ACO tree if corrupted:**
```bash
# From app/ directory
./Console/cake acl_extras aco_sync
```

---

## Step 5: Check Auth/ACL Configuration in Code

**Verify Controller Authorization:**

Check `app/Controller/AppController.php`:
```php
public function beforeFilter() {
    parent::beforeFilter();
    // Should have something like:
    $this->Auth->allow(); // or specific actions
    // ACL check: $this->Acl->check($user, $aco)
}
```

**Check ManagersController or failing controller:**
```php
public function beforeFilter() {
    parent::beforeFilter();
    // Look for explicit $this->Auth->allow() or $this->Acl->deny()
}
```

---

## Step 6: Clear CakePHP Cache

**Clear all cache files:**
```bash
# From app/ directory
rm -rf tmp/cache/models/*
rm -rf tmp/cache/persistent/*
rm -rf tmp/cache/views/*
```

**Or use CakePHP console:**
```bash
./Console/cake cache clear_all
```

**Clear ACL cache specifically:**
```bash
rm -rf tmp/cache/persistent/myapp_core*
```

---

## Step 7: Verify Database Connection & Schema

**Ensure designations table has the required records:**
```sql
SELECT * FROM designations ORDER BY id;
-- Should have the 5 category rows inserted earlier
```

**Check that ACL tables exist and have data:**
```sql
SHOW TABLES LIKE '%aros%' OR LIKE '%acos%';
-- Should show: aros, acos, aros_acos, (possibly groups, users)
```

---

## Step 8: Debug ACL Check Manually

**Create a temporary debug action in DesignationsController:**
```php
public function admin_test_permissions() {
    $user = $this->Auth->user();
    $aco = 'controllers/Designations/admin_index';
    
    $result = $this->Acl->check($user, $aco);
    
    debug(compact('user', 'aco', 'result'));
    
    // Also check all Designations permissions
    $all = $this->Acl->Aro->Acos->find('all', [
        'conditions' => ['Aco.alias LIKE' => '%Designation%'],
        'recursive' => -1
    ]);
    debug($all);
    
    die();
}
```

**Then visit `/admin/designations/test_permissions`**

---

## Step 9: Check for Explicit Deny Rules

**Look for 'controllers' node with -1 permissions:**
```sql
SELECT ac.alias, aa._create, aa._read, aa._update, aa._delete
FROM acos ac
JOIN aros_acos aa ON ac.id = aa.aco_id
WHERE ac.alias IN ('controllers', 'controllers/Designations')
   OR ac.alias LIKE 'controllers/Designations/%'
ORDER BY ac.lft;
```

**If manager has `-1` at higher level, it overrides lower-level `1`s**

---

## Step 10: Verify User-Group Association & ARO Record

**Check user's ARO record:**
```sql
SELECT * FROM aros 
WHERE model = 'User' AND foreign_key = [your_user_id];
```

**Check if user belongs to correct parent group:**
```sql
SELECT * FROM aros WHERE id = (
    SELECT parent_id FROM aros 
    WHERE model = 'User' AND foreign_key = [your_user_id]
);
```

**Should show group foreign_key = 2 for managers**

---

## Common Issues & Fixes

### Issue 1: User assigned to wrong group
**Fix:** Update user's group_id to 2 (Manager)
```sql
UPDATE users SET group_id = 2 WHERE id = [user_id];
```

### Issue 2: ACO tree out of sync after adding new controller/action
**Fix:** Rebuild ACO tree
```bash
./Console/cake acl_extras aco_sync
```

### Issue 3: Cache stale after permission changes
**Fix:** Clear all cache (Step 6)

### Issue 4: Implicit deny at parent node overriding explicit allows
**Fix:** Check 'controllers' node in aros_acos for manager
```sql
SELECT * FROM aros_acos 
WHERE aro_id = (SELECT id FROM aros WHERE model='Group' AND foreign_key=2)
  AND aco_id = (SELECT id FROM acos WHERE alias='controllers');
-- If _read = -1, it blocks all sub-nodes. Update to 1 or NULL.
```

### Issue 5: Missing ARO record for user (not linked to group)
**Fix:** Manually create ARO entry:
```bash
./Console/cake acl initdb
# Or manually insert into aros table
```

---

## Quick Diagnostic Queries

**1. Full manager permission dump:**
```sql
SELECT 
    GROUP_CONCAT(DISTINCT ac.alias ORDER BY ac.lft SEPARATOR '\n') AS allowed_acos
FROM aros_acos aa
JOIN acos ac ON ac.id = aa.aco_id
WHERE aa.aro_id = (SELECT id FROM aros WHERE model='Group' AND foreign_key=2)
  AND (aa._create = 1 OR aa._read = 1 OR aa._update = 1 OR aa._delete = 1);
```

**2. Missing permissions for Designations:**
```sql
-- Find Designations ACOs manager lacks:
SELECT ac.id, ac.alias
FROM acos ac
WHERE ac.alias LIKE 'controllers/Designations/%'
  AND ac.id NOT IN (
    SELECT aco_id FROM aros_acos 
    WHERE aro_id = (SELECT id FROM aros WHERE model='Group' AND foreign_key=2)
  );
```

**3. Check for explicit denies (-1):**
```sql
SELECT ac.alias, aa.*
FROM aros_acos aa
JOIN acos ac ON ac.id = aa.aco_id
WHERE aa.aro_id = (SELECT id FROM aros WHERE model='Group' AND foreign_key=2)
  AND (aa._create = -1 OR aa._read = -1 OR aa._update = -1 OR aa._delete = -1);
```

---

## Restoration Checklist

- [ ] Confirm user is in group_id = 2 (Manager)
- [ ] Verify manager ARO record exists in `aros`
- [ ] Run `./Console/cake acl_extras aco_sync` to sync ACOs
- [ ] Clear all cache in `tmp/cache/`
- [ ] Check for explicit `-1` denies at parent ACO nodes
- [ ] Verify `groups` table has correct name "Manager"
- [ ] Confirm ACL is enabled in `app/Config/bootstrap.php` or `core.php`
- [ ] Test with a fresh login/session

---

## References
- ACL tables: `aros`, `acos`, `aros_acos`
- Manager group_id: 2
- AclExtras plugin likely used for ACO synchronization
- Auth component configured in `app/Controller/AppController.php`
