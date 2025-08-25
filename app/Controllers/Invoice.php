<?php

namespace App\Controllers;

use App\Models\Crud_model;
use App\Models\Customer_model;
use App\Models\Invoice_model;


class Invoice extends BaseController
{
   
    public $session;
 
    public $Invoice_model;
    public $Crud_model;
    public $Customer_model;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();

        $this->Invoice_model = new Invoice_model();
        $this->Crud_model = new Crud_model();
        $this->Customer_model = new Customer_model();
    }


    public function index($id=null)
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
		    'title' => 'Purchase Invoice',
            'branchArray' => $branchArray,
            'view' => 'invoice/invoice_index',
        ];     
    
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }

    }

    public function addInvoiceForm($id=null){

        try {
            $data = [];
            
            if ($id > 0) {
                $data = $this->Crud_model->getAllRecords("invoice v",);
            }

            $view_data = [
                'data'  => $data,
                'customerArray' => $this->Crud_model->get_customerList(),
                'itemArray' => $this->Crud_model->get_ItemList(),
            ];

            return $this->template->view("invoice/add_invoice_form", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function getInvoiceList(){
        try {
            $data = $this->Invoice_model->get_Invoice();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        } 
    }
 
    public function getCustomerDetails($id=null)
    {
        
        $id= $this->request->getPost('id');
    
        $customer  = $this->Crud_model->getRecordOnId("ms_customer", ["id" => $id], "");

        if ($customer) {
            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'mobile' => $customer['mobile'],
                    'address' => $customer['address']
                ]
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Customer not found'
            ]);
        }
    }

    public function saveInvoice()
    {
        try {
            if ($this->request->isAJAX()) {

                $filesize = 10000000; // 10MB
                $path = 'uploads/invoice/pan_card/'; // Path to save the uploaded file
                isDirectory($path);
                $file = $this->request->getFile('pan_card');
                $insert_data = $this->request->getPost();

                // Required fields: customer_id, amount, payment_mode, etc.
                $insert_data['branch_id'] = session('branch_id');          // assuming stored in session
                $insert_data['generated_by'] = session('user_id');         // assuming stored in session
                
                // Handle PAN card file upload
                if ($file && $file->isValid() && !empty($file->getName())) {
                    $upload = $this->Crud_model->uploadOneFile($file, "file", $filesize, $path);
                    if (!empty($upload["filename"])) {
                        $insert_data['pan_card_file'] = $upload["filename"];
                    }
                }

                // Save invoice
                $item_id = $this->Invoice_model->saveInvoiceData($insert_data);
                if ($item_id) {
                    echo json_encode([
                        "success" => true,
                        "message" => "Invoice generated successfully",
                        "redirect_url" => base_url("invoice/printInvoice/" . $item_id)
                    ]);
                } else {
                    echo json_encode([
                        "error" => true,
                        "message" => "Failed to save invoice"
                    ]);
                }

            } else {
                exit('No direct script access allowed');
            }

        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function printInvoice($id)
    {
       
        $data = $this->Invoice_model->getInvoiceDetails($id); // You’ll create this method

        if (!$data) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(" Purchase Invoice not found.");
        }


        $data = [
            'title' => 'Invoice',
            'view' => 'invoice/print_invoice',
            'data' => $data['billing'],
            'branch_name'=>$data['branch_name'],
            'items' => $data['items'],
        ];

        return $this->template->view("template/admin", data: $data);
        // return view('invoice/print_invoice', ['data' => $data]);
    }


}
