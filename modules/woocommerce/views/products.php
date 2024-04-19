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
                                <h4 class="no-margin"><?php echo html_escape(_l('woocommerce_products')) ?></h4>
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
                                <?php if (is_array($summary)) {
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
                                        <a href="#" data-cview="publish" onclick="dt_custom_view('publish','.table-woocommerce'); return false;">
                                            <?= _l('publish'); ?>
                                        </a>
                                    </li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="pending" onclick="dt_custom_view('pending','.table-woocommerce'); return false;">
                                            <?= _l('pending'); ?>
                                        </a>
                                    </li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="private" onclick="dt_custom_view('private','.table-woocommerce'); return false;">
                                            <?= _l('private'); ?>
                                        </a>

                                    </li>

                                    <li class="divider"></li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="simple" onclick="dt_custom_view('simple','.table-woocommerce'); return false;">
                                            <?= _l('simple'); ?>
                                        </a>
                                    </li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="grouped" onclick="dt_custom_view('grouped','.table-woocommerce'); return false;">
                                            <?= _l('grouped'); ?>
                                        </a>
                                    </li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="external" onclick="dt_custom_view('external','.table-woocommerce'); return false;">
                                            <?= _l('external'); ?>
                                        </a>
                                    </li>

                                    <li class="filter-group">
                                        <a href="#" data-cview="variable" onclick="dt_custom_view('variable','.table-woocommerce'); return false;">
                                            <?= _l('variable'); ?>
                                        </a>
                                    </li>
                                </ul>

                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <?php
                        $table_data = array();
                        $obj = array(
                            'name' => _l('sku'),
                            'th_attrs' => array('class' => 'toggleable', 'id' => 'th-sku')
                        );
                        $_table_data = array(
                            $obj,
                            array(
                                'name' => _l('name'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-name')
                            ),
                            array(
                                'name' => _l('status'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-status')
                            ),
                            array(
                                'name' => _l('price'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-price')
                            ),
                            array(
                                'name' => _l('sales'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-sales')
                            ),
                            array(
                                'name' => _l('picture'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-picture')
                            ),
                            array(
                                'name' => _l('view'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-permalink')
                            ),
                        );
                        foreach ($_table_data as $_t) {
                            array_push($table_data, $_t);
                        }

                        $table_data = hooks()->apply_filters('woocommerce_products_table', $table_data);

                        render_datatable($table_data, 'woocommerce');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal content for the edit PRODUCT button-->
<div class="modal fade in" id="updateModal" role="dialog">
    <div class="modal-dialog modal-mg">
        <div class="modal-content data animated fast zoomInUp   ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo html_escape(_l('edit')) ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo _l('client') ?> #</p>
                <?php echo form_open(admin_url('woocommerce/update/product')); ?>
                <div class="form-group">
                    <input type="text" class="form-control" name="productId" id="productId" value="" readonly>
                </div>
                <?php echo render_input('name', 'name', '', 'name', array('id' => 'name')); ?>

                <div class="form-group">
                    <label><?php echo _l("price") ?> <div></div></label>
                    <input type="text" class="form-control" name="regular_price" id="price" value="">
                    <div class="text-info alert-block currentPrice"></div>
                </div>

                <div class="form-group ">
                    <label for="status"><?php echo _l('status'); ?></label>
                    <select class="selectpicker" data-width="100%" name="status" id="status" required>
                        <option value="publish" selected><?php echo html_escape(_l('publish')) ?></option>
                        <option value="draft"><?php echo html_escape(_l('draft')) ?></option>
                        <option value="pending"><?php echo html_escape(_l('pending')) ?></option>
                        <option value="private"><?php echo html_escape(_l('private')) ?></option>
                    </select>
                </div>

                <?php echo render_textarea('xdescription', 'short_description'); ?>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo html_escape(_l('cancel')) ?></button>
                    <button type="submit" class="btn btn-info" name="btn-update"><?php echo _l("update") ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<!-- End Modal content for the Edit Product button-->

<!-- Modal content for Delete-->
<div class="modal fadeIn" id="deleteModal" role="dialog">
    <div class="modal-dialog modal-mg">
        <div class="modal-content data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo html_escape(_l('delete')) ?></h4>
            </div>
            <div class="modal-body">
                <p><?PHP _l('name') ?></p>
                <?php echo form_open(admin_url('woocommerce/delete/products')); ?>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="productName" value="" readonly>
                    <input type="hidden" class="productId" name="productId" value="" readonly>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo html_escape(_l('cancel')) ?></button>
                    <button type="submit" class="btn btn-danger" name="delete"><?php echo html_escape(_l('delete')) ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('item_modal'); ?>

<?php init_tail(); ?>
<script>
    $(function () {
    init_btn_with_tooltips();
    var param = {
        'custom_view': '[name="custom_view"]',
    }

    initDataTable('.table-woocommerce', admin_url + 'woocommerce/table/products', [], [2], param, [0, 'desc']);

});

</script>
<script src="<?php echo module_dir_url("woocommerce", "assets/js/products.js") ?>"></script>
</body>

</html>