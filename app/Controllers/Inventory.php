<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Crud_model;
use App\Models\Inventory_model;
use App\Models\Common_model;


class Inventory extends BaseController
{
    public $session;
    public $Inventory_model;
    public $Common_model;
    public $Crud_model;
    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $this->Inventory_model = new Inventory_model();
        $this->Common_model = new Common_model();
        $this->Crud_model = new Crud_model(); 
    }
    public function index()
    {
        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id');

        if ($checkAdmin) {
            $branchArray = $this->Crud_model->getAllRecords('branch', 'id, branch_name');
        } else {
            $branchArray = $this->Crud_model->getAllRecords('branch', 'id, branch_name', ['id' => $branchId]);
        }
        
        // $branch_id = session()->get('branch_id');
        try {
            $data = [
		    'title' => 'Inventory',
            'view' => 'inventory/viewInventory_list',
            'branch_fund'=> $this->Common_model->get_branch_fund($branchId),
            'branchArray' => $branchArray,
            'itemArray' => $this->Crud_model->get_ItemList(),

        ]; 
             
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }
 
    public function getInventoryLists(){
        try {
            $data = $this->Inventory_model->getInventory_list();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function addInventoryForm($editId = null)
    {
        try {
            $data = [];

            $branch_id = session()->get('branch_id');
           
            if ($editId > 0) {
                $data = $this->Crud_model->getAllRecords("inventory_detail", '',["id" => $editId]);
            }
            $view_data = [
                'data'  => $data,
                'branch_fund'=> $this->Common_model->get_branch_fund($branch_id),
                'itemArray' => $this->Crud_model->get_ItemList(),
            ];


            return $this->template->view("inventory/addInventory", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }

    }

    public function saveInventoryDetails($id = null){
        if ($this->request->isAJAX()) {
            $insert_data = $this->request->getPost();

            if ($this->request->getPost('id')) {
                $item_id = $this->Inventory_model->add_inventory($insert_data, $this->request->getPost('id'));
                $item_id = true;
            } else {
                $item_id = $this->Inventory_model->add_inventory($insert_data, '');                
            }

            if ($item_id) {
                echo json_encode(array("success" => true, 'message' => 'Success'));
            } else {
                echo json_encode(array("error" => false, 'message' => "Error"));
            }
        } else {
            exit('No direct script access allowed');
        }
    }

    public function viewSingle_inventory($edit_id = null){
        try {
            $data = [];
            $joins = [
                [
                    'table' => 'ms_item i',
                    'condition' => 'a.item_id = i.id',
                    'jointype' => 'left'
                ]
            ];
          
            if ($edit_id > 0) {
                $data = $this->Crud_model->getAllRecords("inventory_detail a ", ' a.*,i.item_name ',["a.id" => $edit_id], $joins);
            }

            // $view_data = [
            //     'title'=>'View Inventory',
            //     'data'  => $data,
            //     'view'=>'inventory/viewSingle_inventory',
            // ];
            $view_data = [
                'data'  => $data,
            ];


            return $this->template->view("inventory/viewSingle_inventory", $view_data);
    
            return $this->template->view("template/admin", $view_data);  
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function deleteInventory($d_id = null)
    {
        if ($this->request->isAJAX()) {

            // get ids from POST (ajax)
            $ids = $this->request->getPost('ids'); // expects array: [39,40,41]

            if (!empty($ids) && is_array($ids)) {
                $result = false;

                foreach ($ids as $id) {
                    $result = $this->Crud_model->deleteRowFromDB('inventory_detail', [], 'id', $id);
                }

                if ($result) {
                    return $this->response->setJSON([
                        "success" => true,
                        "message" => "Success"
                    ]);
                } else {
                    return $this->response->setJSON([
                        "error" => true,
                        "message" => "Error"
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    "error" => true,
                    "message" => "No IDs provided"
                ]);
            }

        } else {
            exit('No direct script access allowed');
        }
    }

    public function toggleVisibility($d_id = null){
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $status = $this->request->getPost('status');
            if(!$id){
                $id= $d_id;
            }
            $result = $this->Inventory_model->recived_Inventory($id,$status);
            if($result){
                return json_encode(array("success" => true, 'message' => 'Success'));
            } else {
                return json_encode(array("error" => false, 'message' => "Error"));
            }
        }else {
            exit('No direct script access allowed');
        }
    }

    public function printInventory()
    {
        try {
            $branch_id = $this->request->getPost('branch_id');
            $status    = $this->request->getPost('status');
            $item      = $this->request->getPost('item');

            $where = [];
            $data = [];


            if (!empty($branch_id)) {
                $where['a.branch_id'] = $branch_id;
            }

            if ($status !== '' && $status !== null) {
                $where['a.status'] = $status;
            }

            if (!empty($item)) {
                $where['a.item_id'] = $item;
            }

            $joins = [
                [
                    'table' => 'ms_item i',
                    'condition' => 'a.item_id = i.id',
                    'jointype' => 'left'
                ],
                [
                    'table' => 'branch b',
                    'condition' => 'a.branch_id = b.id',
                    'jointype' => 'left'
                ]
            ];
            $data = $this->Crud_model->getAllRecords("inventory_detail a ", ' a.*,i.item_name,b.branch_name ',  $where, $joins);
            
            $view_data = [
                'data'  => $data,
            ];

            return $this->template->view("inventory/inventoryPrint", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function generateInventoryPdf()
    {
        try {
            $branch_id = $this->request->getGet('branch_id');
            $status    = $this->request->getGet('status');
            $item      = $this->request->getGet('item');

            $where = [];
            
            if (!empty($branch_id)) {
                $where['a.branch_id'] = $branch_id;
            }

            if ($status !== '' && $status !== null && $status !== 'all') {
                $where['a.status'] = $status;
            }

            if (!empty($item)) {
                $where['a.item_id'] = $item;
            }

            $joins = [
                ['table' => 'ms_item i', 'condition' => 'a.item_id = i.id', 'jointype' => 'left'],
                ['table' => 'branch b', 'condition' => 'a.branch_id = b.id', 'jointype' => 'left']
            ];
            $order_by = ['a.ts' => 'DESC'];
           
             
            $data = $this->Crud_model->getAllRecords("inventory_detail a", 'a.*, i.item_name, b.branch_name', $where, $joins,[], $order_by);

            // Calculate totals
            $total = [
                'gross_weight' => 0,
                'diamond'      => 0,
                'silver'       => 0,
                'net_weight'   => 0,
                'total_amount'       => 0,
            ];

            foreach ($data as $row) {
                $total['gross_weight'] += isset($row['gross_weight']) ? floatval($row['gross_weight']) : 0;
                $total['diamond']      += isset($row['diamond']) ? floatval($row['diamond']) : 0;
                $total['silver']       += isset($row['silver']) ? floatval($row['silver']) : 0;
                $total['net_weight']   += isset($row['net_weight']) ? floatval($row['net_weight']) : 0;
                $total['total_amount']       += isset($row['total_amount']) ? floatval($row['total_amount']) : 0;
            }

            $view_data = [
                'data'  => $data,
                'total' => $total
            ];

            $html = view('inventory/inventory_pdf_template', $view_data);

            $timestamp = date('d-m-Y_h-i-A'); // Format: 30-07-2025_10-15-AM
            $filename = "inventory_report_" . $timestamp . ".pdf";

            $pdf = new \App\Libraries\PdfGenerator();
            $pdf->generate($html, $filename);
            exit;

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function receivedInventory($d_id = null){
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
           
            if(!$id){
                $id= $d_id;
            }
            $result = $this->Inventory_model->receivedInventory($id);
            if($result){
                return json_encode(array("success" => true, 'message' => 'Success'));
            } else {
                return json_encode(array("error" => false, 'message' => "Error"));
            }
        }else {
            exit('No direct script access allowed');
        }
    }



}


    


