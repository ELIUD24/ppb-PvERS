<?php
/**
 * ACL Management Shell
 *
 * Provides CLI tools to manage CakePHP's DbAcl system.
 *
 * Usage:
 *   cake Acl init        - Initialize groups, AROs, and ACOs
 *   cake Acl show        - Display current AROs, ACOs, and permissions
 *   cake Acl verify      - Check for inconsistencies in ACL tables
 *   cake Acl create_acos - Create ACO nodes for all controllers
 *   cake Acl create_aros - Create ARO nodes for all groups
 *
 * Note: For permission assignment, use UsersController::initDB()
 *       via browser: http://localhost:8080/users/initDB
 *
 * @package       app.Console.Command
 * @since         PvERS v1.0
 */
App::uses('AclNode', 'Model');
App::uses('Aco', 'Model');
App::uses('Aro', 'Model');

class AclShell extends AppShell {

/**
 * Models used by this shell
 *
 * @var array
 */
	public $uses = array('User', 'Group', 'Aco', 'Aro');

/**
 * Main entry point - shows help
 *
 * @return void
 */
	public function main() {
		$this->out('<info>Acl Management Shell</info>');
		$this->out('');
		$this->out('<comment>Usage:</comment>');
		$this->out('  cake Acl init        - Initialize groups, AROs, and ACOs');
		$this->out('  cake Acl show        - Display current AROs, ACOs, and permissions');
		$this->out('  cake Acl verify      - Check for inconsistencies in ACL tables');
		$this->out('  cake Acl create_acos - Create ACO nodes for all controllers');
		$this->out('  cake Acl create_aros - Create ARO nodes for all groups');
		$this->out('');
		$this->out('<comment>Permissions:</comment>');
		$this->out('  Visit http://yourapp/users/initDB to assign permissions');
		$this->out('  Or call UsersController::initDB() programmatically');
		$this->out('');
	}

/**
 * Full initialization: groups, AROs, and ACOs
 *
 * @return void
 */
	public function init() {
		$this->out('<info>Starting ACL initialization...</info>');
		$this->hr();

		$this->out('<comment>[1/3]</comment> Seeding groups...');
		$this->_seedGroups();

		$this->out('<comment>[2/3]</comment> Creating ARO nodes...');
		$this->create_aros();

		$this->out('<comment>[3/3]</comment> Creating ACO nodes...');
		$this->create_acos();

		$this->hr();
		$this->out('<info>ACL structural setup complete.</info>');
		$this->out('');
		$this->out('Next step: Assign permissions by visiting:');
		$this->out('  http://localhost:8080/users/initDB');
		$this->out('');
	}

/**
 * Seed the groups table with the 5 system roles.
 *
 * @return void
 */
	protected function _seedGroups() {
		$groups = array(
			array(
				'name' => 'Admin',
				'description' => 'System Administrator. Full access to all modules, users, and configuration.',
			),
			array(
				'name' => 'Manager',
				'description' => 'Report Manager. Manages report workflow, reviews submissions, and oversees data quality.',
			),
			array(
				'name' => 'Reporter',
				'description' => 'Healthcare Provider. Submits and manages own adverse event reports (SADRs, AEFIs, PQRs, etc.).',
			),
			array(
				'name' => 'Partner',
				'description' => 'Institution Administrator. Views and manages reports from their institution/facility.',
			),
			array(
				'name' => 'Reviewer',
				'description' => 'Clinical Reviewer. Reviews, evaluates, and follows up on submitted adverse event reports.',
			),
		);

		$created = 0;
		$skipped = 0;

		foreach ($groups as $groupData) {
			$existing = $this->Group->find('first', array(
				'conditions' => array('Group.name' => $groupData['name']),
				'recursive' => -1,
			));

			if ($existing) {
				$this->out('  <warning>Group "' . $groupData['name'] . '" already exists (ID: ' . $existing['Group']['id'] . '), skipping.</warning>');
				$skipped++;
				continue;
			}

			$this->Group->create();
			if ($this->Group->save($groupData)) {
				$this->out('  <info>Created group "' . $groupData['name'] . '"</info> (ID: ' . $this->Group->id . ')');
				$created++;
			} else {
				$this->out('  <error>Failed to create group "' . $groupData['name'] . '"</error>');
			}
		}

		$this->out('  Groups: <info>' . $created . ' created</info>, ' . $skipped . ' skipped.');
	}

/**
 * Create ARO (Access Request Object) nodes for all groups.
 *
 * @return void
 */
	public function create_aros() {
		$groups = $this->Group->find('all', array('recursive' => -1));

		if (empty($groups)) {
			$this->out('  <warning>No groups found. Run "cake Acl init" first.</warning>');
			return;
		}

		$created = 0;
		$skipped = 0;

		foreach ($groups as $group) {
			$existing = $this->Aro->find('first', array(
				'conditions' => array(
					'Aro.model' => 'Group',
					'Aro.foreign_key' => $group['Group']['id'],
				),
				'recursive' => -1,
			));

			if ($existing) {
				$this->out('  ARO for "' . $group['Group']['name'] . '" already exists, skipping.');
				$skipped++;
				continue;
			}

			$aroData = array(
				'model' => 'Group',
				'foreign_key' => $group['Group']['id'],
				'alias' => $group['Group']['name'],
			);

			$this->Aro->create();
			if ($this->Aro->save($aroData)) {
				$this->out('  <info>Created ARO</info> for group "' . $group['Group']['name'] . '" (ID: ' . $group['Group']['id'] . ')');
				$created++;
			} else {
				$this->out('  <error>Failed to create ARO for "' . $group['Group']['name'] . '"</error>');
			}
		}

		$this->out('  AROs: <info>' . $created . ' created</info>, ' . $skipped . ' skipped.');
	}

/**
 * Create ACO (Access Control Object) nodes for all controllers.
 *
 * @return void
 */
	public function create_acos() {
		$controllerPath = APP . 'Controller' . DS;
		$controllerFiles = glob($controllerPath . '*Controller.php');

		// Create root "controllers" node
		$rootAco = $this->Aco->find('first', array(
			'conditions' => array('Aco.alias' => 'controllers'),
			'recursive' => -1,
		));

		if (!$rootAco) {
			$this->Aco->create();
			$rootData = array(
				'alias' => 'controllers',
				'model' => null,
				'foreign_key' => null,
				'parent_id' => null,
			);
			if ($this->Aco->save($rootData)) {
				$this->out('  <info>Created root ACO "controllers"</info>');
			}
			$rootId = $this->Aco->id;
		} else {
			$rootId = $rootAco['Aco']['id'];
			$this->out('  Root ACO "controllers" already exists (ID: ' . $rootId . ')');
		}

		$controllerCount = 0;
		$actionCount = 0;

		foreach ($controllerFiles as $file) {
			$filename = basename($file, '.php');
			if ($filename === 'App') {
				continue;
			}

			$controllerName = str_replace('Controller', '', $filename);
			$controllerAlias = $controllerName; // Without "Controller" suffix for Acl compatibility

			$actions = $this->_extractActions($file);
			if (empty($actions)) {
				continue;
			}

			// Create controller ACO node
			$controllerAco = $this->Aco->find('first', array(
				'conditions' => array(
					'Aco.alias' => $controllerAlias,
					'Aco.parent_id' => $rootId,
				),
				'recursive' => -1,
			));

			if (!$controllerAco) {
				$this->Aco->create();
				$acoData = array(
					'alias' => $controllerAlias,
					'model' => null,
					'foreign_key' => null,
					'parent_id' => $rootId,
				);
				if ($this->Aco->save($acoData)) {
					$this->out('  <info>Created ACO</info> "' . $controllerAlias . '"');
					$controllerId = $this->Aco->id;
					$controllerCount++;
				} else {
					$this->out('  <error>Failed to create ACO "' . $controllerAlias . '"</error>');
					continue;
				}
			} else {
				$controllerId = $controllerAco['Aco']['id'];
			}

			// Create action ACO nodes
			foreach ($actions as $action) {
				$actionAco = $this->Aco->find('first', array(
					'conditions' => array(
						'Aco.alias' => $action,
						'Aco.parent_id' => $controllerId,
					),
					'recursive' => -1,
				));

				if (!$actionAco) {
					$this->Aco->create();
					$acoData = array(
						'alias' => $action,
						'model' => null,
						'foreign_key' => null,
						'parent_id' => $controllerId,
					);
					if ($this->Aco->save($acoData)) {
						$actionCount++;
					}
				}
			}
		}

		$this->out('  ACOs: <info>' . $controllerCount . ' controllers</info>, ' . $actionCount . ' actions created.');
	}

/**
 * Extract public action names from a controller file.
 *
 * @param string $file Path to the controller file
 * @return array List of action names
 */
	protected function _extractActions($file) {
		$actions = array();
		$content = file_get_contents($file);

		preg_match_all('/public\s+function\s+(\w+)\s*\(/', $content, $matches);

		if (!empty($matches[1])) {
			foreach ($matches[1] as $method) {
				if (strpos($method, '_') === 0) {
					continue;
				}
				if (in_array($method, array('beforeFilter', 'beforeRender', 'beforeRedirect',
					'constructClasses', 'paginate', 'isAuthorized'))) {
					continue;
				}
				$actions[] = $method;
			}
		}

		return $actions;
	}

/**
 * Display current ACL state.
 *
 * @return void
 */
	public function show() {
		$this->out('<info>Current ACL State</info>');
		$this->hr();

		// Show AROs
		$this->out('<comment>AROs (Access Request Objects):</comment>');
		$aros = $this->Aro->find('all', array('recursive' => 0));
		if (empty($aros)) {
			$this->out('  (none)');
		} else {
			foreach ($aros as $aro) {
				$alias = $aro['Aro']['alias'] ?: $aro['Aro']['model'] . ':' . $aro['Aro']['foreign_key'];
				$this->out('  [' . $aro['Aro']['id'] . '] ' . $alias);
			}
		}

		$this->hr();

		// Show ACOs (top level)
		$this->out('<comment>ACOs (Access Control Objects) - Top Level:</comment>');
		$acos = $this->Aco->find('all', array(
			'conditions' => array('Aco.parent_id' => null),
			'recursive' => -1,
		));
		if (empty($acos)) {
			$this->out('  (none)');
		} else {
			foreach ($acos as $aco) {
				$this->out('  [' . $aco['Aco']['id'] . '] ' . $aco['Aco']['alias']);
				$children = $this->Aco->find('all', array(
					'conditions' => array('Aco.parent_id' => $aco['Aco']['id']),
					'recursive' => -1,
					'limit' => 10,
				));
				foreach ($children as $child) {
					$this->out('    [' . $child['Aco']['id'] . '] ' . $child['Aco']['alias']);
				}
				if (count($children) >= 10) {
					$this->out('    ...');
				}
			}
		}

		$this->hr();

		// Show permissions
		$this->out('<comment>Permissions (ARO-ACO mappings):</comment>');
		$ArosAco = ClassRegistry::init('ArosAco');
		$permissions = $ArosAco->find('all', array(
			'recursive' => 0,
			'limit' => 50,
		));
		if (empty($permissions)) {
			$this->out('  (none)');
		} else {
			foreach ($permissions as $perm) {
				$this->out('  ARO[' . $perm['ArosAco']['aro_id'] . '] -> ACO[' . $perm['ArosAco']['aco_id'] . ']');
			}
			if (count($permissions) >= 50) {
				$this->out('  ... (showing first 50)');
			}
		}
	}

/**
 * Verify ACL integrity.
 *
 * @return void
 */
	public function verify() {
		$this->out('<info>Verifying ACL integrity...</info>');
		$this->hr();

		$errors = 0;

		// Check AROs
		$this->out('<comment>Checking AROs...</comment>');
		$aros = $this->Aro->find('all', array(
			'conditions' => array('Aro.model' => 'Group'),
			'recursive' => -1,
		));
		foreach ($aros as $aro) {
			$group = $this->Group->find('first', array(
				'conditions' => array('Group.id' => $aro['Aro']['foreign_key']),
				'recursive' => -1,
			));
			if (!$group) {
				$this->out('  <error>ARO[' . $aro['Aro']['id'] . '] references non-existent Group[' . $aro['Aro']['foreign_key'] . ']</error>');
				$errors++;
			}
		}
		if ($errors === 0) {
			$this->out('  <info>All AROs are valid.</info>');
		}

		// Check ACOs
		$this->out('<comment>Checking ACOs...</comment>');
		$acoErrors = 0;
		$acos = $this->Aco->find('all', array('recursive' => -1));
		foreach ($acos as $aco) {
			if ($aco['Aco']['parent_id'] !== null) {
				$parent = $this->Aco->find('first', array(
					'conditions' => array('Aco.id' => $aco['Aco']['parent_id']),
					'recursive' => -1,
				));
				if (!$parent) {
					$this->out('  <error>ACO[' . $aco['Aco']['id'] . '] "' . $aco['Aco']['alias'] . '" has invalid parent_id[' . $aco['Aco']['parent_id'] . ']</error>');
					$acoErrors++;
				}
			}
		}
		if ($acoErrors === 0) {
			$this->out('  <info>All ACOs are valid.</info>');
		}
		$errors += $acoErrors;

		// Check groups without AROs
		$this->out('<comment>Checking groups without AROs...</comment>');
		$groups = $this->Group->find('all', array('recursive' => -1));
		$aroGroupIds = array();
		foreach ($aros as $aro) {
			$aroGroupIds[] = $aro['Aro']['foreign_key'];
		}
		$missingAros = 0;
		foreach ($groups as $group) {
			if (!in_array($group['Group']['id'], $aroGroupIds)) {
				$this->out('  <warning>Group "' . $group['Group']['name'] . '" (ID: ' . $group['Group']['id'] . ') has no ARO</warning>');
				$missingAros++;
			}
		}
		if ($missingAros === 0) {
			$this->out('  <info>All groups have AROs.</info>');
		}
		$errors += $missingAros;

		$this->hr();
		if ($errors === 0) {
			$this->out('<info>ACL verification passed. No issues found.</info>');
		} else {
			$this->out('<error>ACL verification found ' . $errors . ' issue(s).</error>');
		}
	}
}
