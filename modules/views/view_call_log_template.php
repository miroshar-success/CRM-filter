<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal-header task-single-header" data-task-single-id="<?php echo $call_log->id; ?>" >
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?php echo $call_log->call_purpose; ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-8 task-single-col-left" style="min-height: 926px;">


            <div class="tc-content">
                <h4 class="th font-medium mbot5 pull-left"><?php echo _l('call_purpose');?></h4><div class="clearfix"></div>
                <?php echo $call_log->call_purpose; ?>
            </div>
            <div class="clearfix"></div>
            <hr class="hr-10">

            <h4 class="th font-medium mbot5 pull-left">Description</h4>
            <div class="clearfix"></div>
            <div class="tc-content">
                <?php echo $call_log->call_summary; ?>
            </div>
            <div class="clearfix"></div>

        </div>
        <div class="col-md-4 task-single-col-right">
            <h4 class="task-info-heading"><?php echo _l('call_log_info'); ?></h4>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <h5 class="no-mtop task-info-created">
               <small class="text-dark"><?php echo _l('task_created_at','<span class="text-dark">'._dt($call_log->dateadded).'</span>'); ?></small>
            </h5>

            <hr class="task-info-separator">

            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-address-book-o"></i>
                    <?php echo _l('cl_related'); ?>: <?php echo ucfirst($call_log->customer_type);?>
                </h5>
            </div>

            <?php if($call_log->clientid != '' && $call_log->customer_type != '') {
                $rel_data = get_relation_data($call_log->customer_type, $call_log->clientid);
                $rel_val = get_relation_values($rel_data, $call_log->customer_type);
            }
            ?>
            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-address-book-o"></i>
                    <?php echo _l(ucfirst($call_log->customer_type)); ?>: <?php echo $rel_val['name'];?>
                </h5>
            </div>

            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-info"></i>
                    <?php echo _l('cl_type'); ?>: <?php echo $rel_type->name;?>
                </h5>
            </div>

            <?php if($call_log->rel_type != '' && $call_log->rel_id > 0) {
                $rel_data = get_relation_data($rel_type->key, $call_log->rel_id);
                $rel_val = get_relation_values($rel_data, $rel_type->key);
            ?>
            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-info"></i>
                    <?php echo $rel_type->name; ?>: <?php echo $rel_val['name'];?>
                </h5>
            </div>
            <?php }  ?>

            <?php if($call_log->call_direction != '' && $call_log->call_direction >0){ ?>
            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-info"></i>
                    <?php echo _l('call_log_direction'); ?>: <?php echo $call_direction->name;?>
                </h5>
            </div>
            <?php } ?>
            <hr class="task-info-separator">

            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-clock-o"></i>
                    <?php echo _l('cl_call_start_time'); ?>: <?php echo _dt($call_log->call_start_time);?>
                </h5>
            </div>
            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-clock-o"></i>
                    <?php echo _l('cl_call_end_time'); ?>: <?php echo _dt($call_log->call_end_time);?>
                </h5>
            </div>
            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-clock-o"></i>
                    <?php echo _l('cl_call_duration'); ?>: <?php echo $call_log->call_duration;?>
                </h5>
            </div>
            <hr class="task-info-separator">
            <?php
                if( $call_log->has_follow_up == 1){$val = 'YES';}else{$val = 'NO';}
            ?>
            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-bell-o"></i>
                    <?php echo _l('cl_follow_up_requried'); ?>: <?php echo $val;?>
                </h5>
            </div>

            <?php  if( $call_log->has_follow_up == 1){ ?>
            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-clock-o"></i>
                    <?php echo _l('cl_follow_up_schedule'); ?>: <?php echo _dt($call_log->follow_up_schedule);?>
                </h5>
            </div>
            <?php } ?>

            <hr class="task-info-separator">
            <?php if( $call_log->is_completed == 1){$val = 'YES';}else{$val = 'NO';}  ?>
            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-star-o"></i>
                    <?php echo _l('cl_call_log_completed'); ?>: <?php echo $val;?>
                </h5>
            </div>
            <?php if( $call_log->is_important == 1){$val = 'YES';}else{$val = 'NO';}  ?>
            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-star-o"></i>
                    <?php echo _l('cl_call_log_important'); ?>: <?php echo $val;?>
                </h5>
            </div>

            <hr class="task-info-separator">
            <?php $staffOwner = get_staff($call_log->staffid);?>
            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-user-o"></i>
                    <?php echo _l('cl_call_owner'); ?>: <?php echo $staffOwner->firstname.' '.$staffOwner->lastname;?>
                </h5>
            </div>

            <?php $staffMember = get_staff($call_log->call_with_staffid);?>
            <div class="task-info task-info-billable">
                <h5><i class="fa task-info-icon fa-fw fa-lg pull-left fa fa-user-o"></i>
                    <?php echo _l('cl_call_with_staff'); ?>: <?php echo $staffMember->firstname.' '.$staffMember->lastname;?>
                </h5>
            </div>


        </div>
    </div>
</div>
<script>
    $(function() {


        // get a node
        //E('node-id');
    });
</script>