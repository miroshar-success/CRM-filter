<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_115 extends App_module_migration
{
	public function up()
	{    
		//settings
		add_option(SI_LEAD_FILTERS_MODULE_NAME.'_cf',serialize(array()));
	}
}