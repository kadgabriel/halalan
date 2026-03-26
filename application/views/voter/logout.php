<div class="notes">
	<?php echo e('voter_logout_message'); ?>

	<?php
		if ($this->session->flashdata('vpt_hash')){
			echo "Voter reference hash: ".$this->session->flashdata('vpt_hash');
		}
	?>
</div>
<div class="paging">
	THANK YOU FOR VOTING!
</div>