<?php

use app\services\AbstractKanban;
use app\services\invoices\invoicesPipeline;

defined('BASEPATH') or exit('No direct script access allowed');

class Invoices_technical_model extends App_Model
{
    private $statuses;
    private $copy = false;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Inserting new invoice function
     * @param mixed $invoice_id
     * @param string $checked_item_ids  Comma-separated string of item IDs
     */
    public function add($invoice_id, $checked_item_ids)
    {
        // Convert checked_item_ids string into an array
        $item_ids = explode(',', $checked_item_ids);
        
        // Prepare data for insertion
        $data = [];
        foreach ($item_ids as $item_id) {
            $data[] = [
                'invoice' => $invoice_id,
                'item'     => trim($item_id)  // Trim any whitespace
            ];
        }

        // Insert data into tblinvoice_technicals
        if (!empty($data)) {
            return $this->db->insert_batch('tblinvoice_technicals', $data);
        }
        
        return false; // Return false if there's nothing to insert
    }

    /**
     * Update invoice
     * @param mixed $invoice_id
     * @param string $checked_item_ids  Comma-separated string of item IDs
     * @return boolean
     */
    public function update($invoice_id, $checked_item_ids)
    {
        // Step 1: Remove existing items for the given invoice_id
        $this->db->where('invoice', $invoice_id);
        $this->db->delete('tblinvoice_technicals');

        // Step 2: Add new items
        return $this->add($invoice_id, $checked_item_ids);
    }
}

