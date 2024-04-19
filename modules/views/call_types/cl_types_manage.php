<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                     <div class="_buttons">
                        <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#cl-type-modal"><?php echo _l('new_cl_type'); ?></a>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />
                    <div class="clearfix"></div>
                    <?php render_datatable(array(
                        _l('cl_group_name'),
                        _l('options'),
                        ),'cl-types'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('call_types/type.php'); ?>
<?php init_tail(); ?>
<script>
   $(function(){
        initDataTable('.table-cl-types', window.location.href, [1], [1]);
   });
</script>
</body>
</html>
