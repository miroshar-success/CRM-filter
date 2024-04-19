<div id="modal_wrapper"></div>
<div class="modal fade" id="editWooStoreModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('woocommerce_new_store'); ?></h4>
            </div>
            <?php echo form_open('woocommerce/stores/edit', array('id' => 'edit_wooStore-form')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo render_input('name', 'woocommerce_store_name', $store->name);
                        echo render_input('url', 'woocommerce_client', $store->url, 'URL');
                        echo render_input('key', 'woocommerce_consumer_key', $store->key);
                        echo render_input('secret', 'woocommerce_consumer_secret', $store->secret);

                        $selected = array();
                        if (isset($db_selected)) {
                            $selected =  array_merge($selected, $db_selected);
                        } else {
                            array_push($selected, get_staff_user_id());
                        }
                        echo render_select('assignees[]', $staff, array('staffid', array('firstname', 'lastname')), 'assignees', $selected, array('multiple' => true, 'data-actions-box' => true), array(), '', '', false);
                        echo form_hidden('store_id', $store_id);
                        ?>

                        <div class="form-group">
                            <label for="query_auth" class="control-label clearfix">
                                <?= _l('woocommerce_query_auth') ?>
                            </label>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" id="yes_query_auth" name="query_auth" value="1" <?= ($store->query_auth == '1') ? 'checked' : '' ?>>
                                <label for="yes_query_auth"><?= _l('yes') ?></label>
                            </div>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" id="no_query_auth" name="query_auth" value="0" <?= ($store->query_auth == '0') ? 'checked' : '' ?>>
                                <label for="no_query_auth"><?= _l('no') ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close_btn" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- <script src="<?= site_url('/assets/js/main.min.js') ?>"> -->
<script>
    init_selectpicker();
    appValidateForm($("#edit_wooStore-form"), {
        name: "required",
        url: {
            required: true,
            url: true
        },
        key: "required",
        secret: "required",

        'assignees[]': {
            required: true,
            minlength: 1
        }

    }, function(form) {
        $('button[type="submit"], button.close_btn').prop('disabled', true);
        $('button[type="submit"]').html('<i class="fa fa-refresh fa-spin fa-fw"></i>');
        form.submit();
    }, {
        'assignees[]': "Please select at least 1 staff member"
    });
</script>