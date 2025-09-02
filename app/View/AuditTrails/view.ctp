<div class="auditTrails view">
<h2><?php echo __('Audit Trail'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($auditTrail['AuditTrail']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Foreign Key'); ?></dt>
		<dd>
			<?php echo h($auditTrail['AuditTrail']['foreign_key']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Model'); ?></dt>
		<dd>
			<?php echo h($auditTrail['AuditTrail']['model']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Message'); ?></dt>
		<dd>
			<?php echo h($auditTrail['AuditTrail']['message']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ip'); ?></dt>
		<dd>
			<?php echo h($auditTrail['AuditTrail']['ip']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Hostname'); ?></dt>
		<dd>
			<?php echo h($auditTrail['AuditTrail']['hostname']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Uri'); ?></dt>
		<dd>
			<?php echo h($auditTrail['AuditTrail']['uri']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Refer'); ?></dt>
		<dd>
			<?php echo h($auditTrail['AuditTrail']['refer']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User Agent'); ?></dt>
		<dd>
			<?php echo h($auditTrail['AuditTrail']['user_agent']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($auditTrail['AuditTrail']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Audit Trail'), array('action' => 'edit', $auditTrail['AuditTrail']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Audit Trail'), array('action' => 'delete', $auditTrail['AuditTrail']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $auditTrail['AuditTrail']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Audit Trails'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Audit Trail'), array('action' => 'add')); ?> </li>
	</ul>
</div>
