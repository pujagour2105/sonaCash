<?php

namespace App\Models;
 
use \Hermawan\DataTables\DataTable;


class Inventory_model extends Crud_model
{
    protected $table            = 'inventory_detail';

    function __construct()
    {
        parent::__construct();
    }


    function getInventory_list(){
        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id'); 
        
        $builder = $this->db->table('inventory_detail v')
                    ->select('v.id, b.branch_name, date(v.ts) as only_date, v.item_id,i.item_name,
                     v.gross_weight, v.diamond, v.silver, v.net_weight, v.percentage, v.gold_rate,
                      v.silver_rate,v.diamond_rate, v.total_amount,v.status')
                    ->join('branch b', 'v.branch_id = b.id')
                    ->join('ms_item i', 'v.item_id = i.id','left')
                    ->orderBy('v.id', 'desc');

        // Check if the user is an admin or has a branch ID
        if (!$checkAdmin && $branchId) {
            $builder->where('v.branch_id', $branchId);
            $builder->where('v.status !=', 2);
        }

    
        // DataTable logic
        $datatable = DataTable::of($builder)

            ->filter(function ($builder, $request) {
             
                if (isset($request->status)) {
                    $builder->where(['v.status' => $request->status]);
                }

                if (!empty($request->item_name)) {
                    $item_id=$request->item_name;
                    if (!empty($request->item_name)) {
                        $builder->like('v.item_id', $item_id);
                    }
                
                }
                if (!empty($request->branch_id)) {
                    $branch_id=$request->branch_id;
                    if (!empty($request->branch_id)) {
                        $builder->like('v.branch_id', $branch_id);
                    }
                
                }
                if (!empty($request->from_date)) {
                    $from_date = $request->from_date;
                    $builder->where('date(v.ts) >=', $from_date);
                }
                if (!empty($request->to_date)) {
                    $to_date = $request->to_date;
                    $builder->where('date(v.ts) <=', $to_date);
                }
            
            })
            // ->addNumbering()
            ->add('select', function ($row) {
                return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
            }, 'first')
                    
            ->edit('item_name', function ($row) {
                return ucfirst($row->item_name);
            }, 'last')
            ->edit('only_date', function ($row) {
                return date('d-m-Y', strtotime($row->only_date));
            }, 'last')
            
            ->add('action', function ($row) {
                $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
                $html = '';
                if (checkPermission('inventory_management', 'is_edit')) {
                    $html .= '<button type="button" class="btn btn-sm btn-primary" style="margin-right: 5px;" data-act="ajax-modal"  data-action-url="' . base_url('inventory/viewSingle_inventory/' . $row->id) . '"><i class="fa fa-eye fa-xs"></i></button>';
                
                    if ($checkAdmin == 1) {
                        // $html .= '<button type="button" class="btn btn-sm btn-success" style="margin-right: 5px;" data-act="ajax-modal" data-title="Edit Inventory" data-action-url="' . base_url('inventory/addInventoryForm/' . $row->id) . '"><i class="fa fa-edit fa-xs"></i></button>';
                         
                        if ($row->status == 0) {
                            // Pending: Show "Received Inventory" button
                            $html .= '<button type="button" class="btn btn-default btnChangeStatus" 
                                            style="margin-right: 5px;"  
                                            data-bs-toggle="tooltip" 
                                            title="Mark as Received"  
                                            data-title="Are you sure you have received the item and want to mark it as received?" 
                                            data-action-url="' . base_url('inventory/receivedInventory/' . $row->id) . '">
                                            <i class="fa fa-check-circle text-success"></i>
                                    </button>';
                        } else {
                            if ($row->status == 1 || $row->status == 2) {
                                // Determine current visibility
                                $isVisible = ($row->status == 1); // true if currently visible to branch

                                $buttonClass = $isVisible ?  'btn-success':'btn-danger' ;
                                $icon = $isVisible ?'fa-eye' :'fa-eye-slash'  ;
                                $confirmText = $isVisible
                                    ? 'Are you sure you want to hide this item from the branch?'
                                    : 'Are you sure you want to show this item to the branch again?';
                                $tooltipText = $isVisible
                                    ? 'Currently Visible to Branch — Click to Hide'
                                    : 'Currently Hidden from Branch — Click to Show';

                                $actionUrl = base_url('inventory/toggleVisibility/' . $row->id);

                                // $html .= '<button type="button" 
                                //                 class="btn btn-sm ' . $buttonClass . ' btnIsDeleted" 
                                //                 style="margin-right: 5px;" 
                                //                 data-bs-toggle="tooltip"
                                //                 data-status="'.$row->status . '"
                                //                 title="' . $tooltipText . '"
                                //                 data-title="' . $confirmText . '" 
                                //                 data-action-url="' . $actionUrl . '">
                                //                 <i class="fa ' . $icon . '"></i>
                                //         </button>';
                            }
                        }
                    }

                    

                   
                }
                return $html;
            }, 'last')

            ->hide('item_id')
            ->hide('id')
            ->hide('status')
           
            ->toJson();

        return $datatable;

    }

    function add_inventory($data, $id = null) {
        $insertedIds = []; 
        $total_amount = 0;
        $branch_id = session()->get('branch_id');

        foreach ($data['item_name'] as $index => $item_name) {

            // Determine inventory_id
            if (!isset($data['inventory_id'])) {
                $builder = $this->db->table('inventory_detail');
                $maxInventoryId = $builder->selectMax('inventory_id')->get()->getRow();
                $inventory_id = isset($maxInventoryId->inventory_id) ? $maxInventoryId->inventory_id + 1 : 1;
            } else {
                $inventory_id = $this->db->escapeString($data['inventory_id'][$index]);
            }

            $amount = (float) $data['amount'][$index];
            $total_amount += $amount;  // ✅ fix: accumulate all item amounts

            $insert_array = [
                'inventory_id'    => $inventory_id,
                'branch_id'       => $branch_id ?: null,
                'item_id'         => $this->db->escapeString($item_name),
                'gross_weight'    => $this->db->escapeString($data['gross_wt'][$index]),
                'dust'            => $this->db->escapeString($data['dust'][$index]),
                'diamond'         => $this->db->escapeString($data['diamond'][$index]),
                'silver'          => $this->db->escapeString($data['silver'][$index]),
                'net_weight'      => $this->db->escapeString($data['net_wt'][$index]),
                'percentage'      => $this->db->escapeString($data['percentage'][$index]),
                'gold_rate'       => $this->db->escapeString($data['gold_rate'][$index]),
                'diamond_rate'    => $this->db->escapeString($data['dia_rate'][$index]),
                'silver_rate'     => $this->db->escapeString($data['silver_rate'][$index]),
                'total_amount'    => $amount,
                'round_type'      => $data['round_type'][$index],
                'round_value'     => $this->db->escapeString($data['round'][$index]),
                'status'          => '0',
                'update_request'  => '0',
                'ts'              => date("Y-m-d H:i:s")
            ];

            if (!empty($id)) {
                $this->updateRowInDB("inventory_detail", $insert_array, ["id" => $data['id'][$index]]);
                $insertedIds = true;
            } else {
                $insertedId = $this->insertRowInDB("inventory_detail", $insert_array);
                $insertedIds[] = $insertedId;
            }
        }


        // print_r($total_amount);
        // exit;
        // Insert Ledger Debit after inserting inventory records
        if (!empty($insertedIds)) {
            $lastLedger = $this->db->table('rpt_ledger')
                ->where('branch_id', $branch_id)
                ->orderBy('ts', 'DESC')
                ->orderBy('id', 'DESC')
                ->get()
                ->getRowArray();

            if ($lastLedger) {
                $branch_fund = $lastLedger['available_balance'];
            } else {
                $branch_fund = $this->Common_model->get_branch_fund($branch_id);
            }

            $available_balance = $branch_fund - $total_amount;

            $insert_ledger = [
                "branch_id"         => $branch_id,
                "type"              => 'debit',
                "user_id"           => session()->get('id'),
                "ts"                => date("Y-m-d H:i:s"),
                "opening_amount"    => $branch_fund,
                "amount"            => $total_amount,
                "available_balance" => $available_balance
            ];

            $this->insertRowInDB("rpt_ledger", $insert_ledger);
        }

        return $insertedIds;
    }

    function recived_Inventory($id = null,$status = null)
    {
        if ($id) {
            if ($status ==1) {
                $status = 2; 
            }else{
                $status = 1; 
            }
            $insert_array = [
                'status'  =>$status,
            ];
            $this->updateRowInDB("inventory_detail", $insert_array, ["id" => $id]); 
            $insertedIds=true;
            return $insertedIds;
        } else {
            return false;
        }
    }


    function receivedInventory($id = null)
    {
        if ($id) {
            $insert_array = [
                'status'  => 2,
            ];
            $this->updateRowInDB("inventory_detail", $insert_array, ["id" => $id]); 
            $insertedIds=true;
            return $insertedIds;
        } else {
            return false;
        }
    }
 
}
