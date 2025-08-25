<?php

namespace App\Controllers;

use App\Models\Customer_model;
use App\Models\Crud_model;
// use App\Models\Staff_model;
class Customer extends BaseController
{
   
    public $session;
    
    public $Customer_model;
    public $Crud_model;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $this->Customer_model = new Customer_model();
        $this->Crud_model = new Crud_model();
    }


    public function index()
    {
    
        try {
            $data = [
		    'title' => 'Customer list',
            'view' => 'customer/customer_list'
        ];            
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging(payload: $e->getMessage());
        }

       
    }

    public function getCustomerList()
    {
       
        try {
            $data = $this->Customer_model->getCustomerList();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function addCustomerForm($edit_id = null)
    {
       
        try {
            $data = [];
            if ($edit_id > 0) {
                $data = $this->Crud_model->getRecordOnId("ms_customer", ["id" => $edit_id], "");
            }
            $view_data = [
                'data'  => $data,
                'roleArray' => $this->Crud_model->get_roleList("1"),
            ];
            return view('customer/addCustomer_form', $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function saveCustomerData($edit_id = null){
    if ($this->request->isAJAX()) {
            $filesize = 10000000; // 10MB
            $dirName = "";
            $path = IMAGES_FOLDER . $dirName;
            isDirectory($path);

            $file = $this->request->getFile('image');

    
           
           
            $insert_data = $this->request->getPost();
            
            $ext_user = $this->Crud_model->getRecordOnId("ms_customer", ["mobile" => $insert_data["mobile"]], "id");
            if($ext_user) {
                if($insert_data["id"] === $ext_user["id"]) {
                    //
                } else {
                    echo json_encode(array("error" => false, 'message' => "User already exist,")); exit;                    
                }
            }
             

            if ($this->request->getPost('id')) {
                // Editing an existing record
                $editId = $this->request->getPost('id');
                $existing = $this->Crud_model->getRecordOnId("ms_customer", ["id" => $editId]);
            
                if ($file && $file->isValid() && !empty($file->getName())) {
                    // Delete old image if it exists
                    if (!empty($existing['image'])) {
                        $oldImage = $path . $existing['image'];
                        if (file_exists($oldImage)) {
                            unlink($oldImage);
                        }
                    }
            
                    // Upload new file
                    $upload = $this->Crud_model->uploadOneFile($file, "file", $filesize, $path);
                    if (!empty($upload["filename"])) {
                        $insert_data['image'] = $upload["filename"];
                    }
                } else {
                    // Keep old image if no new file uploaded
                    if (!empty($existing['image'])) {
                        $insert_data['image'] = $existing['image'];
                    }
                }
            
                $item_id = $this->Customer_model->add_customer($insert_data, $editId);
            
            } else {
                // New customer
                if ($file && $file->isValid() && !empty($file->getName())) {
                    $upload = $this->Crud_model->uploadOneFile($file, "file", $filesize, $path);
                    if (!empty($upload["filename"])) {
                        $insert_data['image'] = $upload["filename"];
                    }
                }
            
                $item_id = $this->Customer_model->add_customer($insert_data, '');
            }
            

            if ($item_id) {
                echo json_encode([
                    "success" => true,
                    "message" => "Success",
                    "redirect" => base_url('valuation/index/' . $item_id) . '?modal=1'
                ]);
                return;
                // valuation/addValuationForm/'.$row->customer_id.
                // return redirect()->to('../valuation/addValuationForm/' . $item_id);
                // echo json_encode(array("success" => true, 'message' => 'Success',''));
            } else {
                echo json_encode(array("error" => false, 'message' => "Error"));
            }
        } else {
            exit('No direct script access allowed');
        }
    }

    function customerValuationData($customer_id){
        try {
            $data = [];
            if ($customer_id > 0) {
                $data = $this->Crud_model->getAllRecords("valuation_details", '',["customer_id" => $customer_id], "");
            }
            $view_data = [
                'data'  => $data,
                'customer_id'  => $customer_id,
            ];
            return $this->template->view("valuation/view_valuation", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

}