<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_120 extends App_module_migration
{
    public function up()
    {
    	$CI = &get_instance();
        // Perform database upgrade here
        if (!$CI->db->field_exists('woo_id', 'clients'))
        {
            $CI->load->dbforge();
            $fields =  array(
                'woo_id' => array(
                        'type' => 'INT',
                        'constraint' => 9,
                        'null' => TRUE
                ));
            $CI->dbforge->add_column('clients', $fields);
        }

        if (!$CI->db->field_exists('wco_id', 'invoices'))
        {
            $CI->load->dbforge();
            $fields =  array(
                'wco_id' => array(
                        'type' => 'INT',
                        'constraint' => 9,
                        'null' => TRUE
                ));
            $CI->dbforge->add_column('invoices', $fields);
        }
    }
}