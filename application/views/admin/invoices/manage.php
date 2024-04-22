<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			include_once(APPPATH . 'views/admin/invoices/filter_params.php');
			$this->load->view('admin/invoices/list_template');
			?>
		</div>
	</div>
</div>
<?php $this->load->view('admin/includes/modals/sales_attach_file'); ?>
<div id="modal-wrapper"></div>
<script>
	var hidden_columns = [2, 6, 7, 8];
</script>
<?php init_tail(); ?>
<script src="/assets/js/admin/invoices.js"></script>
<script>
	$(function() {
		"use strict";
		<?php if ($report_months !== '') { ?>
			$('#report_months').val("<?php echo htmlspecialchars($report_months); ?>");
		<?php }
		if ($report_from !== '') {
		?>
			$('#report_from').val("<?php echo htmlspecialchars($report_from); ?>");
		<?php
		}
		if ($report_to !== '') {
		?>
			$('#report_to').val("<?php echo htmlspecialchars($report_to); ?>");
		<?php
		}
		?>
		init_invoice();
	});
</script>
</body>

</html>
