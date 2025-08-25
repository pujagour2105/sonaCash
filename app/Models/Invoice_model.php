<?php

namespace App\Models;
 
use \Hermawan\DataTables\DataTable;


class Invoice_model extends Crud_model
{
    protected $table            = 'billing';
    protected $table2           = 'billing_details';

    function __construct()
    {
        parent::__construct();
    }


    function get_Invoice(){

        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id'); 
        

         $builder = $this->db->table('billing')
        ->select('billing.id,branch.branch_name, ms_customer.name as customer_name, billing.mobile,ROUND(SUM(billing_details.gross_wt), 2) AS total_gross_wt,
         FORMAT(billing.amount, 2) as amount,DATE_FORMAT(billing.ts,"%d-%m-%Y") as ts')
        ->join('billing_details', 'billing_details.billing_id = billing.id', 'left')
        ->join('ms_customer', 'ms_customer.id = billing.customer_id')
        ->join('branch', 'branch.id = billing.branch_id')
        ->groupBy('billing.id')
        ->orderBy('billing.id', 'desc');
        
        // Check if the user is an admin or has a branch ID
        if (!$checkAdmin && $branchId) {
            $builder->where('billing.branch_id', $branchId);
        }
        
        // DataTable logic
        $datatable = DataTable::of($builder)

        ->filter(function ($builder, $request) {
        
            if (!empty($request->branch_id)) {
            
                $builder->like('billing.branch_id', $request->branch_id);

            }
        })

        ->add('action', function ($row) {
            $html = '';
            // if (checkPermission('invoice_managment', 'is_edit')) {
                $html .= '<a href="' . base_url('invoice/printInvoice/' . $row->id) . '" class="btn btn-sm btn-primary"  style="margin-right: 5px;" title="Print Sale Invoice"><i class="fa fa-eye"></i> </a>';
            // }
            return $html;
        }, 'last')->hide('status')
        ->hide('id')
        ->addNumbering()
        ->toJson();

        return $datatable;

    }

    public function saveInvoiceData($postData, $id = null)
    {
        try {
           
            $id = isset($postData['id']) ? $postData['id'] : null;

            // Get branch name (assuming you fetch it using session branch_id)
            $serial_no = '01'; 
            $branch_id = get_session('branch_id');
           
            $branch_data = $this->getRecordOnId('branch', ['id' => $branch_id,'status'=>1], 'branch_name,purchase_invoice_no');

           
            if (is_array($branch_data)) {
                $branch_name    =   $branch_data['branch_name'];
                $serial_no      =   $branch_data['purchase_invoice_no'];
            } elseif (is_object($branch_data)) {
                $branch_name    =   $branch_data->branch_name;
                $serial_no      =   $branch_data->purchase_invoice_no;;
            }

            $branch_name = explode(' ', trim($branch_name))[0];

            $currentYear = date('Y');
            $month = date('m');
            if ($month >= 4) {
                $financial_year = substr($currentYear, 2) . '-' . substr($currentYear + 1, 2);
            } else {
                $financial_year = substr($currentYear - 1, 2) . '-' . substr($currentYear, 2);
            }

            $builder = $this->db->table('billing');
            $builder->select('invoice_no');
            $builder->where('branch_id', $branch_id);
            $builder->orderBy('ts', 'DESC');
            $builder->limit(1);
            
            $query = $builder->get();
            $lastInvoice = $query->getRowArray();
            
            if ($lastInvoice) {
                $parts = explode('/', $lastInvoice['invoice_no']);
                $lastSerial = intval($parts[1]);
                $newSerial = str_pad($lastSerial + 1, 2, '0', STR_PAD_LEFT);
            } else {

                $newSerial = ($serial_no > 0) ? $serial_no : 01; // Default starting serial number
            }

            // Final invoice number
            $invoice_number = $branch_name . '/' . $newSerial . '/' . $financial_year;

            $invoice = [
                'invoice_no'    =>  $invoice_number,
                'customer_id'   => $this->db->escapeString($postData['customer_id']),
                'amount'        => $this->db->escapeString($postData['total_amount']),
                'cash_amount'   => $this->db->escapeString($postData['cash_amount']),
                'online_amount' => $this->db->escapeString($postData['online_amount']),
                'balance_amount'=> $this->db->escapeString($postData['balance_amount']),
                'payment_mode'  => $this->db->escapeString($postData['paymentMode']),
                'round_type'    => $this->db->escapeString($postData['round_type']),
                'round_value'    => $this->db->escapeString($postData['round']),
                'mobile'        => $this->db->escapeString($postData['mobile']),
                'address'       => $this->db->escapeString($postData['address']),
                'branch_id'     => get_session('branch_id'),
                'generated_by'  => get_session('id'),
            ];

            // Handle PAN card file (assumes file upload handled in controller)
            if (!empty($postData['pan_card_file'])) {
                $invoice['pan_card_file'] = $postData['pan_card_file'];
            }

            if ($id) {
                $this->updateRowInDB('billing', $invoice, ["id" => $id]);
                $insertId = $id;
            } else {
                $insertId = $this->insertRowInDB('billing', $invoice);
            }

            if ($insertId) {
                // Delete old billing details if updating
                if ($id) {
                    $this->db->table('billing_details')
                            ->where('billing_id', $id)
                            ->delete();
                }

                // Now insert new item details
                if (!empty($postData['items']) && is_array($postData['items'])) {
                    foreach ($postData['items'] as $item) {
                        if (
                            !empty($item['item_id']) &&
                            isset($item['quantity'], $item['rate'], $item['amount'])
                        ) {
                            $invoice_details = [
                                'billing_id' => $insertId,
                                'item_id'    => $this->db->escapeString($item['item_id']),
                                'quantity'   => $this->db->escapeString($item['quantity']),
                                'gross_wt'   => $this->db->escapeString($item['gross_wt']),
                                'rate'       => $this->db->escapeString($item['rate']),
                                'amount'     => $this->db->escapeString($item['amount']),
                            ];
                            $this->insertRowInDB('billing_details', $invoice_details);
                        }
                    }
                }
            }

            return $insertId;

        } catch (\Exception $e) {
            throw new \Exception("Error saving invoice: " . $e->getMessage());
        }
    }


    public function getInvoiceDetails($id)
    {
        // Get billing header info
        $billing = $this->db->table('billing')
            ->select('billing.*, branch.branch_name,branch.email_id as branch_email, ms_customer.name as customer_name,branch.address as branch_address,
                    branch.contact_no as branch_number,branch.gst_no as gst_no, ms_customer.address as customer_address,ms_customer.email as customer_email,
                    ms_customer.mobile as customer_mobile')
            ->join('ms_customer', 'ms_customer.id = billing.customer_id')
            ->join('branch', 'branch.id = billing.branch_id')
            ->where('billing.id', $id)
            ->get()
            ->getRowArray();

        $branch_name= $this->db->table('branch')
            ->select('branch_name')
            ->where('status', '1')
            ->get()
            ->getResultArray();

        // Get billing item details
        $items = $this->db->table('billing_details')
            ->select('billing_details.*, ms_item.item_name as item_name')
            ->join('ms_item', 'ms_item.id = billing_details.item_id')
            ->join('billing', 'billing.id = billing_details.billing_id')
            ->where('billing.id', $id)
            ->get()
            ->getResultArray();

        // Return as a combined structure

        // print_r($items);
        // exit;
        return [
            'billing' => $billing,
            'items'   => $items,
            'branch_name'=>$branch_name,
        ];
    }


   
}
