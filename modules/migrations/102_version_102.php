<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_102 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        add_option('staff_members_create_inline_call_direction', 1);
        add_option('staff_members_create_inline_cl_types', 1);
        add_option('staff_members_bulk_sms', 0);



        $cl_relTypeTable = db_prefix() . 'call_logs_rel_types';
        $cl_callDirectionsTable = db_prefix() . 'call_logs_directions';
        $cl_callLogsTable = db_prefix()."call_logs";

        $CI->db->query('CREATE TABLE IF NOT EXISTS `'.$cl_relTypeTable.'` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `name` varchar(255) NOT NULL,
                          `key` varchar(100) NOT NULL,
                          `is_default` tinyint(4) DEFAULT "0",
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;');

        $CI->db->query('CREATE TABLE IF NOT EXISTS `'.$cl_callDirectionsTable.'` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `name` varchar(255) NOT NULL,
                          `is_default` tinyint(4) DEFAULT "0",
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;');

        if (!$CI->db->field_exists('cl_rel_type', $cl_callLogsTable)) {
            $CI->db->query("ALTER TABLE `" . $cl_callLogsTable . "` ADD `cl_rel_type` int(11) DEFAULT '0' AFTER `rel_type`;");
        }

        $cl_rel_types = get_related_to_types();
        foreach ($cl_rel_types as $item){
            $CI->db->insert($cl_relTypeTable, array(
                'name'=>$item['lang_key'],
                'key'=>$item['key'],
                'is_default' => $item['is_default']
            ));
            $id = $CI->db->insert_id();

            $data = array(
                    'cl_rel_type'  =>  $id
            );
            $CI->db->where('rel_type', $item['key']);
            $CI->db->update($cl_callLogsTable, $data);
        }

        if ($CI->db->field_exists('rel_type', $cl_callLogsTable)) {
            $CI->db->query("ALTER TABLE `" . $cl_callLogsTable . "` DROP `rel_type` ;");
        }

        if ($CI->db->field_exists('cl_rel_type', $cl_callLogsTable)) {
            $CI->db->query("ALTER TABLE `" . $cl_callLogsTable . "` CHANGE `cl_rel_type` `rel_type` INT(11) DEFAULT '0' ;");
        }

        $cl_call_directions = get_call_directions();
        foreach ($cl_call_directions as $item){
            $CI->db->insert($cl_callDirectionsTable, array(
                'name'=> _l($item['lang_key']),
                'is_default' => 1
            ));
        }
    }
}