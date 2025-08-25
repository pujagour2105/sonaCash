<?php

namespace App\Models;

use \Hermawan\DataTables\DataTable;
use App\Models\Common_model; 

class Distribution_model extends Crud_model
{
 
    protected $table = 'ms_customer';
    private $Common_model;
    function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model();
    }

    public function getAllList(){
        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id'); 

        $builder = $this->db->table('amount_distribution a')
                    ->select('a.id,b.branch_name,a.customer_name,a.amount,a.duration,  
                    a.description, DATE(a.ts) as only_date, a.status,a.is_approved ')
                    ->join('branch b', 'a.branch_id = b.id')
                    ->orderBy('a.id', 'desc');
        
        // Check if the user is an admin or has a branch ID
        if (!$checkAdmin && $branchId) {
            $builder->where('a.branch_id', $branchId);
        }

        $datatable = DataTable::of($builder)
            ->filter(function ($builder, $request) {
                if (isset($request->status)) {
                    $builder->where(['a.status' => $request->status]);
                }
                if (isset($request->branch_id)) {
                    $builder->where(['a.branch_id' => $request->branch_id]);
                }
                if (!empty($request->from_date)) {
                    $builder->where('DATE(a.ts) >=', $request->from_date);
                }
                
                if (!empty($request->to_date)) {
                    $builder->where('DATE(a.ts) <=', $request->to_date);
                }
                if (isset($request->isApproved)) {
                    if ($request->isApproved !== "all") {
                       $builder->where('a.is_approved', $request->isApproved);
                    }
                }else{
                    $builder->where('a.is_approved', 0);
                }


            })

            ->edit('name', function ($row) {
                return ucfirst($row->name);
            }, 'last')
            ->edit('only_date', function ($row) {
                return date('d-m-Y', strtotime($row->only_date));
            }, 'last')

            ->add('action', function ($row) {
                $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
                $html = '';
                if (checkPermission('amt_distribution_management', 'is_edit')) {
                    $html .= '<button type="button" class="btn btn-sm btn-warning" style="margin-right: 5px;" data-act="ajax-modal"
                                         title="View Record" 
                                        data-toggle="tooltip" 
                                        data-title="Amount Distribution" data-action-url="' . base_url('distribution/ViewSingleDistribution/' . $row->id) . '"><i class="fa fa-eye"></i> </button>';

                    if($checkAdmin ==1){
                        if($row->status==1 && $row->is_approved == 0){
                            
                                $html .= '<button type="button" class="btn btn-sm btn-success editData" style="margin-right: 5px;" data-act="ajax-modal"
                                        title="Edit record" 
                                        data-toggle="tooltip" 
                                        data-title="Edit Amount Distribution" data-action-url="' . base_url('distribution/addAmountDistribution/' . $row->id) . '"><i class="fa fa-edit fa-xs"></i></button>';
                    
                                $html .= '<button type="button" class="btn btn-sm btn-info editData" style="margin-right: 5px;" data-act="ajax-modal"
                                        title="Approve record" 
                                        data-toggle="tooltip" 
                                        data-title="Approve Action" data-action-url="' . base_url('distribution/ActionApprove/' . $row->id) . '"><i class="fa fa-check fa-xs"></i> </button>';
                           
                        }
                        
                        
                    }
                   
                }
                return $html;
            }, 'last')
            ->edit('is_approved', function ($row) {
                if ($row->is_approved == '1')
                    return '<span class="badge badge-info">Approved</span>';
                elseif ($row->is_approved == '2')
                    return '<span class="badge badge-danger">Rejected</span>';
                else
                    return '<span class="badge badge-warning">Pending</span>';
            })
            ->edit('status', function ($row) {
                if ($row->status == '1')
                    return '<span class="badge badge-success">Active</span>';
                else
                    return '<span class="badge badge-danger">Inactive</span>';
            })->hide('id')
            ->hide('status')
            ->addNumbering()

            ->toJson();

        return $datatable;
    }

   public function add_distribution_Data($data, $id = null)
    {
        $insert_user = [
            "branch_id"     => session()->has('branch_id') ? session()->get('branch_id') : null,
            "customer_name" => $this->db->escapeString($data['name']),
            "amount"        => $this->db->escapeString($data['amount']),
            "description"   => $data['description'],
            "is_approved"   => 0,
            "duration"      => $this->db->escapeString($data['duration']),
            "request_by"    => get_session('id'),
            // "status"        => $this->db->escapeString($data['status']),
        ];
    
        $insert_user["ts"] = date("Y-m-d H:i:s");
        
        if ($id) {
            $this->updateRowInDB("amount_distribution", $insert_user, ["id" => $id]);
        } else {
            $id = $this->insertRowInDB("amount_distribution", $insert_user);
        }
    
        return true;
    }


    public function ApprovedData($data,$id){
        $getRecord = $this->getRecordOnId("amount_distribution", ["id" => $id]);

        if ($id && !empty($getRecord)) {

            $is_approved=$data['is_approved'];
            // Update approval info
            $insert_user = [
                "is_approved"   => $data['is_approved'],
                "remarks"       => $data['remark'],
                "approved_by"   =>  get_session('id'),
                "approved_date" => date("Y-m-d H:i:s"),
            ];
            $result=$this->updateRowInDB("amount_distribution", $insert_user, ["id" => $id]);

            if($result && $is_approved==1){
                $lastLedger = $this->db->table('rpt_ledger')
                ->where('branch_id', $getRecord["branch_id"])
                ->orderBy('ts', 'DESC')
                ->orderBy('id', 'DESC')
                ->get()
                ->getRowArray();

                if ($lastLedger) {
                    
                    $branch_fund = $lastLedger['available_balance'];
                } else {
                    $branch_fund = $this->Common_model->get_branch_fund($getRecord["branch_id"]);
                }

                $available_balance = $branch_fund - $getRecord["amount"]; // subtract for debit

                // Step 2: Prepare insert array for debit
                $insert_ledger = [
                    "branch_id"         => $getRecord["branch_id"],
                    "type"              => 'debit', // Debit entry
                    "user_id"           => session()->has('id') ? session()->get('id') : null,
                    "ts"                => date("Y-m-d H:i:s"), // timestamp
                    "opening_amount"    => $branch_fund, // Opening balance (last available or base branch fund)
                    "amount"            => $getRecord["amount"], // Debit amount
                    "available_balance" => $available_balance // New available balance after debit
                ];
                // Insert into ledger table
                $id=$this->insertRowInDB("rpt_ledger", $insert_ledger);
                return true;
            }else{
                return true;
            }
        }

    }
    
    public function distributionStatusChange($id){
        if ($id) {
            $insert_user = [
                "status"   => 0,
            ];
            $this->updateRowInDB("amount_distribution", $insert_user, ["id" => $id]);
            return true;
        }
    }
}
