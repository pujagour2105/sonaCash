<?php

namespace App\Models;



class Cron_model extends Crud_model
{
    protected $table = 'valuation_details';
    
    function __construct()
    {
        parent::__construct();
    }

    public function syncMissingCustomers()
    {
        // Step 1: Fetch all valuations with empty customer_id
        $builder = $this->db->table('valuation_details');
        $builder->select('id, valuation_id, customer_name');
        $builder->where('customer_id',NULL);
        $query = $builder->get();
        $results = $query->getResultArray();
       

        foreach ($results as $row) {
            $customerName = trim($row['customer_name']);

            if (!empty($customerName)) {
                // Step 2: Insert into ms_customer
                $customerData = [
                    'name' => $customerName,
                    'ts' => date('Y-m-d H:i:s')
                ];

                $this->db->table('ms_customer')->insert($customerData);
                // echo $this->db->getLastQuery(); 
                $newCustomerId = $this->db->insertID();

                // Step 3: Update valuation_details with new customer_id
                $this->db->table('valuation_details')
                ->where('valuation_id', $row['valuation_id']) 
                ->where('id', $row['id'])          
                ->update(['customer_id' => $newCustomerId]); 
            }
        }

       return true;

    }
    public function syncMissingItems()
    {
   
        $builder = $this->db->table('inventory_detail');
        $builder->select('item_name');
        $builder->where('item_id', null);
        $builder->distinct();
        $query = $builder->get();
        $results = $query->getResultArray();

        foreach ($results as $row) {
            $itemName = trim($row['item_name']);

            if (!empty($itemName)) {
                // Step 2: Check if item already exists in ms_item
                $existingItem = $this->db->table('ms_item')
                    ->select('id')
                    ->where('name', $itemName)
                    ->get()
                    ->getRow();

                if ($existingItem) {
                    $itemId = $existingItem->id;
                } else {
                    // Step 3: Insert item into ms_item
                    $itemData = [
                        'name' => $itemName,
                        'ts' => date('Y-m-d H:i:s')
                    ];
                    $this->db->table('ms_item')->insert($itemData);
                    $itemId = $this->db->insertID();
                }

                // Step 4: Update inventory_detail with new item_id
                $this->db->table('inventory_detail')
                    ->where('inventory_id', $row['inventory_id'])
                    ->where('id', $row['id'])
                    ->update(['item_id' => $itemId]);
            }
        }

        return true;
    }

}
