<?php

use app\services\AbstractKanban;
use app\services\estimates\estimatesPipeline;

defined('BASEPATH') or exit('No direct script access allowed');

class Estimates_technical_model extends App_Model
{
    private $statuses;
    private $copy = false;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Inserting new estimate function
     * @param mixed $estimate_id
     * @param string $checked_item_ids  Comma-separated string of item IDs
     */
    public function add($estimate_id, $checked_item_ids)
    {
        // Convert checked_item_ids string into an array
        $item_ids = explode(',', $checked_item_ids);
        
        // Prepare data for insertion
        $data = [];
        foreach ($item_ids as $item_id) {
            $data[] = [
                'estimate' => $estimate_id,
                'item'     => trim($item_id)  // Trim any whitespace
            ];
        }

        // Insert data into tblestimate_technicals
        if (!empty($data)) {
            return $this->db->insert_batch('tblestimate_technicals', $data);
        }
        
        return false; // Return false if there's nothing to insert
    }

    /**
     * Update estimate
     * @param mixed $estimate_id
     * @param string $checked_item_ids  Comma-separated string of item IDs
     * @return boolean
     */
    public function update($estimate_id, $checked_item_ids)
    {
        // Step 1: Remove existing items for the given estimate_id
        $this->db->where('estimate', $estimate_id);
        $this->db->delete('tblestimate_technicals');

        // Step 2: Add new items
        return $this->add($estimate_id, $checked_item_ids);
    }
}

