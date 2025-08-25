<?php

namespace App\Controllers;

use App\Models\Valuation_model; 
use App\Models\Crud_model;
use App\Models\Report_model;

class Report extends BaseController
{
   
    public $session;
    // public $Staff_model;
    public $Report_model;
    public $Crud_model;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $this->Report_model = new Report_model();
        $this->Crud_model = new Crud_model();
    }


    public function index($id=null)
    {   

        try {
            $data = [
		    'title' => 'Reports',
            'view' => 'report/report_index',
        ];     
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }

    }

    public function branch_valuation_report(){
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
            'branchArray' => $branchArray,
            'view' => 'report/branch_valuation_rpt',
            'customerArray' => $this->Crud_model->get_customerList(),
            'monthArray'=>$this->Crud_model->get_monthArray(),
        ]; 
 
             
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function getReport(){
        try {
            $data = $this->Report_model->getReport();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function branch_report(){
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
            'branchArray' => $branchArray,
            'view' => 'report/branch_report',
            'customerArray' => $this->Crud_model->get_customerList(),
            'monthArray'=>$this->Crud_model->get_monthArray(),
        ]; 
 
             
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function branchReport(){
        try {
            $data = $this->Report_model->getBranchReport();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function invoice_report(){
        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id');

        if ($checkAdmin) {
            $branchArray = $this->Crud_model->getAllRecords('branch', 'id, branch_name');
        } else {
            $branchArray = $this->Crud_model->getAllRecords('branch', 'id, branch_name', ['id' => $branchId]);
        }

        try {
            $data = [
		    'title' => 'Invoice list',
            'branchArray' => $branchArray,
            'view' => 'report/invoice_report',
            'customerArray' => $this->Crud_model->get_customerList(),
            'monthArray'=>$this->Crud_model->get_monthArray(),
        ]; 
 
             
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function get_invoiceReport(){
        try {
            $data = $this->Report_model->getInvoiceReport();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }


    public function sale_invoice_report(){
        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id');

        if ($checkAdmin) {
            $branchArray = $this->Crud_model->getAllRecords('branch', 'id, branch_name');
        } else {
            $branchArray = $this->Crud_model->getAllRecords('branch', 'id, branch_name', ['id' => $branchId]);
        }

        try {
            $data = [
		    'title' => 'Sale Invoice Report',
            'branchArray' => $branchArray,
            'view' => 'report/sale_invoice_report',
            'customerArray' => $this->Crud_model->get_customerList(),
            'monthArray'=>$this->Crud_model->get_monthArray(),
        ]; 
 
             
        return $this->template->view("template/admin", $data);    
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }

    public function get_saleinvoiceReport(){
        try {
            $data = $this->Report_model->getSaleInvoiceReport();
            return $data->getBody();
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }


}
