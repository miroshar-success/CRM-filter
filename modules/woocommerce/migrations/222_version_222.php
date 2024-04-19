<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_222 extends App_module_migration
{
    public function up()
    {
        $this->ci->load->dbforge();

        if (!$this->ci->db->field_exists('query_auth', 'woocommerce_stores')) {
            $fields =  array(
                'query_auth' => array(
                    'type' => 'INT',
                    'default' => 1,
                )
            );
            $this->ci->dbforge->add_column('woocommerce_stores', $fields);
        }
    }
}
