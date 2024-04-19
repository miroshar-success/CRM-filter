<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_105 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();

        add_option('staff_members_daily_calls_target', 0);
        add_option('staff_members_monthly_calls_target', 0);

        $cl_goalsNotificationsTable = db_prefix() . 'call_logs_goals_notified';
        if (!$CI->db->table_exists($cl_goalsNotificationsTable)) {
            $CI->db->query('CREATE TABLE `'. $cl_goalsNotificationsTable .'` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `staffid` int(11) DEFAULT "0",
              `notify_date` datetime DEFAULT NULL,
              `goal_type` varchar(255) DEFAULT "daily",
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;');
        }
    }
}

