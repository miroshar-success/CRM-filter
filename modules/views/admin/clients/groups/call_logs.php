<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if(isset($client)){ ?>
    <h4 class="customer-profile-group-heading"><?php echo _l('call_logs'); ?></h4>
    <?php if(has_permission('call_logs','','create')){ ?>
        <a href="<?php echo admin_url('call_logs/call_log?customer_type=customer&clientid='.$client->userid); ?>" class="btn btn-info mbot15<?php if($client->active == 0){echo ' disabled';} ?>">
            <?php echo _l('new_call_log'); ?>
        </a>
    <?php } ?>
    <?php if(has_permission('call_logs','','view') || has_permission('call_logs','','view_own') || get_option('allow_staff_view_call_logs_assigned') == '1'){ ?>
        <?php
            $this->load->view('../../modules/call_logs/views/admin/clients/groups/table_html', array('class'=>'call_logs-single-client'));
            $this->load->view('../../modules/call_logs/views/admin/clients/groups/call_logs_js');
        ?>
    <?php } ?>
<?php } ?>
