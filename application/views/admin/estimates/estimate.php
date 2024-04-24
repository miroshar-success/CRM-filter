<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php
            echo form_open($this->uri->uri_string(), ['id' => 'estimate-form', 'class' => '_transaction_form estimate-form']);
            if (isset($estimate)) {
                echo form_hidden('isedit');
            }
            ?>
            <div class="col-md-12">
                <h4
                    class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-space-x-2">
                    <span>
                        <?php echo isset($estimate) ? format_estimate_number($estimate) : _l('create_new_estimate'); ?>
                    </span>
                    <?php echo isset($estimate) ? format_estimate_status($estimate->status) : ''; ?>
                </h4>
                <?php $this->load->view('admin/estimates/estimate_template'); ?>
            </div>
            <?php echo form_close(); ?>
            <?php $this->load->view('admin/invoice_items/item'); ?>
        </div>
    </div>
</div>
</div>
<?php init_tail(); ?>
<script>
$(document).ready(function() {
    $('#custom_fields\\[estimate\\]\\[25\\]').val(1);
    $('.table.estimate-items-table > * > tr > :where(*:nth-child(8)) input[name="quantity"] ').focusout(function() {
        var newValue = $(this).val(); // Get the value of .qq
        $('#custom_fields\\[estimate\\]\\[25\\]').val(newValue);
    });
});
$(document).ready(function() {
    // Count the number of elements with class "input-group-addon"
    var count = $('.input-group-addon').length;

    // Add a class to each element with the prefix "L" followed by the count
    $('.input-group-addon').each(function(index) {
        $(this).addClass('L' + (index + 1));
    });
});

$(document).ready(function() {
    var number = $('.input-group input[name="number"]').val();
        var prefix_year = $('.input-group #prefix_year').text().replace(/\s/g, '');
        var beforez = $('.L1').text().replace(/\s/g, '');
        // var newValueid = beforez + number +'-'+ prefix_year;
        var newValueid = number +'-'+ prefix_year;
        $('#custom_fields\\[estimate\\]\\[47\\]').val(newValueid);

    $('.input-group input[name="number"]').change(function() {
        var number = $('.input-group input[name="number"]').val();
        var prefix_year = $('.input-group #prefix_year').text().replace(/\s/g, '');
        var beforez = $('.L1').text().replace(/\s/g, '');
        // var newValueid = beforez + number +'-'+ prefix_year;
        var newValueid = number +'-'+ prefix_year;
        $('#custom_fields\\[estimate\\]\\[47\\]').val(newValueid);
    });
});
$(function() {
    validate_estimate_form();
    // Init accountacy currency symbol
    init_currency();
    // Project ajax search
    init_ajax_project_search_by_customer_id();
    // Maybe items ajax search
    init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');
});
</script>
</body>
</html>

