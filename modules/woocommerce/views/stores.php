<?php
defined('BASEPATH') or exit('No direct script access allowed');
init_head();
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo html_escape(_l('woocommerce_stores')) ?></h4>
                        <hr>
                        <?php if (staff_can('create', 'woocommerce')) { ?>
                            <button type="button" id="newWooStore" class="btn btn-info pull-left display-block">
                                <?php echo _l('new_woocommerce_store'); ?>
                            </button>
                        <?php } else { ?>
                            <button type="button" disabled class="btn btn-info pull-left display-block">
                                <?php echo _l('new_woocommerce_store'); ?> </button>
                        <?php } ?>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <?php render_datatable(array(
                            _l('woocommerce_store_id'),
                            _l('woocommerce_store_name'),
                            _l('woocommerce_store_assigned'),
                            _l('woocommerce_store_date'),
                            _l('options'),
                        ), 'woocommerce_stores'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal_wrapper"></div>

<?php init_tail(); ?>
<?php require('modules/woocommerce/assets/js/stores.php'); ?>
</body>

</html>