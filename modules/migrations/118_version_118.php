<?php



defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Version_118 extends App_module_migration

{

	public function up()

	{
		//v8
		add_option('twiml_app_friendly_name', 0);
		add_option('twiml_app_sid', 0);
		add_option('twiml_app_voice_request_url', 0);

	}

}



