<?php

namespace App\Models;

use \Hermawan\DataTables\DataTable;

class Staff_model extends Crud_model
{
 
    protected $table = 'admin';
    private $perm_statements = array('view', 'edit', 'add');
    function __construct()
    {
        parent::__construct();
    }

    
    // Get all streams list
    public function getStaffists() 
    {
        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id');

        $builder = $this->db->table('admin a')
        ->select('a.id,b.branch_name, a.name, a.email, a.mobile, a.gender, d.name as role, a.status')
        ->join("designation d", "d.id=a.role_id", "inner")
        ->join("branch b", "b.id=a.branch_id", "left");


        if (!$checkAdmin && $branchId) {
            $builder->where('a.branch_id', $branchId);
        }
        $datatable =  DataTable::of($builder)->filter(function ($builder, $request) {
            if (isset($request->status)) {
                $builder->where(["a.status" => $request->status]);
            } 
             if (isset($request->branch_id)) {
                $builder->where(["a.branch_id" => $request->branch_id]);
            }            
        })
        ->edit('name', function ($row) {
            return ucfirst($row->name);
        }, 'last')
        
        ->edit('gender', function ($row) {
            return ucfirst($row->gender);
        }, 'last')

        ->add('action', function ($row) {
            $html = "";
            if(checkPermission("staff_management", "is_edit")) {
                $html .= '<button type="button" class="btn btn-primary btn-sm" data-post-type="master" data-act="ajax-modal" data-title="Edit Staff" data-action-url="' . base_url('staff/addStaffForm/' . $row->id) . '">Edit</button> &nbsp;';
            }
            return $html;
        }, 'last')
        ->edit('status', function ($row) {
            if ($row->status == 1)
                return '<a href="javascript:;"><span class="badge badge-success">Active</span></a>';
            else
                return '<a href="javascript:;"><span class="badge badge-danger">Inactive</span></a>';
        })
        ->toJson();
        return $datatable;
    }

    public function add_staff($data, $staffid = null)
    {

        $insert_user = [
            "name" => $this->db->escapeString($data['name']),
            "email" => $this->db->escapeString($data['email']),
            "mobile" => $this->db->escapeString($data['mobile']),
            "role_id" => $data['role_id'],
            "branch_id" => $data['branch_id'],
            "dob" => $data['dob'],
            "doj" => $data['doj'],
            "gender" => $data['gender'],
            "address" => $data['address'],
            "status" => $this->db->escapeString($data['status']),
            "is_admin" => isset($data["administrator"]) ? "1" : "0",
        ];

        $password = $this->db->escapeString($data['password']);            
        if($password) {
            $insert_user["password"] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($staffid) {
            $insert_user["updated_at"] = date("Y-m-d H:i:s");
            $insert_user["updated_by"] = get_session('id');
            $this->updateRowInDB("admin", $insert_user, ["id" => $staffid]);
        } else {
            $insert_user["created_at"] = date("Y-m-d H:i:s");
            $insert_user["created_by"] = get_session('id');            
            
            $staffid = $this->insertRowInDB("admin", $insert_user);
        }
        
        $this->add_staff_Acl($staffid, $data);
        return true;
    }

    function add_staff_Acl($staffid, $data)
    {
        $query = $this->db->query("select * from ms_modules WHERE status = '1'");
        if ($query) {
            foreach ($query->getResultArray() as $permission) {                
                $check_acl = $this->getRecordOnId("ms_module_permissions", ["staffid" => $staffid, "module_id" => $permission['id']], "");
                if ($check_acl) {
                } else {
                    $this->insertRowInDB("ms_module_permissions", ['module_id' => $permission['id'], 'staffid' => $staffid, 'is_add' => '0', 'is_edit' => '0', 'is_view' => '0']);
                }
            }

            $permissions = array();
            if (isset($data['view'])) {
                $permissions['view'] = $data['view'];
                unset($data['view']);
            }

            if (isset($data['edit'])) {
                $permissions['edit'] = $data['edit'];
                unset($data['edit']);
            }
            if (isset($data['create'])) {
                $permissions['add'] = $data['create'];
                unset($data['create']);
            }
            $this->updateRowInDB("ms_module_permissions", ['is_add' => '0', 'is_edit' => '0', 'is_view' => '0'], ['staffid' => $staffid]);
            
            foreach ($this->perm_statements as $c) {
                foreach ($permissions as $key => $p) {
                    if ($key == $c) {
                        foreach ($p as $perm) {
                            $colName = 'is_' . $c;
                            $query = $this->db->query("UPDATE `ms_module_permissions` SET $colName = 1 WHERE `staffid` = $staffid AND  module_id = $perm");
                        }
                    }
                }
            }
            return true;
        } else
            return false;
    }

    
}
