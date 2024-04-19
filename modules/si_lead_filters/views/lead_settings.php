<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();?>
<link href="<?php echo module_dir_url('si_lead_filters','assets/css/si_lead_filters_style.css'); ?>" rel="stylesheet" />
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<?php echo form_open($this->uri->uri_string(),"id=si_form_lead_filter_settings"); ?>
						<h4 class="pull-left"><?php echo _l('si_lf_title_settings'); ?></h4>
                        <div class="clearfix"></div>
						<hr />
                        <div class="row mbot15">
                            <div class="col-md-6">
                            <?php echo render_select('si_lf_cf[]',$custom_fields,array('id','name'),'si_lf_settings_cf_text',$selected_custom_fields,array('data-width'=>'100%','data-none-selected-text'=>_l('leads_all'),'multiple'=>true),array(),'no-mbot','',false);?>
                            </div>
                            <div class="col-md-12 text-center">
                                <hr/>
                                <button type="submit" class="btn btn-info" name="save"><?php echo _l('submit')?></button>
                            </div>    
                        </div>    
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>  
<script>
$('select[name="si_lf_cf[]"]').selectpicker({
    maxOptions:<?php echo SI_LEAD_FILTERS_MAX_CUSTOM_FIELDS;?>,
});  
</script>      