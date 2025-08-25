<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Crud_model;
use App\Models\Item_model;


class Item extends BaseController
{
    public $session;
    public $Item_model;
    public $Crud_model;
    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $this->Item_model = new Item_model();
        $this->Crud_model = new Crud_model();
    }
    public function index()
    {
        try {
            $data = [
		    'title' => 'Item',
            'view' => 'item/item_list',
           
        ]; 
  
             
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function getItemLists(){
        try {
            $data = $this->Item_model->getItem_list();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        } 
    }

    public function addItemForm($editId = null)
    {
        try {
            $data = [];
            if ($editId > 0) {
                $data = $this->Crud_model->getRecordOnId("ms_item", ["id" => $editId]);
            }
            $view_data = [
                'data'  => $data,
            ];

           

            return $this->template->view("item/add_item_form", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }

    }

    public function saveItem(){
        if ($this->request->isAJAX()) {
           
            $insert_data = $this->request->getPost();
            
            $ext_item = $this->Crud_model->getRecordOnId("ms_item", ["item_name" => $insert_data["item_name"]], "id");
            if($ext_item) {
                if($insert_data["id"] === $ext_item["id"]) {
                    //
                } else {
                    echo json_encode(array("error" => false, 'message' => "Item already exist,"));                
                }
            }
            

            if ($this->request->getPost('id')) {
                

                $item_id = $this->Item_model->add_Item($insert_data, $this->request->getPost('id'));
                $item_id = true;
            } else {
                
                $item_id = $this->Item_model->add_Item($insert_data, '');                
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
}
