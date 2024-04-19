<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                     <div class="_buttons">
                        <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#call_directions-modal"><?php echo _l('new_call_direction'); ?></a>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />
                    <div class="clearfix"></div>
                    <?php render_datatable(array(
                        _l('cl_group_name'),
                        _l('options'),
                        ),'call_directions'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('call_types/call_direction.php'); ?>
<?php init_tail(); ?>
<script>
   $(function(){
        initDataTable('.table-call_directions', window.location.href, [1], [1]);
   });
</script>
</body>
</html>
