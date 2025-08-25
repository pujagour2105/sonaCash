<?php
use App\Controllers\Security_Controller;
use App\Controllers\App_Controller;

function check_duplicate_master_data($table, $col, $title,$cond=null) {
    $CI = \Config\Database::connect();
    $builder = $CI->table($table)
        ->select('id')
        ->where($col, $title);

     // Add additional conditions if provided
    if (!empty($cond) && is_array($cond)) {
        foreach ($cond as $key => $value) {
            $builder->where($key, $value);
        }
    }
    $result = $builder->get()->getResultArray();
    return !empty($result);
}

