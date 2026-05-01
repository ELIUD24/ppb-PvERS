<div class="designations index">
	<h2><?php echo __('Designations'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('category'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
	</tr>
	<?php
	foreach ($designations as $designation): ?>
	<tr>
		<td><?php echo h($designation['Designation']['id']); ?>&nbsp;</td>
		<td><?php echo h($designation['Designation']['name']); ?>&nbsp;</td>
		<td>
			<?php
				$categories = array(
					'1' => __('Physician'),
					'2' => __('Pharmacist'),
					'3' => __('Other Health Professional'),
					'4' => __('Lawyer'),
					'5' => __('Consumer or other non-health professional')
				);
				echo isset($categories[$designation['Designation']['category']]) ? h($categories[$designation['Designation']['category']]) : '<small class="muted">' . __('please set') . '</small>';
			?>&nbsp;
		</td>
		<td><?php echo h($designation['Designation']['modified']); ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
