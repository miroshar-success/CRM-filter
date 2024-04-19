<div id="modal_wrapper"></div>
<div class="modal fade" id="newWooStoreModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('woocommerce_new_store'); ?></h4>
            </div>
            <?php echo form_open('woocommerce/stores/new', array('id' => 'new_wooStore-form')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo render_input('name', 'woocommerce_store_name');
                        echo render_input('url', 'woocommerce_client', '', 'URL');
                        echo render_input('key', 'woocommerce_consumer_key');
                        echo render_input('secret', 'woocommerce_consumer_secret');

                        $selected = array();
                        
                        array_push($selected, get_staff_user_id());

                        echo render_select('assignees[]', $staff, array('staffid', array('firstname', 'lastname')), 'assignees', $selected, array('multiple' => true, 'data-actions-box' => true), array(), '', '', false);
                        echo render_yes_no_option('query_auth', 'woocommerce_query_auth');
                        ?>
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
<script>
        init_selectpicker();
</script>
