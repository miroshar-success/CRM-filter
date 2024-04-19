<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_104 extends App_module_migration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $CI = &get_instance();

        if ($CI->db->table_exists(ElITE_CUSTOM_JS_CSS_TABLE_NAME)) {
            if (!$CI->db->field_exists('code_view', ElITE_CUSTOM_JS_CSS_TABLE_NAME)) {
                $CI->db->query('ALTER TABLE `' . ElITE_CUSTOM_JS_CSS_TABLE_NAME . '` ADD code_view varchar(50) NOT NULL AFTER code_type;');
            }
        }
    }
}