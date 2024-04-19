<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
		<div class="col-lg-6 no-padding" style="padding-left:0px; padding-right:0px; background:#fff;height: 100vh;-webkit-box-shadow: 0 2px 30px 2px rgba(0,0,0,.1) !important; box-shadow: 0px 2px 30px 2px rgba(0,0,0,.1) !important;" >
			<div class="form-content">
            <div style="width: 100%; height: 50px;">
                <?php if((get_option('use_knowledge_base') == 1 && !is_client_logged_in() && get_option('knowledge_base_without_registration') == 1) || (get_option('use_knowledge_base') == 1 && is_client_logged_in())){ ?>
				<div class="form-title2" style="float: none; text-align: center; margin-top: 20px;margin-bottom: 30px; color:#d0d0d0;"><?php echo _l('clients_login_heading_no_register'); ?></div>
				<?php } else { ?>
				<div class="form-title1" style="float: left;"><img style="float: right;" src="/uploads/company/logo.png" class="img-responsive" alt="logotype"></div><div class="form-title2" style="float: right; margin-top: 20px; margin-bottom: 30px; color:#d0d0d0;"><?php echo _l('clients_login_heading_no_register'); ?></div>
                <?php } ?>
			</div>	
		<?php echo form_open($this->uri->uri_string(),array('class'=>'login-form')); ?>

		<div class="form-group" style="padding-top:50px">
		    
			<label for="email"><?php echo _l('clients_login_email'); ?></label>
			<input type="text" autofocus="true" class="form-control" name="email" id="email">
			<?php echo form_error('email'); ?>
		</div>
		<div class="form-group">
			<label for="password"><?php echo _l('clients_login_password'); ?></label>
			<input type="password" class="form-control" name="password" id="password">
			<?php echo form_error('password'); ?>
		</div>
		<?php if(get_option('use_recaptcha_customers_area') == 1 && get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != ''){ ?>
		<div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
		<?php echo form_error('g-recaptcha-response'); ?>
		<?php } ?>
		<div class="checkbox">
			<input type="checkbox" name="remember" id="remember">
			<label for="remember">
				<?php echo _l('clients_login_remember'); ?>
			</label>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-info btn-block"><?php echo _l('clients_login_login_string'); ?></button>
			<?php if(get_option('allow_registration') == 1) { ?>
			<a href="<?php echo site_url('clients/register'); ?>" class="btn btn-success btn-block"><?php echo _l('clients_register_string'); ?>
			</a>
			<?php } ?>
		</div>
		<a href="<?php echo site_url('clients/forgot_password'); ?>"><?php echo _l('customer_forgot_password'); ?></a>

		<div class="copyright-footer"><?php echo date('Y'); ?> <?php echo _l('clients_copyright', get_option('companyname')); ?></span>
		<?php if(is_gdpr() && get_option('gdpr_show_terms_and_conditions_in_footer') == '1') { ?>
		<br><a href="<?php echo terms_url(); ?>" class="terms-and-conditions-footer"><?php echo _l('terms_and_conditions'); ?></a>
		<?php } ?>
		<?php if(is_gdpr() && is_client_logged_in() && get_option('show_gdpr_link_in_footer') == '1') { ?>
		<br><a href="<?php echo site_url('clients/gdpr'); ?>" class="gdpr-footer"><?php echo _l('gdpr_short'); ?></a>
		<?php } ?>
		
		<?php echo form_close(); ?>
	
		</div>
		</div>