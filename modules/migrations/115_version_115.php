<?php







defined('BASEPATH') or exit('No direct script access allowed');







class Migration_Version_115 extends App_module_migration
{



    public function up()



    {



    	$CI = &get_instance();

		$call_logsTable = db_prefix() . 'call_logs';

		

		//v1

		if (!$CI->db->field_exists('dateadded', $call_logsTable)) {

            $CI->db->query("ALTER TABLE `" . $call_logsTable . "` ADD `dateadded` datetime DEFAULT NULL AFTER `rel_id`;");

        }



        if (!$CI->db->field_exists('dateaupdated', $call_logsTable)) {

            $CI->db->query("ALTER TABLE `" . $call_logsTable . "` ADD `dateaupdated` datetime DEFAULT NULL AFTER `dateadded`;");

        }

		

    	if (!$CI->db->field_exists('userphone', db_prefix().'call_logs')) {



            $CI->db->query("ALTER TABLE `".db_prefix()."call_logs` ADD COLUMN userphone VARCHAR(255) AFTER call_purpose");



        }



        if (!$CI->db->field_exists('datestart', db_prefix().'call_logs')) {



            $CI->db->query("ALTER TABLE `".db_prefix()."call_logs` ADD COLUMN datestart datetime AFTER dateaupdated");



        }



    	if (!$CI->db->field_exists('contactid', db_prefix().'call_logs')) {



            $CI->db->query("ALTER TABLE `".db_prefix()."call_logs` ADD COLUMN contactid INT(11) AFTER clientid");



        }



    }



}







