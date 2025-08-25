<?php

namespace App\Controllers;

use App\Models\Branch_model;
use App\Models\Crud_model;

class Branch extends BaseController
{
    public $session;
    public $Branch_model;
    public $Crud_model;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $this->Branch_model = new Branch_model();
        $this->Crud_model = new Crud_model();
    }

    // List of branch
    public function index($url_value = null)
    {
        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id');

        if ($checkAdmin) {
            $branchArray = $this->Crud_model->getAllRecords('branch', 'id, branch_name',['status'=>1]);
        } else {
            $branchArray = $this->Crud_model->getAllRecords('branch', 'id, branch_name', ['id' => $branchId,'status'=>1]);
        }
        
        try {
            $data = [
		    'title' => 'Branch',
            'branchArray' => $branchArray,
            'view' => 'branch/branch_list'
        ];            
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Get branch data
    public function get_lists()
    {
        try {
            $data = $this->Branch_model->get_lists();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Add role form
    public function addForm($edit_id = null)
    {
        try {
            $data = [];
            if ($edit_id > 0) {
                $data = $this->Crud_model->getRecordOnId("branch", ["id" => $edit_id], "");
            }
            $view_data = [
                'data'  => $data
            ];
            return $this->template->view("branch/add_form", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Save role
    function savData($edit_id = null)
    {
        if ($this->request->isAJAX()) {
            $id = '';
            $postData=$this->request->getPost();
            $id=$postData['id'];


            $id_data = check_duplicate_master_data("branch", "branch_name", trim($postData["branch_name"]),['id !=' => $id]); // table, col, value
            if($id_data) {
                echo json_encode(array("error" => false, 'message' => "Branch already exsit, Please try with another name!")); exit;
            }
            
            $item_id = $this->Branch_model->save_data($postData);

            if ($item_id) {
                echo json_encode(array("success" => true, 'message' => 'Success'));
            } else {
                echo json_encode(array("error" => false, 'message' => "Error"));
            }
        } else {
            exit('No direct script access allowed');
        }
    }
    

    // branch funds
    public function funds($url_value = null)
    {
        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id');

        if ($checkAdmin) {
            $branchArray = $this->Crud_model->getAllRecords('branch', 'id, branch_name',['status'=>1]);
        } else {
            $branchArray = $this->Crud_model->getAllRecords('branch', 'id, branch_name', ['id' => $branchId,'status'=>1]);
        }
        try {
            $data = [
		    'title' => 'Branch',
            'branchArray' => $branchArray,
            'view' => 'branch/branch_fund_list'
        ];            
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    } 

    // Get branch data
    public function get_funds_lists()
    {
        try {
            $data = $this->Branch_model->get_funds_lists();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Add role form
    public function add_fund_form($edit_id = null)
    {
        try {
            $data = [];
            if ($edit_id > 0) {
                $data = $this->Crud_model->getRecordOnId("branch_fund", ["id" => $edit_id], "");
            }
            $view_data = [
                'data'  => $data,
                "branchArray" => $this->Crud_model->getBranchList(),
            ];
            return $this->template->view("branch/add_fund_form", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }
    
    // Save role
    function savFundData($edit_id = null)
    {
        if ($this->request->isAJAX()) {
            $id = '';
            $postData=$this->request->getPost();
                
            // $id_data = check_duplicate_master_data("branch", "branch_name", trim($postData["branch_name"])); // table, col, value
            // if($id_data) {
            //     echo json_encode(array("error" => false, 'message' => "Branch already exsit, Please try with another name!")); exit;
            // }
            
            $item_id = $this->Branch_model->save_branch_fund_data($postData);

            if ($item_id) {
                echo json_encode(array("success" => true, 'message' => 'Success'));
            } else {
                echo json_encode(array("error" => false, 'message' => "Error"));
            }
        } else {
            exit('No direct script access allowed');
        }
    }
    

}
