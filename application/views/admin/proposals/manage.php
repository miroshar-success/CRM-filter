<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="panel-table-full">
                <?php $this->load->view('admin/proposals/list_template'); ?>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/includes/modals/sales_attach_file'); ?>
<script>
var hidden_columns = [4, 5, 6, 7, 8];
</script>
<?php init_tail(); ?>
<div id="convert_helper"></div>

</body>

</html>
<script src="/assets/js/admin/proposals.js"></script>
<script>
var proposal_id;
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
    <?php if ($report_months_valid !== '') { ?>
        $('#report_months_valid').val("<?php echo htmlspecialchars($report_months_valid); ?>");
    <?php }
    if ($report_from_valid !== '') {
    ?>
        $('#report_from_valid').val("<?php echo htmlspecialchars($report_from_valid); ?>");
    <?php
    }
    if ($report_to_valid !== '') {
    ?>
        $('#report_to_valid').val("<?php echo htmlspecialchars($report_to_valid); ?>");
    <?php
    }
    ?>
    var Proposals_ServerParams = {};
    $.each($('._hidden_inputs._filters input, ._hidden_inputs._filters select'), function() {
        Proposals_ServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
    });
    console.log(Proposals_ServerParams);
    initDataTable('.table-proposals', admin_url + 'proposals/table', ['undefined'], ['undefined'],
        Proposals_ServerParams, [7, 'desc']);
    init_proposal();
});
</script>
