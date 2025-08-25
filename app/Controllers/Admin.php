<?php

namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\Admin_model;
use App\Models\Crud_model;
use App\Models\Common_model;

class Admin extends BaseController
{
    public $session;
    public $Admin_model;
    public $Crud_model;
    public $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $this->Admin_model = new Admin_model();
        $this->Crud_model = new Crud_model();
        $this->Common_model = new Common_model();


        // $session = \Config\Services::session();
        // $session->destroy();
        // app_redirect('/');
        isAdminLogin();
    }

    // Login page
    public function index()
    {   
        $session = \Config\Services::session();
        if($session->get("id")) {
            app_redirect('/admin/dashboard');
        }
        
            $data = [
                'title' => 'Login',
                'view' => 'admin/login',
            ];
            return view('template/admin/login', $data);

    }

    // Admin login
    function checkAdmin()
    {
        try {
            if ($this->request->isAJAX()) {
                
                $this->Admin_model = new Admin_model();
                $loginUser = $this->Admin_model->login_user($this->request->getPost());
                // p($loginUser);exit;
                if ($loginUser) {
                    if ($loginUser['status'] == 1) {
                        $new_cookie = $this->request->getPost('email') . '|||' . $this->request->getPost('password');
                        if (!empty($this->request->getPost("remember"))) {
                            setcookie("ciuid", base64_encode($new_cookie), time() + (10 * 365 * 24 * 60 * 60), '/');
                        } else {
                            setcookie("ciuid", "", 0, "/");
                        }

                        $user_session = $this->Admin_model->getSessionData($loginUser['id']);
                        $session = \Config\Services::session();
                        $session->set($user_session);
                        $data = ["host_code" => 2, "host_description" => "success"];
                    } else {
                        $data = ["host_code" => 1, "host_description" => "Account inactive, please contact to Admin!"];
                    }
                } else {
                    $data = ["host_code" => 1, "host_description" => "Invalid email/password"];
                }
                echo json_encode($data);
            }
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Dashboard
    public function dashboard()
    {
        // $session = \Config\Services::session();
        // $branch_id = session()->get('branch_id');
        // $fund = $this->Common_model->get_branch_fund($branch_id);

        
        $branch_fund = $this->Admin_model->getBranchFund();
        $amt_distribution = $this->Admin_model->getAmountDistribution();

        // p($branch_fund);exit;
        $data = [
            'title' => 'Dashboard Page',
            'branch_fund'=>$branch_fund,
            'distribution'=>$amt_distribution,
            'view' => 'admin/dashboard'
        ];
        // p($data);exit;
        return view('template/admin', $data);
    }



    /*


    
    // forgot password
    public function forgot()
    {
        // isAdminLogin();
        $data = [
            'title' => 'Forgot Password',
            'view' => 'admin/forgot_password',
        ];
        return view('template/admin/login', $data);
    }

    public function forgotpw() {
        if ($postdata = $this->request->getPost()) {
            
            $email = $postdata["email"];
            $email_data = $this->Admin_model->get_staff_by_email($email);
            if ($email_data) {

                $key = base64_encode(base64_encode($email));
                $reset_link = base_url("staff-forgot/" . $key);
                $forgot_result = $this->Crud_model->updateRowInDB("admin", ["forgot_link" => $key], ["email" => $email]);
                    $email_html = $this->Crud_model->getRecordOnId("email_templates", ["id" => 2, "status" => 1]);
                    
                    if ($email_html) {
                        
                        $mail_body =  mail_data_replace($email_html["default_message"]);
                        $mail_body = str_replace("{USER_NAME}", $email_data["name"], $mail_body);
                        $mail_body = str_replace("{RESET_PASSWORD_URL}", $reset_link, $mail_body);
                        // echo $mail_body; exit;
                        $status = send_mail_func($email, $email_html["email_subject"], $mail_body, '');
                        // $log_array=[ "subject" => $email_html["email_subject"], "email" => $email, "msg" => $mail_body, "status" => "0", "ts" => date("Y-m-d H:i:s") ];
                        // $this->Crud_model->insertRowInDB("ms_emaillog", $log_array);
                    }
                    echo json_encode(array("success" => true, 'message' => 'Email sent to registered email!'));
                    exit;
            } else {
                echo json_encode(array("success" => false, 'message' => 'Invalid Email ID'));
                exit;
            }
        }
    }

    // reset password 
    public function staff_forgot()
    {
        isAdminLogin();
        $uri = get_segments();
        $email = base64_decode(base64_decode($uri[1]));

        $user_array = $this->Crud_model->getRecordOnId("admin", ["email" => $email]);
        $session = \Config\Services::session();
        if ($user_array["forgot_link"]) {
            $data = [
                'title' => 'Reset password',
                'view' => 'admin/reset_password',
                'data' => $user_array
            ];
            return view('template/admin/login', $data);
        } else {
            $session->setFlashdata("error_message", "Invalid link");
            app_redirect("/");
        }
    }

    // Reset password
    public function staff_reset()
    {
        if ($postdata = $this->request->getPost()) {
            $forgot_result = $this->Crud_model->updateRowInDB("admin", ["password" => password_hash($postdata["password"], PASSWORD_DEFAULT), "forgot_link" => null], ["id" => $postdata['id']]);
            if ($forgot_result) {
                $user_data = $this->Crud_model->getRecordOnId("admin", ["id" => $postdata['id']]);

                $email_html = $this->Crud_model->getRecordOnId("email_templates", ["id" => 3, "status" => 1]);
                if ($email_html) {
                    $LoginUrl = base_url();
                    $mail_body =  mail_data_replace($email_html["default_message"]);
                    $mail_body = str_replace("{USER_NAME}", $user_data["name"], $mail_body);
                    
                    send_mail_func($user_data["email"], $email_html["email_subject"], $mail_body, '');
                }
                echo json_encode(array("success" => true, 'message' => 'Password changed, please login'));
            } else {
                echo json_encode(array("error" => false, 'message' => 'Error, please try again'));
            }
        }
    }
*/
    public function changepassword()
    {
        
        try {
            if ($postData = $this->request->getPost()) {

                $result_profile = $this->Crud_model->updateRowInDB("admin", $postData, ["id" => get_session('id')]);
                $user_session = $this->Admin_model->getSessionData(get_session('id'));
                $session = \Config\Services::session();
                $session->set($user_session);
                echo json_encode(array("success" => true, 'message' => 'Success'));
                exit;
            }

            $profile_data = $this->Admin_model->get_profile(get_session('id'));
            $data = [
                'title' => 'Profile',
                'view' => 'admin/change_password',
                'data' => $profile_data,
            ];
            return $this->template->view("template/admin", $data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }


    public function action_changepassword()
    {
        $session = session();
        $userId = get_session('id');

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Basic validation
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'All fields are required.'
            ]);
        }

        if ($newPassword !== $confirmPassword) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'New password and confirm password do not match.'
            ]);
        }

        
        // Get user record
        $user = $this->Crud_model->getRecordOnId("admin", ["id" => $userId]);

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User not found.'
            ]);
        }

        // Verify old password
        if (!password_verify($currentPassword, $user['password'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Current password is incorrect.'
            ]);
        }

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updated = $this->Crud_model->updateRowInDB('admin', ['password' => $hashedPassword], ['id' => $userId]);

        if ($updated) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Password changed successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to change password.'
            ]);
        }
    }
    // Profile
    public function profile()
    {
        $router = service('router');
        try {
            if ($postData = $this->request->getPost()) {

                $result_profile = $this->Crud_model->updateRowInDB("admin", $postData, ["id" => get_session('id')]);
                $user_session = $this->Admin_model->getSessionData(get_session('id'));
                $session = \Config\Services::session();
                $session->set($user_session);
                echo json_encode(array("success" => true, 'message' => 'Success'));
                exit;
            }

            $profile_data = $this->Admin_model->get_profile(get_session('id'));
            $data = [
                'title' => 'Profile',
                'view' => 'admin/profile',
                'data' => $profile_data,
            ];
            return $this->template->view("template/admin", $data);
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    // Update user image
    public function uploadImage()
    {
        if ($this->request->isAJAX()) {
            if (isset($_FILES['ajax_file'])) {
                $imge = $_FILES['ajax_file'];
                if ($imge['size'] <= 10000000) {
                    $path = IMAGES_FOLDER . "users/";
                    isDirectory($path);
                    $dir = $path . get_session('id');
                    isDirectory($dir);

                    $ext = strtolower(pathinfo($_FILES['ajax_file']['name'], PATHINFO_EXTENSION));
                    $final_image = rand(1000, 1000000) . '.' . $ext;
                    $imge['name'] = $final_image;

                    $destination = $dir . '/' . $imge['name'];
                    $isuploaded = move_uploaded_file($imge['tmp_name'], $destination);
                    if ($isuploaded) {

                        if (get_session('image')) {
                            $image_dir = $path . get_session('id') . '/' . get_session('image');
                            if (file_exists($image_dir)) {
                                unlink($image_dir);
                            }
                        }

                        $imageArray['image'] = $imge['name'];
                        $imgresult = $this->Crud_model->updateRowInDB("admin", $imageArray, ["id" => get_session('id')]);
                        $imgresult = true;
                        if ($imgresult) {
                            $user_session = $this->Admin_model->getSessionData(get_session('id'));
                            $session = \Config\Services::session();
                            $session->set($user_session);

                            echo json_encode(array("status" => "success", "message" => "File has been uploaded successfully"));
                        } else {
                            echo json_encode(array("status" => "error", "message" => "Image not saved"));
                        }
                    } else {
                        echo json_encode(array("status" => "fail", "message" => "Some error to upload this file"));
                    }
                } else {
                    echo json_encode(array("error" => false, 'message' => "File size can't more than 1 MB"));
                }
            } else {
                echo json_encode(array("error" => false, 'message' => "Error"));
            }
        } else {
            exit('No direct script access allowed');
        }
    }


    
    
    function logout()
    {
        $session = \Config\Services::session();
        $session->destroy();
        app_redirect('/');
    }

    
}
