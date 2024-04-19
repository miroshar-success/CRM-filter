<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <h4 class="no-margin"><?php echo html_escape(_l('woocommerce_orders')) ?></h4>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <?php echo form_open($this->uri->uri_string()); ?>
                                <div class="col-md-9 col-sm-9">
                                    <?php
                                    $selected = active_store_id();
                                    $selected;
                                    $stores = json_decode(json_encode($stores), true);
                                    echo render_select('store_id', $stores, array('store_id', 'name'), '', $selected);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <button type="submit" class="btn btn-info btn-block" style="margin-top:3px;"><?php echo _l('select'); ?></button>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row mbot15">
                            <div class="justify-contents-center">
                                <?php
                                if (is_array($summary)) {
                                    foreach ($summary as $item) { ?>
                                        <div class="col-md-3 text-center col-xs-6 border-right">
                                            <h3 class="bold"><?php echo html_escape($item->total) ?></h3>
                                            <span class="text-info"><?php echo html_escape(_l($item->slug)) ?></span>
                                        </div>
                                <?php }
                                } ?>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <div class="_filters _hidden_inputs hidden">
                                <?php echo form_hidden(
                                    'custom_view',
                                    'all'
                                ); ?>
                            </div>

                            <div class="btn-group pull-right btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-left width300 height500">

                                    <li class="filter-group active">
                                        <a href="#" data-cview="all" onclick="dt_custom_view('','.table-woocommerce',''); return false;">
                                            <?php echo _l('all'); ?>
                                        </a>
                                    </li>

                                    <li class="divider"></li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="completed" onclick="dt_custom_view('completed','.table-woocommerce'); return false;">
                                            <?= _l('completed'); ?>
                                        </a>
                                    </li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="pending" onclick="dt_custom_view('pending','.table-woocommerce'); return false;">
                                            <?= _l('pending'); ?>
                                        </a>
                                    </li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="processing" onclick="dt_custom_view('processing','.table-woocommerce'); return false;">
                                            <?= _l('processing'); ?>
                                        </a>

                                    </li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="on-hold" onclick="dt_custom_view('on-hold','.table-woocommerce'); return false;">
                                            <?= _l('on-hold'); ?>
                                        </a>
                                    </li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="cancelled" onclick="dt_custom_view('cancelled','.table-woocommerce'); return false;">
                                            <?= _l('cancelled'); ?>
                                        </a>
                                    </li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="failed" onclick="dt_custom_view('failed','.table-woocommerce'); return false;">
                                            <?= _l('failed'); ?>
                                        </a>
                                    </li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="refunded" onclick="dt_custom_view('refunded','.table-woocommerce'); return false;">
                                            <?= _l('refunded'); ?>
                                        </a>
                                    </li>
                                </ul>

                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <?php
                        $table_data = array();
                        $obj = array(
                            'name' => _l('order') . ' #',
                            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-id')
                        );
                        $_table_data = array(
                            $obj,
                            array(
                                'name' => _l('customer'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-customer')
                            ),
                            array(
                                'name' => _l('address'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-address')
                            ),
                            array(
                                'name' => _l('phone_number'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-phone')
                            ),
                            array(
                                'name' => _l('status'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-status')
                            ),
                            array(
                                'name' => _l('total_spent'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-total')
                            ),
                            array(
                                'name' => _l('order_date'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-date_created')
                            ),
                            array(
                                'name' => _l('invoice_id'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-invoice')
                            ),
                        );
                        foreach ($_table_data as $_t) {
                            array_push($table_data, $_t);
                        }
                        $table_data = hooks()->apply_filters('woocommerce_orders_table', $table_data);
                        render_datatable($table_data, 'woocommerce ', [], [
                            'data-last-order-identifier' => 'woocommerce',
                            'data-default-order'         => get_table_last_order('woocommerce'),
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <!-- end -->
            <?php echo form_open(admin_url('woocommerce/update_woo')); ?>
            <!-- Modal content for the update order button-->
            <div class="modal fadeIn" id="updateModal" role="dialog">
                <div class="modal-dialog modal-mg">
                    <div class="modal-content data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><?php echo html_escape(_l('update_order')) ?></h4>
                        </div>
                        <div class="modal-body">
                            <p><?php echo _l('woocommerce_order_id') ?></p>
                            <form action="" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="orderId" id="orderId" value="" readonly>
                                    <label for="sel1"><?php echo _l('woocommerce_status_select') ?>:</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="pending"><?php echo html_escape(_l('pending')) ?></option>
                                        <option value="processing"><?php echo html_escape(_l('processing')) ?></option>
                                        <option value="on-hold"><?php echo html_escape(_l('on-hold')) ?></option>
                                        <option value="completed"><?php echo html_escape(_l('completed')) ?></option>
                                        <option value="cancelled"><?php echo html_escape(_l('cancelled')) ?></option>
                                        <option value="refunded"><?php echo html_escape(_l('refunded')) ?></option>
                                        <option value="failed"><?php echo html_escape(_l('failed')) ?></option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo html_escape(_l('cancel')) ?></button>
                                    <button type="submit" class="btn btn-info" name="btn-update">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal content for the update order button-->

            <?php echo form_close(); ?>
            <!-- Modal content for the delete order button-->
            <div class="modal fadeIn" id="deleteModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <?php echo form_open(admin_url('woocommerce/update_woo')); ?>
                        <div class="modal-body">
                            <p><?php echo html_escape(_l('are_you_Sure_you_want_to_delete_this_Order')) ?> </p>
                            <div class="form-group">
                                <input type="text" class="form-control" name="orderid" id="orderid" value="" readonly>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo html_escape(_l('cancel')) ?></button>
                                <button type="submit" class="btn btn-danger" name="btn-delete"><?php echo html_escape(_l('delete')) ?></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            <!--end Modal content for the delete order button-->
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(function() {
        var param = {
            'custom_view': '[name="custom_view"]'
        }
        init_btn_with_tooltips();
        initDataTable('.table-woocommerce', admin_url + 'woocommerce/table/orders', [], [2], param, [0, 'desc']);
    });
</script>
<script src=" <?php echo site_url('modules/woocommerce/assets/js/orders.js'); ?>"></script>
</body>

</html>