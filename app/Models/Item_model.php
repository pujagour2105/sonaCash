<?php

namespace App\Models;
 
use \Hermawan\DataTables\DataTable;


class Item_model extends Crud_model
{
    protected $table            = 'ms_item';

    function __construct()
    {
        parent::__construct();
    }


    function getItem_list(){
        $builder = $this->db->table('ms_item ')
                    ->select('id, item_name,status')
                    ->orderby('id','desc');
                   
        // DataTable logic
        $datatable = DataTable::of($builder)

            ->filter(function ($builder, $request) {
                if (isset($request->status)) {
                    $builder->where(["status" => $request->status]);
                }
             
                if (!empty($request->item_name)) {
                    if (!empty($item_name)) {
                        $builder->like('v.item_name', $item_name);
                    }
                }
            })

            ->edit('item_name', function ($row) {
                return ucfirst($row->item_name);
            }, 'last')
            
            ->add('action', function ($row) {
                // $html = '';
                // if (checkPermission('item_management', 'is_edit')) {
                //     $html .= '<button type="button" class="btn btn-sm btn-success" style="margin-right: 5px;" data-act="ajax-modal" data-title="Edit Item" data-action-url="' . base_url('item/addItemForm/' . $row->id) . '"><i class="fa fa-edit fa-xs"></i></button>';
                //     // $html .= '<button type="button" class="btn btn-sm btn-danger btnChangeStatus" style="margin-right: 5px;"  data-title="Delete" data-action-url="' . base_url('item/deleteValuati/' . $row->id) . '"><i class="fa fa-trash fa-xs"></i></button>';  
                   
                // }
                return '<button type="button" class="btn btn-sm btn-success" style="margin-right: 5px;" data-act="ajax-modal" data-title="Edit Item" data-action-url="' . base_url('item/addItemForm/' . $row->id) . '"><i class="fa fa-edit fa-xs"></i></button>';;
            }, 'last')->hide('status')->hide('id')->addNumbering()

            ->toJson();

        return $datatable;

    }

    function add_Item($data, $id = null){
        $insert_data = [
            "item_name" => $this->db->escapeString($data['item_name']),
            "ts" => date("Y-m-d H:i:s")
        ];

        if ($id) {
            
            $this->updateRowInDB("ms_item", $insert_data, ["id" => $id]);
        } else {
            $id = $this->insertRowInDB("ms_item", $insert_data);
        }
    
        return true;
    }
 
}
