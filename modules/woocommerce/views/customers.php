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
                                <h4 class="no-margin"><?php echo html_escape(_l('woocommerce_customers')) ?></h4>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <?php echo form_open($this->uri->uri_string()); ?>
                                <div class="col-md-9 col-sm-9">
                                    <?php
                                    $selected = active_store_id();
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
                        <?php
                        $table_data = array();
                        $_table_data = array(
                            // $obj,
                            array(
                                'name' => _l('id'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-id')
                            ),
                            array(
                                'name' => _l('username'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-username')
                            ),
                            array(
                                'name' => _l('name'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-name')
                            ),
                            array(
                                'name' => _l('phone_number'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-phone')
                            ),
                            array(
                                'name' => _l('email'),
                                'th_attrs' => array('class' => 'toggleable', 'id' => 'th-email')
                            ),
                            array(
                                'name' => _l('avatar'),
                                'th_attrs' => array('class' => 'toggleable ', 'id' => 'th-avatar')
                            ),
                        );
                        foreach ($_table_data as $_t) {
                            array_push($table_data, $_t);
                        }

                        $table_data = hooks()->apply_filters('woocommerce_customers_table', $table_data);

                        render_datatable($table_data, 'woocommerce');
                        ?>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal content for the edit CUSTOMER button-->
<div class="modal fade in" id="updateModal" role="dialog">
    <div class="modal-dialog modal-mg">
        <div class="modal-content data animated fast zoomInUp       ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo html_escape(_l('edit')) ?></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open(admin_url('woocommerce/update/customer/1'), array('id' => "formCust", "onsubmit" => "return checkpass(this)",)); ?>
                <div class="form-group">
                    <label><?php echo _l("username") ?></label>
                    <input type="text" class="form-control" name="username" id="username" value="" readonly required>
                    <input type="hidden" class="form-control" name="custId" id="custId" required readonly>
                </div>
                <?php echo render_input('email', 'client_email', '', 'email', array('id' => 'custEmail')); ?>
                <?php echo render_input('firstName', 'client_firstname', '', 'text', array('id' => 'firstName')); ?>
                <?php echo render_input('lastName', 'client_lastname', '', 'text', array('id' => 'lastName')); ?>

                <hr>

                <div class="col-md-12 row">
                    <div class="row">
                        <div class="col-md-5 mtop10 border-right">
                            <span><?php echo _l('clients_edit_profile_change_password_btn'); ?></span>
                        </div>
                        <div class="col-md-5 mtop10">
                            <div class="onoffswitch">
                                <input type="checkbox" id="changePassword" class="onoffswitch-checkbox" onclick="spass()" value="1" name="change_password">
                                <label class="onoffswitch-label" for="changePassword"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="spass" class="hide">
                    <?php echo render_input('password', 'client_password', '', 'password', array('id' => 'password')); ?>
                    <?php echo render_input('passwordr', 'clients_register_password_repeat', '', 'password', array('id' => 'passwordr')); ?>
                    <div class="hide text-danger alert-error pcheck"><?= _l("passwords_dont_match") ?></div>

                </div>
                <hr>
                <small class=" small alert-info">Beta ( more options coming soon )</small>
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
<div class="modal fadeIn" id="deletecustModal" role="dialog">
    <div class="modal-dialog modal-mg">
        <div class="modal-content data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo html_escape(_l('delete') . ' ' . _l('customer')) ?></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open(admin_url('woocommerce/delete/customers')); ?>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="cname" value="" readonly>
                    <input type="hidden" class="form-control" name="productId" id="productId">
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
<!-- end -->

<div id="modal_wrapper"></div>

<?php init_tail(); ?>
<script src="<?php echo site_url('modules/woocommerce/assets/js/customers.js'); ?>"></script>
<script>
    function importCustomer(el) {
        var id = $(el).data('id');
        $("#modal_wrapper").load("<?php echo admin_url('woocommerce/misc/modal'); ?>", {
            slug: 'customer',
            customer_id: id
        }, function(response) {

            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#importCustomerModal').is(':hidden')) {
                $('#importCustomerModal').modal({
                    show: true
                });
                init_selectpicker()
            }
        });
    }
</script>
</body>

</html>

</html>