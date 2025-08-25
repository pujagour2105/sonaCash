<?php

namespace App\Models;

use \Hermawan\DataTables\DataTable;

class Common_model extends Crud_model
{
    // protected $table = 'admin';
    function __construct()
    {
        parent::__construct();
    }
    
    function get_branch_fund($branch_id) {
        $builder = $this->db->table("branch_fund");
        $builder->select('sum(amount) as amount')
        ->where('branch_id', $branch_id);
        $result = $builder->get();
        // print_r($this->db->getLastQuery()->getQuery());exit;
        if ($result->getRow()) {
            return $result->getRow()->amount;
        } else {
            return 0;
        }
    }
    
    function get_branch_expense($branch_id) {
        $builder = $this->db->table("inventory_detail");
        $builder->select('sum(total_amount) AS totamount')
        ->where('branch_id', $branch_id);
        $result = $builder->get();
        if ($result->getRow()) {
            return $result->getRow()->totamount;
        } else {
            return 0;
        }
    }
    

}
