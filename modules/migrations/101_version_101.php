<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_101 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $call_logsTable = db_prefix() . 'call_logs';

        if (!$CI->db->field_exists('dateadded', $call_logsTable)) {
            $CI->db->query("ALTER TABLE `" . $call_logsTable . "` ADD `dateadded` datetime DEFAULT NULL AFTER `rel_id`;");
        }

        if (!$CI->db->field_exists('dateaupdated', $call_logsTable)) {
            $CI->db->query("ALTER TABLE `" . $call_logsTable . "` ADD `dateaupdated` datetime DEFAULT NULL AFTER `dateadded`;");
        }
    }
}