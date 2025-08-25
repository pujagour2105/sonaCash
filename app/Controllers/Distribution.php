<?php

namespace App\Controllers;

use App\Models\Distribution_model;
use App\Models\Crud_model;

class Distribution extends BaseController
{
   
    public $session;
    // public $Staff_model;
    public $Distribution_model;
    public $Crud_model;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $this->Distribution_model = new Distribution_model();
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
        
    
        try {
            $data = [
		    'title' => 'Amount Distribution list',
            'branchArray' => $branchArray,
            'view' => 'amount_distribution/distribution_list'
        ];            
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging(payload: $e->getMessage());
        }

       
    }

    public function getAllList()
    {
       
        try {
            $data = $this->Distribution_model->getAllList();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function addAmountDistribution($id=null){
        try {
            $data = [];
            if ($id > 0) {
                $data = $this->Crud_model->getRecordOnId("amount_distribution", ["id" => $id]);
            }
            $view_data = [
                'data'  => $data,
            ];

            return $this->template->view("amount_distribution/add_distribution", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }

    }

    public function saveAmtDistribution($id=null){
        if ($this->request->isAJAX()) {
            $insert_data = $this->request->getPost();

            if ($this->request->getPost('id')) {
                $item_id = $this->Distribution_model->add_distribution_Data($insert_data, $this->request->getPost('id'));
                $item_id = true;
            } else {
                $item_id = $this->Distribution_model->add_distribution_Data($insert_data, '');                
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

    public function ActionApprove($id){
        try {
            $data = [];
            if ($id > 0) {
                $data = $this->Crud_model->getRecordOnId("amount_distribution", ["id" => $id]);
            }
            $view_data = [
                'data'  => $data,
            ];

            return $this->template->view("amount_distribution/action_approve", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function action_approveDistribution($editid=null){
        if ($this->request->isAJAX()) {
            $insert_data = $this->request->getPost();
            $id = $this->request->getPost('id');
            if(!$id){
                $id= $editid;
            }
           
            $result = $this->Distribution_model->ApprovedData($insert_data,$id);
            if($result){
                return json_encode(array("success" => true, 'message' => 'Success'));
            } else {
                return json_encode(array("error" => false, 'message' => "Error"));
            }
        }else {
            exit('No direct script access allowed');
        }
    }

    public function ViewSingleDistribution($id){
        try {
            $data = [];
            if ($id > 0) {
                $data = $this->Crud_model->getRecordOnId("amount_distribution", ["id" => $id]);
            }
            $view_data = [
                'data'  => $data,
            ];

            return $this->template->view("amount_distribution/view_amountDistribution", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function deleteDistribution($id){
        if ($this->request->isAJAX()) {
            $id2 = $this->request->getPost('id');
            if(!$id){
                $id= $id2;
            }
           
            
            $result = $this->Distribution_model->distributionStatusChange($id);
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