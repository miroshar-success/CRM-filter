<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property-read CI_DB_mysql_driver $db
 */
class Migration_Version_305 extends CI_Migration
{
    public function up()
    {
        update_option('show_php_version_notice', '1');

        add_option('automatically_set_logged_in_staff_sales_agent', '1');
        add_option('contract_sign_reminder_every_days', '0');
        
        if (!$this->db->field_exists('last_sent_at', db_prefix() . 'subscriptions')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'subscriptions` ADD `last_sent_at` DATETIME NULL DEFAULT NULL;');
        }

        if (!$this->db->field_exists('last_sent_at', db_prefix() . 'contracts')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'contracts` ADD `last_sent_at` DATETIME NULL DEFAULT NULL;');
        }

        if (!$this->db->field_exists('contacts_sent_to', db_prefix() . 'contracts')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'contracts` ADD `contacts_sent_to` TEXT NULL DEFAULT NULL;');
        }

        if (!$this->db->field_exists('last_sign_reminder_at', db_prefix() . 'contracts')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'contracts` ADD `last_sign_reminder_at` DATETIME NULL DEFAULT NULL;');
        }

        // attempt to add last sent at to older contracts where possible
        $this->db->where('not_visible_to_client', 0);
        $this->db->where('isexpirynotified', 1);

        $this->db->group_start();
        $this->db->where('signed', 1);
        $this->db->or_where('marked_as_signed', 1);
        $this->db->group_end();

        $this->db->update(db_prefix() . 'contracts', ['last_sent_at' => date('c')]);

        create_email_template(
            'Contract Sign Reminder',
            '<p>Hello {contact_firstname} {contact_lastname}<br /><br />This is a reminder to review and sign the contract:<a href="{contract_link}">{contract_subject}</a></p><p>You can view and sign by visiting: <a href="{contract_link}">{contract_subject}</a></p><p><br />We are looking forward working with you.<br /><br />Kind Regards,<br /><br />{email_signature}</p>',
            'contract',
            'Contract Sign Reminder (Sent to Customer)',
            'contract-sign-reminder',
            1,
        );

	    $this->paymentGatewayFeeFeature();
    }


    public function paymentGatewayFeeFeature(): void
    {
        $this->db->query(
            'CREATE TABLE IF NOT EXISTS ' . db_prefix() . 'payment_attempts (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `reference` VARCHAR(100) NOT NULL,
            `invoice_id` INT NOT NULL,
            `amount` double NOT NULL,
            `fee` double NOT NULL,
            `payment_gateway` VARCHAR(100) NOT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;'
        );

        if (!$this->db->field_exists('attempt_reference', db_prefix() . 'twocheckout_log')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'twocheckout_log` ADD `attempt_reference` VARCHAR(100) NULL DEFAULT NULL;');
        }
    }
}
