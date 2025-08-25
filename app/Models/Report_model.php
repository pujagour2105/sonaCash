<?php

namespace App\Models;
 
use \Hermawan\DataTables\DataTable;


class Report_model extends Crud_model
{
    protected $table            = 'valuation';

    function __construct()
    {
        parent::__construct();
    }


    function getReport(){
        $builder = $this->db->table('valuation v');
        $builder->select('
            c.name as customer_name, 
            date(v.ts) as only_date, 
            i.item_name, 
            d.net_weight, 
            d.rate, 
            d.amount
        ');
        $builder->join('valuation_details d', 'v.id = d.valuation_id');
        $builder->join('ms_customer c', 'v.customer_id = c.id', 'left');
        $builder->join('ms_item i', 'd.item_id = i.id', 'left')->orderBy('v.ts desc');

        // $query = $builder->get();
        // $results = $query->getResult();
                   
        // DataTable logic
        $datatable = DataTable::of($builder)

            ->filter(function ($builder, $request) {
             
                if (!empty($request->branch_id)) {
                    if (!empty($request->branch_id)) {
                        $builder->like('v.branch_id',$request->branch_id);
                    }
                }
                if (isset($request->status)) {
                    $builder->where(['v.status' => $request->status]);
                }
             
                if (isset($request->customer_id)) {
                    $builder->where(['v.customer_id' => $request->customer_id]);
                }
                if (isset($request->month_id) && !empty($request->month_id)) {
                    $builder->where('MONTH(v.ts)', $request->month_id);
                }
            })

            ->edit('item_name', function ($row) {
                return ucfirst($row->item_name);
            }, 'last')
            ->edit('only_date', function ($row) {
                return date('d-m-Y', strtotime($row->only_date));
            })

            ->toJson();

        return $datatable;

    }


   
    public function getBranchReport()
    {
        $request = service('request');
        $branch_id = $request->getPost('branch_id');
        $month_id = $request->getPost('month_id');
        $from_date = $request->getPost('from_date');
        
        $to_date = $request->getPost('to_date') ? $request->getPost('to_date') : $from_date;

        // Validation
        if (empty($branch_id)) {
            return $this->response->setJSON([
                'data' => [],
                'error' => 'Branch ID is required'
            ]);
        }

        $builder = $this->db->table('rpt_ledger');
        $builder->select('ts, branch_id, amount, type, available_balance, opening_amount');
        $builder->where('branch_id', $branch_id)->orderBy('ts desc');

        if (!empty($month_id)) {
            $builder->where('MONTH(ts)', $month_id);
        }

        if (!empty($from_date)) {
            $builder->where('DATE(ts) >=', $from_date);
        }

        if (!empty($to_date)) {
            $builder->where('DATE(ts) <=', $to_date);
        }

    
        $builder->orderBy('ts', 'desc');
        $result = $builder->get()->getResultArray();
        
        // Process the result into grouped daily data
        $dailyReport = [];

        foreach ($result as $entry) {
            $date = date('Y-m-d', strtotime($entry['ts']));

            if (!isset($dailyReport[$date])) {
                $dailyReport[$date] = [
                    'only_date' => $date,
                    'opening_amount' => $entry['opening_amount'],
                    'todays_credit' => 0,
                    'todays_debit' => 0,
                    'closing_amount' => 0, // will update as loop continues
                ];
            }

            // Accumulate credit/debit
            if ($entry['type'] === 'credit') {
                $dailyReport[$date]['todays_credit'] += $entry['amount'];
            } elseif ($entry['type'] === 'debit') {
                $dailyReport[$date]['todays_debit'] += $entry['amount'];
            }

            // Always keep the latest available_balance as closing
            $dailyReport[$date]['closing_amount'] =($dailyReport[$date]['opening_amount']+ $dailyReport[$date]['todays_credit'])-$dailyReport[$date]['todays_debit'] ;
        }

        // Format for DataTables output
        $data = [];
        foreach ($dailyReport as $row) {
            $data[] = [
                date('d-m-Y', strtotime($row['only_date'])),
                number_format($row['opening_amount'], 2),
                number_format($row['todays_credit'], 2),
                number_format($row['todays_debit'], 2),
                number_format($row['closing_amount'], 2),
            ];
        }

        // print_r($data);
        // exit;

        echo json_encode([
            'data' => $data,
            'recordsFiltered' => count($data),
            'recordsTotal' => count($data),
        ]);
        exit;

        // return $this->response->setJSON([
        //     'data' => $data
        // ]);
    }


    public function getInvoiceReport(){
        $builder = $this->db->table('billing b');
        $builder->join('branch br', 'br.id = b.branch_id', 'left');
        $builder->join('ms_customer c', 'c.id = b.customer_id', 'left')
        ->groupBy('b.branch_id, b.customer_id')
        ->orderBy('b.ts desc');

        $builder->select("
            br.branch_name as branch_name,
            c.name as customer_name,
            SUM(b.amount) as total_amount,
            SUM(b.amount - b.balance_amount) as paid_amount,
            SUM(b.balance_amount) as balance_amount,
            IF(b.payment_mode = '' OR b.payment_mode IS NULL, 'CASH', b.payment_mode) AS payment_mode,
            date(b.ts) as only_date
        ");

        $datatable = DataTable::of($builder)
            ->filter(function ($builder, $request) {
                if (!empty($request->branch_id)) {
                    $builder->where('b.branch_id', $request->branch_id);
                }

                if (!empty($request->payment_mode)) {
                    if($request->payment_mode=='CASH'){
                        $builder->where('b.payment_mode', '');
                        $builder->where('b.online_amount', '0');
                    }
                    else
                    $builder->where('b.payment_mode', $request->payment_mode);
                }

                if (!empty($request->from_date)) {
                    $builder->where('DATE(b.ts) >=', $request->from_date);
                }

                if (!empty($request->to_date)) {
                    $builder->where('DATE(b.ts) <=', $request->to_date);
                }
            })
            ->edit('only_date', function ($row) {
                return date('d-m-Y', strtotime($row->only_date));
            })
            ->toJson();

        return $datatable;

    }

    public function getSaleInvoiceReport(){

        $builder = $this->db->table('sale_invoice b');
        $builder->join('branch br', 'br.id = b.branch_id', 'left');
        $builder->join('ms_customer c', 'c.id = b.customer_id', 'left')
        ->groupBy('b.branch_id, b.customer_id')->orderBy('b.ts desc');

        $builder->select("
            br.branch_name AS branch_name,
            c.name AS customer_name,
            SUM(b.amount) AS total_amount,
            SUM(b.amount - b.balance_amount) AS paid_amount,
            SUM(b.balance_amount) AS balance_amount,
            IF(b.payment_mode = '' OR b.payment_mode IS NULL, 'CASH', b.payment_mode) AS payment_mode,
            DATE(b.ts) AS only_date
        ");

        $datatable = DataTable::of($builder)
            ->filter(function ($builder, $request) {
                if (!empty($request->branch_id)) {
                    $builder->where('b.branch_id', $request->branch_id);
                }

                if (!empty($request->payment_mode)) {
                    if($request->payment_mode=='CASH'){
                        $builder->where('b.payment_mode', '');
                        $builder->where('b.online_amount', '0');
                    }
                    else
                    $builder->where('b.payment_mode', $request->payment_mode);
                }

                if (!empty($request->from_date)) {
                    $builder->where('DATE(b.ts) >=', $request->from_date);
                }

                if (!empty($request->to_date)) {
                    $builder->where('DATE(b.ts) <=', $request->to_date);
                }
            })
            ->edit('only_date', function ($row) {
                return date('d-m-Y', strtotime($row->only_date));
            })
            
            ->toJson();

        return $datatable;

    }
        


    
    
    

}
