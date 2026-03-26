<?php echo display_messages('', $this->session->flashdata('messages')); ?>
<div class="content_left">
	<h2><?php echo e('admin_turnout_label'); ?></h2>
</div>
<div class="content_right">
	<p class="align_right">
		View:
		<?php echo form_dropdown('election_id', for_dropdown($elections, 'id', 'election'), $election_id, 'class="changeElections" style="width: 130px;"'); ?>
	</p>
</div>
<div class="clear"></div>
<table cellpadding="0" cellspacing="0" class="table">
	<tr>
		<th scope="col"><?php echo e('admin_turnout_block'); ?></th>
		<th scope="col" class="w15"><?php echo e('admin_turnout_total_voters'); ?></th>
		<th scope="col" class="w15"><?php echo e('admin_turnout_voted'); ?></th>
		<th scope="col" class="w15"><?php echo e('admin_turnout_percentage'); ?></th>
	</tr>
	<?php if (empty($turnout_data['blocks'])): ?>
	<tr>
		<td colspan="4" align="center"><em><?php echo e('admin_turnout_no_data'); ?></em></td>
	</tr>
	<?php else: ?>
	<?php $i = 0; ?>
	<?php foreach ($turnout_data['blocks'] as $block): ?>
	<tr class="<?php echo ($i % 2 == 0) ? 'odd' : 'even'  ?>">
		<td>
			<?php echo $block['block_name']; ?>
		</td>
		<td align="center">
			<?php echo $block['total_voters']; ?>
		</td>
		<td align="center">
			<?php echo $block['voted']; ?>
		</td>
		<td align="center">
			<?php echo number_format($block['percentage'], 2); ?>%
		</td>
	</tr>
	<?php $i = $i + 1; ?>
	<?php endforeach; ?>
	<tr class="aggregate" style="font-weight: bold; background-color: #e0e0e0;">
		<td>
			<?php echo e('admin_turnout_total'); ?>
		</td>
		<td align="center">
			<?php echo $turnout_data['aggregate']['total_voters']; ?>
		</td>
		<td align="center">
			<?php echo $turnout_data['aggregate']['voted']; ?>
		</td>
		<td align="center">
			<?php echo number_format($turnout_data['aggregate']['percentage'], 2); ?>%
		</td>
	</tr>
	<?php endif; ?>
</table>
