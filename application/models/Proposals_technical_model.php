<?php

use app\services\AbstractKanban;
use app\services\proposals\ProposalsPipeline;

defined('BASEPATH') or exit('No direct script access allowed');

class Proposals_technical_model extends App_Model
{
    private $statuses;
    private $copy = false;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Inserting new proposal function
     * @param mixed $proposal_id
     * @param string $checked_item_ids  Comma-separated string of item IDs
     */
    public function add($proposal_id, $checked_item_ids)
    {
        // Convert checked_item_ids string into an array
        $item_ids = explode(',', $checked_item_ids);
        
        // Prepare data for insertion
        $data = [];
        foreach ($item_ids as $item_id) {
            $data[] = [
                'proposal' => $proposal_id,
                'item'     => trim($item_id)  // Trim any whitespace
            ];
        }

        // Insert data into tblproposal_technicals
        if (!empty($data)) {
            return $this->db->insert_batch('tblproposal_technicals', $data);
        }
        
        return false; // Return false if there's nothing to insert
    }

    /**
     * Update proposal
     * @param mixed $proposal_id
     * @param string $checked_item_ids  Comma-separated string of item IDs
     * @return boolean
     */
    public function update($proposal_id, $checked_item_ids)
    {
        // Step 1: Remove existing items for the given proposal_id
        $this->db->where('proposal', $proposal_id);
        $this->db->delete('tblproposal_technicals');

        // Step 2: Add new items
        return $this->add($proposal_id, $checked_item_ids);
    }
}

