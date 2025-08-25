<?php

namespace App\Models;
use App\Models\Common_model; 
use \Hermawan\DataTables\DataTable;

class Branch_model extends Crud_model
{
    protected $table = 'rpt_ledger';
    private $Common_model;
    function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model();
    }
    
    // Get role details
    public function save_data($postData)
    {
        if ($postData["id"]) {
            $this->updateRowInDB("branch", $postData, $where = ["id" => $postData["id"]]);
            $item_id = true;
        } else {
            unset($postData["id"]);
            $item_id = $this->insertRowInDB("branch", $postData);
        }
        return $item_id;
    }

    public function save_branch_fund_data($postData)
    {
        $edit_id = $postData["id"];
        $insert_array = [
            'user_id'       => session()->has('id') ? session()->get('id') : null,
            'branch_id'     => $postData["branch_id"],
            'amount'        => $postData["amount"],
            'description'   => $postData["description"],
            'status'        => '1',
            'ts'            => date("Y-m-d H:i:s")
        ];

        $lastLedger = $this->db->table('rpt_ledger')
                    ->where('branch_id', $postData["branch_id"])
                    ->orderBy('ts', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->getRowArray();

        if ($lastLedger) {
            $branch_fund = $lastLedger['available_balance'];
        } else {
            $branch_fund = $this->Common_model->get_branch_fund($postData["branch_id"]);
        }
       

        $available_balance = $branch_fund + $postData["amount"];

        $insert_ledger = [
        "branch_id"         => $postData["branch_id"],
        "type"              => 'credit',
        "user_id"           => session()->has('id') ? session()->get('id') : null,
        "ts"                => date("Y-m-d H:i:s"),
        "opening_amount"    => $branch_fund ?$branch_fund :0,
        "amount"            => $postData["amount"],
        "available_balance" => $available_balance
        ];


        if ($edit_id>0 || $edit_id != "") {
            $this->updateRowInDB("branch_fund", $insert_array, $where = ["id" => $edit_id]);
            $item_id = true;
        } else {
            unset($postData["id"]);
            $item_id = $this->insertRowInDB("branch_fund", $insert_array);
            $item_ledger = $this->insertRowInDB("rpt_ledger", $insert_ledger);
        }
        
        return $item_id;
    }

    // Get all streams list
    public function get_lists() 
    {
        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id'); 


        $builder = $this->db->table('branch')->select('id, branch_name, contact_person, contact_no, address, status'); 

        if ($checkAdmin != 1) {
            $builder->where('id', $branchId);
        }

        $datatable =  DataTable::of($builder)->filter(function ($builder, $request) {
            if (isset($request->status)) {
                $builder->where(["status" => $request->status]);
            }
            // $compiledQuery = $builder->getCompiledSelect();
            // echo $compiledQuery;
        })
            ->add('action', function ($row) {
                // $html = "";
                // if(checkPermission("branch_management", "is_view")) {
                  return  '<button type="button" class="btn btn-primary" data-post-type="master" data-act="ajax-modal" data-title="Edit Branch" data-action-url="' . base_url('branch/addForm/' . $row->id) . '">Edit</button> &nbsp;';
                // }
              
            }, 'last')
            ->edit('status', function ($row) {
                if ($row->status == 1)
                    return '<a href="javascript:;"><span class="badge badge-success">Active</span></a>';
                else
                    return '<a href="javascript:;"><span class="badge badge-danger">Inactive</span></a>';
            })
            ->hide('id')
            ->addNumbering()
            ->toJson();
        return $datatable;
    }


    // branch funds
    public function get_funds_lists() 
    {
        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id'); 

        $builder = $this->db->table('branch_fund f')->select('f.id, f.branch_id,b.branch_name, f.amount, f.description, f.ts, f.status')
        ->join("branch b", "b.id=f.branch_id","inner")
        ->orderBy("f.ts", "desc")
        ->orderBy("f.id", "desc"); 

        if ($checkAdmin != 1) {
            $builder->where('f.branch_id', $branchId);
        }
        
        $datatable =  DataTable::of($builder)->filter(function ($builder, $request) {
            if (isset($request->status)) {
                $builder->where(["f.status" => $request->status]);
            }
            if (isset($request->branch_id)) {
                $builder->where(["f.branch_id" => $request->branch_id]);
            }
            if (!empty($request->from_date) && empty($request->to_date)) {
                    // Only from_date is set — use exact match
                    $builder->where('DATE(f.ts)', $request->from_date);
                } elseif (!empty($request->from_date) && !empty($request->to_date)) {
                    // Both from_date and to_date are set — use range
                    $builder->where('DATE(f.ts) >=', $request->from_date);
                    $builder->where('DATE(f.ts) <=', $request->to_date);
                }
            // $compiledQuery = $builder->getCompiledSelect();
            // echo $compiledQuery;
        })
        ->edit('ts', function ($row) {
            return date("d-m-Y", strtotime($row->ts));
        })
        ->add('action', function ($row) {
                $html = "";
                if(checkPermission("branch_fund_management", "is_view")) {
                    $html .= '<button type="button" class="btn btn-primary" data-post-type="master" data-act="ajax-modal" data-title="Edit Branch Fund" data-action-url="' . base_url('branch/add_fund_form/' . $row->id) . '">Edit</button> &nbsp;';
                }
                return $html;
            }, 'last')
        ->edit('status', function ($row) {
                if ($row->status == 1)
                    return '<a href="javascript:;"><span class="badge badge-success">Active</span></a>';
                else
                    return '<a href="javascript:;"><span class="badge badge-danger">Inactive</span></a>';
            })
        ->hide('branch_id')
        ->hide('id')
        ->addNumbering()
            ->toJson();
        return $datatable;
    }
    

}
