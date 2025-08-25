<?php

namespace App\Controllers;

use App\Models\Staff_model;
use App\Models\Crud_model;

class Staff extends BaseController
{
    public $session;
    public $Staff_model;
    public $Crud_model;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $this->Staff_model = new Staff_model();
        $this->Crud_model = new Crud_model();
    }

    
    /* @Author: Sunil Joshi
    @ Objective : Staff list page
    */
    public function index()
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
		    'title' => 'Staff list',
            'view' => 'staff/staff_list',
            'branchArray'=>$branchArray,
        ];            
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }


    /* @Author: Sunil Joshi
    @ Objective : get staff listing
    */
    public function getStaffists()
    {
        try {
            $data = $this->Staff_model->getStaffists();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    /* @Author: Sunil Joshi
    @ Objective : Add/edit staff form
    */
    public function addStaffForm($edit_id = null)
    {
        try {
            $data = [];
            if ($edit_id > 0) {
                $data = $this->Crud_model->getRecordOnId("admin", ["id" => $edit_id], "");
            }
            $view_data = [
                'data'  => $data,
                // 'stateArray' => $this->Crud_model->getStateList_data(),
                'roleArray' => $this->Crud_model->get_roleList("1"),
                "branchArray" => $this->Crud_model->getBranchList(),
                'moduleArray' => $this->Crud_model->get_modulesList(),
                // 'levelArray' => $this->Staff_model->getLevel_lists(),
                // 'tlArray' => $this->Crud_model->get_employeeBy_role(4,''),
            ];
            return $this->template->view("staff/addStaff_form", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    /* @Author: Sunil Joshi
    @ Objective : Save staff data
    */
    function savStaffData($edit_id = null)
    {
        if ($this->request->isAJAX()) {
            $insert_data = $this->request->getPost();
            
            $ext_user = $this->Crud_model->getRecordOnId("admin", ["email" => $insert_data["email"]], "id");
            if($ext_user) {
                if($insert_data["id"] === $ext_user["id"]) {
                    //
                } else {
                    echo json_encode(array("error" => false, 'message' => "User already exist,")); exit;                    
                }
            }

            if ($this->request->getPost('id')) {
                $item_id = $this->Staff_model->add_staff($insert_data, $this->request->getPost('id'));
                $item_id = true;
            } else {
                $item_id = $this->Staff_model->add_staff($insert_data, '');                
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


    // =========================================================================================================




    

    public function emaildemo() {
        echo "hello <br>";
        send_mail_func("suniljoshi.sik@gmail.com", "Test mail subect", "test mail body", '', '', '');
    }
    

    //==========================================================


    //======================= END STREAM ----------------------------


    //======================= START COURSE ----------------------------
    // List of Course
    public function course()
    {
        try {
            $data = [
		    'title' => 'Course',
            'view' => 'master/course/course_list',
            "streamArray" => $this->Crud_model->getStreamList()
        ];            
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Get role data
    public function getCourseList()
    {
        try {
            $data = $this->Staff_model->getCourseList();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Add role form
    public function addCourseForm($edit_id = null)
    {
        try {
            $data = [];
            if ($edit_id > 0) {
                $data = $this->Crud_model->getRecordOnId("subcategory", ["id" => $edit_id], "");
            }
            $view_data = [
                'data'  => $data,
                "streamArray" => $this->Crud_model->getStreamList(),
                "universityArray" => $this->Crud_model->getUniversityList_data(),
            ];
            return $this->template->view("master/course/addCourse_form", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Save role
    function savecourse($edit_id = null)
    {
        if ($this->request->isAJAX()) {
            $id = '';
            $postData=$this->request->getPost();
            $image = $this->request->getFile("image");            
            $filesize = 10000000;
            $dirName = "upload/subcategory/";
            $path = IMAGES_FOLDER . $dirName;
            isDirectory($path);
            unset($postData["image"]);
            if (!empty($image)) {
                // delete old image
                $extImg = $this->Crud_model->getRecordOnId("subcategory", ["id" => $postData["id"]], "image");
                if($extImg) {
                    $oldImage = IMAGES_FOLDER.$extImg["image"];
                    if( file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }

                $image_name = $this->Crud_model->uploadOneFile($image, "file", $filesize, $path, $id);
                $postData["image"] = $dirName . $image_name["filename"];
            }
            $item_id = $this->Staff_model->save_courses($postData);
            if ($item_id) {
                echo json_encode(array("success" => true, 'message' => 'Success'));
            } else {
                echo json_encode(array("error" => false, 'message' => "Error"));
            }
        } else {
            exit('No direct script access allowed');
        }
    }
    //======================= END COURSE ----------------------------
    
    //======================= START common fields ----------------------------
    // List of Course
    public function course_fields()
    {
        try {
            $data = [
		    'title' => 'Course Common Fields',
            'view' => 'master/course_common/fields_list',
            "streamArray" => $this->Crud_model->getStreamList()
        ];            
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Get role data
    public function get_course_fields()
    {
        try {
            $data = $this->Staff_model->get_course_fields();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Add role form
    public function addCourse_fieldsForm($edit_id = null)
    {
        try {
            $result = [];
            if ($edit_id > 0) {
                $result = $this->Crud_model->getRecordOnId("ms_common_fields", ["id" => $edit_id], "");
            }
            $view_data = [
                'data'  => $result
            ];
            return $this->template->view("master/course_common/addFields_form", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Save role
    function savecourse_commonfields($edit_id = null)
    {
        if ($this->request->isAJAX()) {
            $item_id = $this->Staff_model->savecourse_commonfields($this->request->getPost());
            if ($item_id) {
                echo json_encode(array("success" => true, 'message' => 'Success'));
            } else {
                echo json_encode(array("error" => false, 'message' => "Error"));
            }
        } else {
            exit('No direct script access allowed');
        }
    }
    //======================= END COURSE ----------------------------
    
    //======================= START CITY MAPPING ----------------------------
    // List of Course
    public function cities()
    {
        try {
            $data = [
		    'title' => 'Add City',
            'view' => 'master/city/city_list',
            'stateArray' => $this->Crud_model->getStateList_data()
        ];            
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Get role data
    public function get_city_lists()
    {
        try {
            $data = $this->Staff_model->get_city_lists();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Add role form
    public function addCityForm($edit_id = null)
    {
        try {
            $result = [];
            if ($edit_id > 0) {
                $result = $this->Crud_model->getRecordOnId("ms_city", ["id" => $edit_id], "");
            }
            $view_data = [
                'data'  => $result,
                'stateArray' => $this->Crud_model->getStateList_data()
            ];
            return $this->template->view("master/city/addCityForm", $view_data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Save role
    function savCityData($edit_id = null)
    {
        if ($this->request->isAJAX()) {
            $item_id = $this->Staff_model->savCityData($this->request->getPost());
            if ($item_id) {
                echo json_encode(array("success" => true, 'message' => 'Success'));
            } else {
                echo json_encode(array("error" => false, 'message' => "Error"));
            }
        } else {
            exit('No direct script access allowed');
        }
    }
    //======================= END COURSE ----------------------------

    


}
