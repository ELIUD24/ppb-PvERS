<div class="designations view">
<h2><?php echo __('Designation'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($designation['Designation']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($designation['Designation']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Category'); ?></dt>
		<dd>
			<?php
				$categories = array(
					'1' => __('Physician'),
					'2' => __('Pharmacist'),
					'3' => __('Other Health Professional'),
					'4' => __('Lawyer'),
					'5' => __('Consumer or other non-health professional')
				);
				echo isset($categories[$designation['Designation']['category']]) ? h($categories[$designation['Designation']['category']]) : '<small class="muted">' . __('please set') . '</small>';
			?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($designation['Designation']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($designation['Designation']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Designation'), array('action' => 'edit', $designation['Designation']['id'])); ?></li>
		<li><?php echo $this->Form->postLink(__('Delete Designation'), array('action' => 'delete', $designation['Designation']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $designation['Designation']['id']))); ?></li>
		<li><?php echo $this->Html->link(__('List Designations'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('New Designation'), array('action' => 'add')); ?></li>
	</ul>
</div>
