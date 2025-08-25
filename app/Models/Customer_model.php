<?php

namespace App\Models;

use \Hermawan\DataTables\DataTable;

class Customer_model extends Crud_model
{
 
    protected $table = 'ms_customer';
    function __construct()
    {
        parent::__construct();
    }

    
    // Get all streams list
    public function getCustomerList() 
    {
        $builder = $this->db->table('ms_customer a')->select('a.id, a.name, a.email, a.mobile, a.address,a.ts, a.status')->orderBy('a.id', 'DESC');

        $datatable = DataTable::of($builder)
            ->filter(function ($builder, $request) {
                if (isset($request->status)) {
                    $builder->where(['a.status' => $request->status]);
                }
                if (!empty($request->from_date) && empty($request->to_date)) {
                    // Only from_date is set — use exact match
                    $builder->where('DATE(a.ts)', $request->from_date);
                } elseif (!empty($request->from_date) && !empty($request->to_date)) {
                    // Both from_date and to_date are set — use range
                    $builder->where('DATE(a.ts) >=', $request->from_date);
                    $builder->where('DATE(a.ts) <=', $request->to_date);
                }
            })

            ->edit('name', function ($row) {
                return ucfirst($row->name);
            }, 'last')

            ->edit('address', function ($row) {
                return ucfirst($row->address);
            }, 'last')
            ->edit('ts', function ($row) {
                return date("d-m-Y", strtotime($row->ts));
            }, 'last')

            ->add('action', function ($row) {
                $html = '';
                // if (checkPermission("customer_management", "is_edit")) {
                    $html .= '<button type="button" class="btn btn-primary btn-sm" data-post-type="master" data-act="ajax-modal" data-title="Edit Customer" data-action-url="' . base_url('customer/addCustomerForm/' . $row->id) . '">Edit</button> &nbsp;';
                    $html .= '<a href="' . base_url('valuation/index/' . $row->id) . '" class="btn btn-info btn-sm" title="View Valuation">View Valuation List</a> &nbsp;';
                // }
                return $html;
            }, 'last')

            ->edit('status', function ($row) {
                if ($row->status == '1')
                    return '<span class="badge badge-success">Active</span>';
                else
                    return '<span class="badge badge-danger">Inactive</span>';
            })
            ->hide('id')
            ->addNumbering()
            ->toJson();

        return $datatable;
    }

    public function add_customer($data, $id = null) 
    {

        $insert_user = [
            "name" => $this->db->escapeString($data['name']),
            "email" => $this->db->escapeString($data['email']),
            "mobile" => $this->db->escapeString($data['mobile']),
            "address" => $data['address'],
            "image" => $data['image'],
            "status" => $this->db->escapeString($data['status']),
        ];

        if ($id) {
            $insert_user["ts"] = date("Y-m-d H:i:s");
            $this->updateRowInDB("ms_customer", $insert_user, ["id" => $id]);
            
        } else {
            $insert_user["ts"] = date("Y-m-d H:i:s");
            $id = $this->insertRowInDB("ms_customer", $insert_user);
        }
    
        return $id;
    }

    
}
