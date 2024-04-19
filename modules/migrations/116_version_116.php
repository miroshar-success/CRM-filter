<?php







defined('BASEPATH') or exit('No direct script access allowed');







class Migration_Version_116 extends App_module_migration
{



    public function up()



    {



    	$CI = &get_instance();

		$call_logsTable = db_prefix() . 'call_logs';

		

		//v1

		if (!$CI->db->field_exists('opt_event_type', $call_logsTable)) {

            $CI->db->query("ALTER TABLE `" . $call_logsTable . "` ADD `opt_event_type` ENUM('call','sms','bulk sms') NOT NULL DEFAULT 'call' AFTER `datestart`;");

        }



        if (!$CI->db->field_exists('sms_content', $call_logsTable)) {

            $CI->db->query("ALTER TABLE `" . $call_logsTable . "` ADD `sms_content` TEXT NULL DEFAULT NULL AFTER `opt_event_type`;");

        }

		

    	if (!$CI->db->field_exists('twilio_sms_response', db_prefix().'call_logs')) {



            $CI->db->query("ALTER TABLE `".db_prefix()."call_logs` ADD  twilio_sms_response TEXT NULL DEFAULT NULL AFTER `sms_content`");



        }


    }



}







