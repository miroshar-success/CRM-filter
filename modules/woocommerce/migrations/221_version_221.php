<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_221 extends App_module_migration
{
    public function up()
    {
        $this->ci->load->dbforge();

        $fields = array(
            'date_created' => array(
                'name' => 'date_created',
                'type' => 'DATETIME',
                'default' => null,
            ),
        );
        $this->ci->dbforge->modify_column('woocommerce_orders', $fields);
    }
}
