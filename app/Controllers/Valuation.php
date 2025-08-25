<?php

namespace App\Controllers;

use App\Models\Valuation_model; 
use App\Models\Crud_model;
// use App\Models\Staff_model;
class Valuation extends BaseController
{
   
    public $session;
    // public $Staff_model;
    public $Valuation_model;
    public $Crud_model;
 
    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        // $this->Staff_model = new Staff_model();
        $this->Valuation_model = new Valuation_model();
        $this->Crud_model = new Crud_model();
    }


    public function index($id=null)
    {
        if ($id !== null) {
            // Use $id here
           $customer_id= $id;
        } else {
            // Handle the case when $id is null
            $customer_id = null;
        }
 
        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id');

        if ($checkAdmin) {
            $branchArray = $this->Crud_model->getAllRecords('branch', 'id, branch_name');
        } else {
            $branchArray = $this->Crud_model->getAllRecords('branch', 'id, branch_name', ['id' => $branchId]);
        }
        

        try {
            $data = [
		    'title' => 'Valuation list',
            'view' => 'valuation/valuation_list',
            'branchArray' => $branchArray,
            'customerArray' => $this->Crud_model->get_customerList(),
            // 'customer_id' => $customer_id,
             
        ]; 
             
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }

    }

    public function addValuationForm( $customer_id = null,$edit_id = null)
    {
        try {
            $data = [];
            
            if ($edit_id > 0) {
                $joins = [
                    [
                        'table' => ' valuation_details d ',
                        'condition' => 'd.valuation_id=v.id ',
                        'jointype' => 'left'
                    ]
                ];
                $data = $this->Crud_model->getAllRecords(
                    "valuation v",
                    "v.*, d.id AS `val_id`,d.item_id,d.net_weight,d.dust,d.gross_weight,d.rate,d.amount,d.round_type,d.round_value,d.amount",
                    ["v.id" => $edit_id],
                    $joins
                );
            }

            $customer_id = $customer_id ?? ($data['customer_id'] ?? null);

            $view_data = [
                'data'  => $data,
                'customerArray' => $this->Crud_model->get_customerList(),
                'itemArray' => $this->Crud_model->get_ItemList(),
                'customer_id' => $customer_id?? null,
            ];

            // print_r($view_data);
            // exit;

            return $this->template->view("valuation/addValuationForm", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function getValuationLists()
    {
        try {
            $data = $this->Valuation_model->getValuationList();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }
    function saveValuationData($edit_id = null)
    {
        if ($this->request->isAJAX()) {
            $insert_data = $this->request->getPost();

            if ($this->request->getPost('id')) {
                $item_id = $this->Valuation_model->add_Valuation($insert_data, $this->request->getPost('id'));
                $item_id = true;
                $edit=true;
            } else { 
                $edit=false;
                $item_id = $this->Valuation_model->add_Valuation($insert_data, '');                
            }

            if ($item_id) {
                echo json_encode(array("success" => true, 'message' => 'Success','data'=>$edit));
            } else {
                echo json_encode(array("error" => false, 'message' => "Error"));
            }
        } else {
            exit('No direct script access allowed');
        }
    }
    public function viewValuationData($id = null)
    {
        // if (!$id) {
        //     return redirect()->to('/valuation/index');
        // }

       
        // return $this->template->view("template/admin",$data);

        try {
            $data = [];
            
             $joins = [
                    [
                        'table' => 'valuation_details d',
                        'condition' => 'd.valuation_id = v.id',
                        'jointype' => 'left'
                    ],
                    [
                        'table' => 'ms_customer c',
                        'condition' => 'v.customer_id = c.id',
                        'jointype' => 'left'
                    ],
                    [
                        'table' => 'ms_item i',
                        'condition' => 'd.item_id = i.id',
                        'jointype' => 'left'
                    ]
                ];

                $select = "v.*, d.gross_weight, d.dust, d.net_weight, d.rate, d.amount,
                        c.name AS customer_name, c.address, c.mobile, c.email, i.item_name";

             

            $view_data = [
                'data'  => $this->Crud_model->getAllRecords("valuation v", $select, ["v.id" => $id], $joins),
            ];

            return $this->template->view("valuation/view_valuation", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }


    function addValuation_image($edit_id = null){
        try {
            $data = [];
            $joins = [
                [
                    'table' => 'ms_customer c',
                    'condition' => 'v.customer_id = c.id',
                    'jointype' => 'left'
                ]
            ];
            
            if ($edit_id > 0) {
                $joins = [
                    [
                        'table' => ' valuation_details d ',
                        'condition' => 'd.valuation_id=v.id ',
                        'jointype' => 'left'
                    ],[
                        'table' => 'ms_customer c',
                        'condition' => 'v.customer_id = c.id',
                        'jointype' => 'left'
                    ],
                    [
                        'table' => 'ms_item i',
                        'condition' => 'd.item_id = i.id',
                        'jointype' => 'left'
                    ]
                ];

                $select = "v.*, d.gross_weight, d.dust, d.net_weight, d.rate, d.amount, c.name AS customer_name, c.image as cust_image,i.item_name";
                $data = $this->Crud_model->getAllRecords(
                    "valuation v",
                    $select,
                    ["v.id" => $edit_id],
                    $joins
                );
            }


            $view_data = [
                'data'  => $data,
            ];

            return $this->template->view("valuation/upload_image", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function saveValuationImage($editID = null)
    {
        if ($this->request->isAJAX()) {
            try {
                $request = \Config\Services::request();
            $postData = $this->request->getPost();

            $filesize = 10000000; // 10MB
            $dirName = "";
            $path = IMAGES_FOLDER . $dirName;
            isDirectory($path);

            $edit_id = $postData['id'] ?? $editID;

            $imageFields = ["image", "jewellery_image", "client_image"];

            $existing = $this->Crud_model->getRecordOnId("valuation", ["id" => $edit_id]);

            foreach ($imageFields as $imageField) {
                $file = $request->getFile($imageField);
                if ($file && $file->isValid()) {
                    
                    if (!empty($existing[$imageField])) {
                        $oldImage = $path . $existing[$imageField];
                        if (file_exists($oldImage)) {
                            unlink($oldImage);
                        }
                    }

                    $upload = $this->Crud_model->uploadOneFile($file, "file", $filesize, $path);
                    if (!empty($upload["filename"])) {
                        $postData[$imageField] = $upload["filename"];
                        $postData['otp_verified'] = $existing["otp_verified"];
                    }
                } else {
                    if (!empty($existing[$imageField])) {
                        $postData[$imageField] = $existing[$imageField];
                        $postData['otp_verified'] = $existing["otp_verified"];
                    }
                }
            }

            $result = $this->Valuation_model->add_Valuation($postData, $edit_id);

            if($result){
                echo json_encode(array("success" => true, 'message' => 'Success'));
            } else {
                echo json_encode(array("error" => false, 'message' => "Error"));
            }
            } catch (\Exception $e) {
                debugging($e->getMessage());
            }
            
        }else {
            exit('No direct script access allowed');
        }

       
    }

    
    public function verifyOtp()
    {
        $otp = $this->request->getPost('otp');
        
        $expectedOtp = $_SESSION['otp_sent'] ?? '';
        $valuationId = $_SESSION['valuation_id'] ?? null;
    
        // Check if OTP matches
        if ($otp == $expectedOtp && $valuationId) {
    
            $verifyOtp= $this->Valuation_model->verifyOtp($valuationId, $otp);
            if($verifyOtp){
                echo json_encode(['success' => true,'valuation_id' => $valuationId, 'message' => 'Otp verified successfully']);
                exit;
            }else{
                echo json_encode(['success' => false]);
                exit;
            }
           
        } else {
            echo json_encode(['success' => false]);
            exit;
        }
    }

    public function resendOtp($edit_id=null){
        if ($this->request->isAJAX()) {
            $edit_id = $edit_id?$edit_id:$this->request->getPost('valuationId');
            $existing = $this->Crud_model->getRecordOnId("valuation", ["id" => $edit_id,'otp_verified' => 0]);

            if($existing){
                $send_otp = $this->Valuation_model->sendOtp( $edit_id);
                if($send_otp){
                    return json_encode(array("success" => true, 'message' => 'Otp send successfully'));
                }
               
            }else{
                return json_encode(array("error" => false, 'message' => "User Is already verified"));
            }
        }else{
            exit('No direct script access allowed');
        }
    
    }

    public function loadOtpModel($valuation_id = null){
        if ($this->request->isAJAX()) {
            $valuationId = $_SESSION['valuation_id'] ?? null;
            $data = $this->Crud_model->getRecordOnId("valuation", ["id" => $valuationId]);
            $view_data = [
                'data'  => $data,
            ];
            return $this->template->view("valuation/smsTemplate", $view_data);
        }else{
            exit('No direct script access allowed');
        }
    }

    public function recivedValuation($valuation_id = null){
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('valuation_id');
            if(!$id){
                $id= $valuation_id;
            }
           
            $result = $this->Valuation_model->recivedValuation($id);
            if($result){
                return json_encode(array("success" => true, 'message' => 'Success'));
            } else {
                return json_encode(array("error" => false, 'message' => "Error"));
            }
        }else {
            exit('No direct script access allowed');
        }
    }

    // old Code
    // public function deleteValuation($valuation_id = null){
    //     if ($this->request->isAJAX()) {
    //         $id = $this->request->getPost('valuation_id');
    //         if(!$id){
    //             $id= $valuation_id;
    //         }
    //         $result = $this->Valuation_model->deleteValuation($id);
    //         if($result){
    //             return json_encode(array("success" => true, 'message' => 'Success'));
    //         } else {
    //             return json_encode(array("error" => false, 'message' => "Error"));
    //         }
    //     }else {
    //         exit('No direct script access allowed');
    //     }
    // }

    public function deleteMultipleValuations()
    {
        $ids = $this->request->getPost('ids'); 

        if (!$ids || !is_array($ids)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No valuations selected.'
            ]);
        }

        foreach ($ids as $valuation_id) {

            $data = $this->Crud_model->getRecordOnId("valuation", ["id" => $valuation_id]);

            if (!empty($data) && isset($data)) {
                $record = $data;

                // Unlink Valuation image
                if (!empty($record['image'])) {
                    $valuationFile = FCPATH . 'uploads/' . $record['image'];
                    if (file_exists($valuationFile)) {
                        @unlink($valuationFile);
                    }
                }

                // Unlink jewellery image
                if (!empty($record['jewellery_image'])) {
                    $jewelleryFile = FCPATH . 'uploads/' . $record['jewellery_image'];
                    if (file_exists($jewelleryFile)) {
                        @unlink($jewelleryFile);
                    }
                }

                // Unlink client image
                if (!empty($record['client_image'])) {
                    $clientFile = FCPATH . 'uploads/' . $record['client_image'];
                    if (file_exists($clientFile)) {
                        @unlink($clientFile);
                    }
                }
            }

            $this->Crud_model->deleteRowFromDB('valuation_details', [], 'valuation_id', $valuation_id);
        }

        $deletedCount = $this->Crud_model->deleteRowFromDB('valuation', [], 'id', $ids); 

        if ($deletedCount > 0) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "$deletedCount record(s) deleted successfully."
            ]);
        } elseif ($deletedCount === 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No matching records found to delete.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred during deletion.'
            ]);
        }
    }

    public function deleteImage()
    {
        $request = service('request');

        $filename = $request->getPost('filename');
        $field = $request->getPost('field');
        $id = $request->getPost('id');

        if (!$filename || !$field || !$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing data.']);
        }

        $uploadPath = FCPATH . 'uploads/' . $filename;

        // Delete file from folder
        if (file_exists($uploadPath)) {
            if (!unlink($uploadPath)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete file from server.']);
            }
        }

        // Remove image reference from DB
        $db = \Config\Database::connect();
        $builder = $db->table('valuation');

        $builder->where('id', $id)->update([
            $field => null
        ]);

        if ($db->affectedRows() > 0) {
            return $this->response->setJSON(['success' => true, 'message' => 'Image deleted successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update database.']);
        }
    }


}
