<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">

    <div class="content">

        <div class="row">

            <?php

            echo form_open($this->uri->uri_string(), ['id' => 'invoice-form', 'class' => '_transaction_form invoice-form']);

            if (isset($invoice)) {

                echo form_hidden('isedit');

            }

            ?>

            <div class="col-md-12">

                <h4

                    class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-space-x-2">

                    <span>

                        <?php echo isset($invoice) ? format_invoice_number($invoice) : _l('create_new_invoice'); ?>

                    </span>

                    <?php echo isset($invoice) ? format_invoice_status($invoice->status) : ''; ?>

                </h4>

                <?php $this->load->view('admin/invoices/invoice_template'); ?>

            </div>

            <?php echo form_close(); ?>

            <?php $this->load->view('admin/invoice_items/item'); ?>

        </div>

    </div>

</div>

<?php init_tail(); ?>

<script>
$(document).ready(function() {
    $('#custom_fields\\[invoice\\]\\[28\\]').val(1);
    $('.table.invoice-items-table > * > tr > :where(*:nth-child(8)) input[name="quantity"] ').focusout(function() {
        var newValue = $(this).val();
        $('#custom_fields\\[invoice\\]\\[28\\]').val(newValue);
    });
    let sum = 0;

    $('.technical_items_area .technical_invoice_item_check:checked').each(function() {
        sum += parseFloat($(this).val()) || 0;
    });

    $('.technical_items_total').text('€' + sum.toFixed(2));
    $('.technical_items_totals').val(sum.toFixed(2));
    $('.technical_invoice_item_check').on('change', function() {
        let sum = 0;

        $('.technical_items_area .technical_invoice_item_check:checked').each(function() {
            sum += parseFloat($(this).val()) || 0;
        });

        $('.technical_items_total').text('€' + sum.toFixed(2));
        $('.technical_items_totals').val(sum.toFixed(2));
        var $checkbox = $(this);
        var technicalItemId = $checkbox.closest('.form-group').find('.technical_item_ids').val();
        var itemableid = $checkbox.closest('.form-group').find('.itemable_id').val();
        if ($checkbox.is(':checked')) {
            $checkbox.closest('.form-group').append('<input type="hidden" name="technical_newitems[]" value="' + technicalItemId + '" class="technical_newitems">');
        } else {
            $checkbox.closest('.form-group').find('.technical_newitems').remove();
            $('#removed_items').append('<input type="hidden" name="technical_removed_items[]" value="' + itemableid + '">');
        }
    });

    $('#invoice-form').on('submit', function(e) { 
        let checkedIds = [];
        $('.technical_invoice_item_check:checked').each(function() {
            let itemId = $(this).closest('.form-group').find('.technical_item_ids').val();
            checkedIds.push(itemId);
        });
        $('#checked_item_ids').val(checkedIds.join(','));
        // validate_invoice_form();
    });
});
$(function() {

    validate_invoice_form();

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