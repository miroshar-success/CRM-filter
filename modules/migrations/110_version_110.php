<?php



defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Version_110 extends App_module_migration

{

    public function up()

    {

    	$CI = &get_instance();
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



