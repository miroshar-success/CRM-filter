<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_111 extends App_module_migration
{
    public function up()
    {
    	$this->ci = &get_instance();
        // Perform database upgrade here
        $this->ci->load->dbforge();
        $fields =  array(
	        'woo_id' => array(
	                'type' => 'INT',
	                'constraint' => 9,
	                'null' => TRUE
	        ));
		$this->ci->dbforge->add_column('clients', $fields);
    }
}