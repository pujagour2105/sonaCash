<?php

namespace App\Models;

use \Hermawan\DataTables\DataTable;

class Admin_model extends Crud_model
{
 
    protected $table = 'admin';
    private $perm_statements = array('view', 'edit', 'add');

    function __construct()
    {
        parent::__construct();
    }

    public function login_user($data = array())
    {
        extract($data);
        $email = $this->db->escapeString($email);
        $password = $this->db->escapeString($password);
        $this->db->table($this->table);
        $this->select('id, name, password, status, image');
        $this->where('email', $email);
        $result = $this->get();
        // echo $this->getLastQuery()->getQuery();     
        if ($result->getRow()) {
            $response = $result->getRowArray();            
            $pass = $response["password"];
            $authenticatePassword = password_verify($password, $pass);
            if($authenticatePassword) {
                return $response;
            } else {
                return false;
            }
        }
        else
            return false;
    }

    /*@Author: Sunil Joshi
	 *@Objective: To get user session based on user id
	*/
    public function getSessionData($id)
    {
        $this->db->table($this->table);
        $result = $this->select('id, name, email, image,branch_id, role_id, is_admin')->where('id', $id)->get();
        if ($result->getRow())
            return $result->getRowArray();
        else
            return false;
    }


    public function get_staff_by_email($email)
    {
        $result = array();
        $result = $this->getRecordOnId($this->table, ["email" => $email], "");
        return $result;
    }


    public function get_profile($id)
    {
        $result = array();
        $result = $this->getRecordOnId($this->table, ["id" => $id], "");
        return $result;
    }


    public function getBranchFund()
    {
        $isAdmin = session()->get('is_admin');
        $branchId = session()->get('branch_id');

        $subQuery = $this->db->table('rpt_ledger as rl1')
            ->select('rl1.branch_id, rl1.available_balance')
            ->where('rl1.id = (
                SELECT rl2.id FROM rpt_ledger as rl2 
                WHERE rl2.branch_id = rl1.branch_id 
                ORDER BY rl2.ts DESC, rl2.id DESC 
                LIMIT 1
            )', null, false); // raw subquery allowed

        $builder = $this->db->table('branch b')
            ->select('b.branch_name, IFNULL(rl.available_balance, 0.00) as available_balance')
            ->join("({$subQuery->getCompiledSelect()}) rl", 'b.id = rl.branch_id', 'left')
            ->orderBy('b.branch_name', 'ASC');

        // ✅ Apply filter only for non-super admins
        if ($isAdmin != 1) {
            $builder->where('b.id', $branchId);
        }

        $result = $builder->get()->getResult();

        return $result;

    }


    public function getAmountDistribution() {
        $isAdmin = session()->get('is_admin');
        $branchId = session()->get('branch_id');

        // Create query builder
        $builder = $this->db->table('amount_distribution ad')
                    ->select('b.branch_name, ad.customer_name, ad.amount, DATE(ad.ts) as date_only')
                    ->join('branch b', 'b.id = ad.branch_id')
                    ->where('MONTH(ad.ts)', date('m'))
                    ->where('YEAR(ad.ts)', date('Y'))
                    ->where('is_approved', '0')
                    ->orderBy('ad.ts', 'DESC');

        // Apply branch filter for non-admins
        if ($isAdmin != 1) {
            $builder->where('ad.branch_id', $branchId);  // Fixed: use branch_id instead of id
        }

        // Execute query
        $query = $builder->get();
        return $query->getResult();
    }

}
