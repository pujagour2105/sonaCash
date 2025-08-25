<?php

namespace App\Models;

use \Hermawan\DataTables\DataTable;
use App\Models\Crud_model;

class Valuation_model extends Crud_model
{
 
    protected $table = 'valuation_details';
    protected $table2 = 'valuation';
    
    function __construct()
    {
        parent::__construct();
        // $this->Crud_model = new Crud_model(); 
    }

    public function getValuationList() 
    {
        $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
        $branchId = session()->get('branch_id'); 
        

        $builder = $this->db->table('valuation v')
            ->select('v.id,v.customer_id, c.name, c.mobile, b.branch_name,IFNULL(va.total_gross_wt, 0) as total_gross_wt,
                    IFNULL(va.total_amount, 0) as total_amount,DATE(v.ts) as only_date,  v.otp_verified, v.status, v.is_deleted')
            ->join('branch b', 'v.branch_id = b.id')
            ->join('ms_customer c', 'v.customer_id = c.id', 'left')
            ->join('(SELECT valuation_id, SUM(amount) as total_amount,SUM(gross_weight) as total_gross_wt 
                    FROM valuation_details 
                    GROUP BY valuation_id) va', 'va.valuation_id = v.id', 'left')
            ->groupBy('v.id')
            ->orderBy('v.ts', 'desc');
        // Check if the user is an admin or has a branch ID
        if (!$checkAdmin && $branchId) {
            $builder->where('v.branch_id', $branchId);
        }
    
        // DataTable logic
        $datatable = DataTable::of($builder)
        

            ->filter(function ($builder, $request) {
                $search = $request->search['value'] ?? '';
                if ($search) {
                    // Apply search only on non-aggregated columns
                    $builder->groupStart()
                        ->like('c.name', $search)
                        ->orLike('c.mobile', $search)
                        ->orLike('b.branch_name', $search)
                        ->groupEnd();
                }

                if (isset($request->status)) {
                    $builder->where(['v.status' => $request->status]);
                }
             
                if (isset($request->customer_id)) {
                    $builder->where(['v.customer_id' => $request->customer_id]);
                }
                if (isset($request->branch_id)) {
                    $builder->where(['v.branch_id' => $request->branch_id]);
                }
            
                if (isset($request->otp_verified)) {
                    $builder->where(['v.otp_verified' => $request->otp_verified]);
                }
            
                if (isset($request->is_deleted)) {
                    $builder->where(['v.is_deleted' => $request->is_deleted]);
                }

                if (!empty($request->from_date) && empty($request->to_date)) {
                    // Only from_date is set — use exact match
                    $builder->where('DATE(v.ts)', $request->from_date);
                } elseif (!empty($request->from_date) && !empty($request->to_date)) {
                    // Both from_date and to_date are set — use range
                    $builder->where('DATE(v.ts) >=', $request->from_date);
                    $builder->where('DATE(v.ts) <=', $request->to_date);
                }
               

                // echo $builder->getCompiledSelect(); // ← see the full SQL with filters
                // exit;
            })
            ->add('select', function ($row) {
                return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
            }, 'first')
           
            ->add('action', function ($row) {
                $checkAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
                $html = '';
                // if (checkPermission('valuation_management', 'is_edit')) {
                    // $html .= '<a href="' . base_url('valuation/viewValuationData/' . $row->id) . '" class="btn btn-sm btn-primary"  style="margin-right: 5px;" title="View Valuation"><i class="fa fa-eye"></i> </a>';
                    $html .= '<button type="button" class="btn btn-sm btn-primary"  id="btnviewValuation_' . $row->id . '"  style="margin-right: 5px;" data-bs-toggle="tooltip"  title="View Valuation"   data-act="ajax-modal"  data-action-url="' . base_url('valuation/viewValuationData/' . $row->id) . '"><i class="fa fa-eye"></i></button>';
        
                    $html .= '<button type="button" class="btn btn-sm btn-info btn-uploadImage"  id="btnUploadImage_' . $row->id . '" $valuation_id style="margin-right: 5px;" data-bs-toggle="tooltip"  title="Upload Image"   data-act="ajax-modal" data-title="Upload Image" data-action-url="' . base_url('valuation/addValuation_image/' . $row->id) . '"><i class="fa fa-image"></i></button>';

                    if($row->status==1 && $checkAdmin==1){
                        $html .= '<button type="button" class="btn btn-sm btn-success"  style="margin-right: 5px;" data-act="ajax-modal"  data-bs-toggle="tooltip"  title="Edit Valuation"  data-title="Edit Valuation" data-action-url="' . base_url('valuation/addValuationForm/'.$row->customer_id.'/' . $row->id) . '"><i class="fa fa-edit"></i></button>';
                    }
                    if($checkAdmin ==1){
                        if($row->status==1){ 
                            $html .= '<button type="button" class="btn btn-default btnChangeStatus" style="margin-right: 5px;"  data-bs-toggle="tooltip"   title="Recived Valuation"  data-title="Are you sure, you have received the item and want to remove the record ?" data-action-url="' . base_url('valuation/recivedValuation/' . $row->id) . '"><i class="fa fa-check-circle text-success"></i> </i></button>';
                        }else{

                            // $isDeleted = $row->is_deleted ? 0:1;

                            // $buttonClass = $isDeleted ?'btn-danger'  :'btn-success' ;
                            // $icon = $isDeleted ?'fa-recycle'  :'fa-undo' ;
                            // $actionUrl = $isDeleted 
                            //     ? base_url('valuation/deleteValuation/' . $row->id) 
                            //     : base_url('valuation/deleteValuation/' . $row->id);
                            // $confirmText = $isDeleted 
                            //     ? 'Are you sure, you want to delete this record?'
                            //     : 'Are you sure, you want to restore this record?' ;
                            // $tooltipText = $isDeleted ? 'Delete Data /Remove from branch' : 'Restore Data/Show to Branch';
                            // $html .= '<button type="button" 
                            //                 class="btn btn-sm ' . $buttonClass . ' btnIsDeleted" 
                            //                 style="margin-right: 5px;" 
                            //                 title="' . $tooltipText . '"
                            //                 data-bs-toggle="tooltip"
                            //                 data-title="' . $confirmText . '" 
                            //                 data-action-url="' . $actionUrl . '">
                            //                 <i class="fa ' . $icon . '"></i>
                            //         </button>';
                            // $html .= '<button type="button" class="btn btn-sm btn-danger btnIsDeleted" style="margin-right: 5px;"  data-title="Are you sure, you want to delete this record ?" data-action-url="' . base_url('valuation/deleteValuation/' . $row->id) . '"><i class="fa fa-recycle"></i> </button>';
                        }
                        
                    
                        
                    }
                    if($row->otp_verified==0){
                        $html .= '<button type="button" class="btn btn-sm btn-warning resendOtp"
                        data-bs-toggle="tooltip" 
                        title="Resend Verification"  
                        style="margin-right: 5px;"  data-action-url="' . base_url('valuation/resendOtp/' . $row->id) . '"><i class="fa fa-check-circle"></i></button>';
                    }
                    
                // }
                return $html;
            }, 'last')
            ->edit('otp_verified', function ($row) {
                return $row->otp_verified == 1
                    ? '<span class="badge badge-info">Verified</span>'
                    : '<span class="badge badge-warning">Pending</span>';
            })
            ->edit('only_date', function ($row) {
                return date("d-m-Y", strtotime($row->only_date));
            })
            ->edit('status', function ($row) {
                return $row->status == 1
                    ? '<span class="badge badge-success">Pending</span>'
                    : '<span class="badge badge-danger">Deposite</span>';
            })
            ->hide('id')
            ->hide('is_deleted')// Hide is_deleted column
            ->hide('customer_id')// Hide customer_id column
            // ->addNumbering()
            ->toJson();


        return $datatable;

    }

    public function add_Valuation($data,$editid = null)
    {
        $lastValuationID=0;
        $id=$data['id']?$data['id'] :$editid;
        $valuation_data['customer_id'] = $data['customer_id'];
        $valuation_data['branch_id']   = $branch_id = session()->has('branch_id') ? session()->get('branch_id') : null;


       
        

        $valuation_data['otp_verified']= 0;

        if (!empty($data['image'])) {
            $valuation_data['image'] = $data['image'];
            $valuation_data['otp_verified']= $data['otp_verified'];
        }

        if (!empty($data['image2'])) {
            $valuation_data['image2'] = $data['image2'];
        }

        if (!empty($data['client_image'])) {
            $valuation_data['client_image'] = $data['client_image'];
        }

        if (!empty($data['jewellery_image'])) {
            $valuation_data['jewellery_image'] = $data['jewellery_image'];
        }

        if (!empty($id) ) {
            $this->updateRowInDB("valuation", $valuation_data, ["id"=>$id]); 
            $lastValuationID=$id;
        } else {
            $lastValuationID = $this->insertRowInDB("valuation", $valuation_data); 
           
        }
        
        if(!empty($data['image2']) || !empty($data['image'])){
            return true;
        }
        
        $insertedIds = []; 
        
           
        for ($index = 0; $index < count($data['item_id']); $index++) {

            if (empty($data['item_id'][$index]) || empty($data['gross_wt'][$index])) {
                continue;
            }
               
            $item_id = $data['item_id'][$index];
            $val_id = isset($data['val_id'][$index]) ? $data['val_id'][$index] : null;
            $netWeight = $data['net_wt'][$index];
            
            $insert_array = [
                'valuation_id' => $lastValuationID,
                'item_id'       => $this->db->escapeString($item_id),
                'net_weight'    => $this->db->escapeString($netWeight),
                'dust'          => $this->db->escapeString($data['dust'][$index]),
                'gross_weight'  => $this->db->escapeString($data['gross_wt'][$index]),
                'rate'          => $this->db->escapeString($data['rate'][$index]),
                'amount'        => $this->db->escapeString($data['amount'][$index]),
                'round_type'    => $data['round_type'][$index],
                'round_value'   => $this->db->escapeString($data['round'][$index]),
                'ts'            => date("Y-m-d H:i:s")
            ];
            
            if (!empty($val_id) ) {
                $this->updateRowInDB("valuation_details", $insert_array, ['id'=>$val_id]); 
                $insertedIds[] = $val_id;
            } else {
                $insertedId = $this->insertRowInDB("valuation_details", $insert_array); 
                $insertedIds[] = $insertedId;
            }
        }
        // echo $editid;exit;
        $this->sendOtp($lastValuationID);
    
        return $insertedIds; 
        
    }

    public function add_Valuation_image($data, $valuation_id=null,$editid = null){
       
        $filesize = 10000000; // 10MB
        $dirName = "uploads/";
        $path = IMAGES_FOLDER . $dirName;
        isDirectory($path);

        $imageFields = ["image", "image2"];

        foreach ($imageFields as $imageField) {
            $file = $this->request->getFile($imageField);
            
            if (!empty($file) && $file->isValid()) {
                // Delete old image if exists
                $extImg = $this->getRecordOnId("valuation", ["id" => $valuation_id], $imageField);
                if ($extImg && !empty($extImg[$imageField])) {
                    $oldImage = $path . $extImg[$imageField];
                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }

                // Upload new image
                $uploaded = $this->uploadOneFile($file, "file", $filesize, $path);
                $postData[$imageField] = $uploaded["filename"];
            } else {
                
                unset($postData[$imageField]);
            }
        }

    }

    public function verifyOtp($valuationId, $otp){
        $insert_array = [
            'otp_verified'  => 1,
            'otp_code'  => '',
        ];
        $res=$this->updateRowInDB("valuation", $insert_array, ['id' => $valuationId,'otp_code' => $otp]); 
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public function sendOtp($editid){

        $valuation_data['otp_sent_at'] = date("Y-m-d H:i:s");

        $builder = $this->db->table('valuation v');
        $builder->select('v.otp_code, c.name, c.mobile, d.amount, d.net_weight');
        $builder->join('valuation_details d', 'd.valuation_id = v.id');
        $builder->join('ms_customer c', 'v.customer_id = c.id', 'left');
        $builder->where('v.id', $editid);

        $query = $builder->get();
        $results = $query->getResult(); // --> getResult() to fetch all rows

        $otp      = '';
        $c_name   = '';
        $c_mobile = '';
        $total_weight = 0;
        $total_amount = 0;

        if (!empty($results)) {
            // From first row
            $otp=$results[0]->otp_code;
            $c_name   = $results[0]->name;
            $c_mobile = $results[0]->mobile;

            foreach ($results as $row) {
                $total_weight += (float) $row->net_weight;
                $total_amount += (float) $row->amount;
            }
        }   
    
        if (empty($otp) || $otp == "") {
            $otp = rand(100000, 999999);
            $valuation_data['otp_code'] = $otp;
        }

        $name = $c_name;
        $phone = $c_mobile; 

    
        // $msgText = "Dear Customer, Thank you for visiting Sona Cash For Gold. Your payment of Rs. $total_amount has been processed. Your OTP is $otp for transaction weight $total_weight. For any queries, contact us at 8383930310. SONA JEWELLERS";
        $msgText = "Dear $name, Thank you for visiting Sona Cash For Gold. Your payment of Rs. $total_amount has been processed. Your OTP is $otp for transaction weight $total_weight. For any queries, contact us at 8383930310. SONA JEWELLERS";
    
        
        $template_id = '1707174591146701078';
        
        $sms= sendSMS($phone, $template_id, $msgText);

        // print_r($sms);exit;

        if($sms){
            $_SESSION['otp_sent'] = $otp;
            $_SESSION['valuation_id'] = $editid;
            $updatRow=$this->updateRowInDB("valuation", $valuation_data, ["id"=>$editid]); 
            if($updatRow){
                return true;
            }else{
                return false;  
            }
        }else{
            return false;  
        }
    }

    public function recivedValuation($valuation_id=null){
        if (!empty($valuation_id)) {
            $insert_array = [
                'status'  => 0,
                'is_deleted'  => 1,
            ];
            $this->updateRowInDB("valuation", $insert_array, ["id" => $valuation_id]); 
            $insertedIds=true;
            return $insertedIds;
        }
    }
    
    function deleteValuation($valuation_id=null){
        if (!empty($valuation_id)) {
            $extImg = $this->getRecordOnId("valuation", ["id" => $valuation_id], 'is_deleted');

            $currentStatus = isset($extImg['is_deleted']) ? $extImg['is_deleted'] : 0;
            
            $newStatus = ($currentStatus == 1) ? 0 : 1;

            $update_array = [
                'is_deleted' => $newStatus,
            ];

            $this->updateRowInDB("valuation", $update_array, ["id" => $valuation_id]);

            return true;
        }
 
    }
    
}
