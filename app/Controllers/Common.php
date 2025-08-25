<?php

namespace App\Controllers;

use App\Models\Crud_model;
use App\Models\Common_model;
use Exception;

class Common extends AppController 
{
    public $session;
    protected $Crud_model;
    protected $Common_model;
    public $template;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();        
        $this->Crud_model = new Crud_model();
        $this->Common_model = new Common_model();
    }

    


    
    
    // get ct wise stream
    public function get_branch_fund()
    {
        try {
            if ($postdata = $this->request->getPost()) {
                // $fund = $this->Common_model->get_branch_fund($postdata['id']);
                // $expense = $this->Common_model->get_branch_expense($postdata['id']);
                $amount = getAvailableFundBranch($postdata['id']);
                $amount = number_format((float)$amount, 2, '.', '');
                $data = ["host_code" => 0, "host_description" => "success", "amount" => $amount];
                echo json_encode($data); exit;
            }
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    
    
    

}
