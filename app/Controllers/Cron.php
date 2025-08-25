<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Cron_model;

class CronController extends BaseController
{
    protected $cronModel;

    public function __construct()
    {
        $this->cronModel = new Cron_model();
    }

    public function index()
    {
        $this->syncCustomers();
        $this->syncItems();
    
        return "All cron tasks done."; 
    }

    public function syncCustomers()
    {
        $result = $this->cronModel->syncMissingCustomers();

        if ($result) {
            echo "success";
        } else {
            echo "error";
        }
    }
    public function syncItems()
    {
        $result = $this->cronModel->syncMissingCustomers();

        if ($result) {
            echo "success";
        } else {
            echo "error";
        }
    }
}
