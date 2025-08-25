<?php

use App\Controllers\Security_Controller;
use App\Controllers\App_Controller;
use App\Libraries\Pdf;
use App\Libraries\Clean_data;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


/**
 * print array
 * 
 * @return ip
 */
if (!function_exists('p')) {
    function p($array, $exit = null)
    {
        if (is_array($array)) {
            echo "<PRE>";
            print_r($array);
            if ($exit == 1)
                exit;
        }
    }
}

/**
 * check admin login
 * 
 * @return array
 */
if (!function_exists('isAdminLogin')) {
    function isAdminLogin()
    {
        $session = \Config\Services::session();
        $uri_string = uri_string();
        if (empty($session->get('id'))) {
            // app_redirect('/');
            if ($uri_string === "admin/forgot" || $uri_string === "" || $uri_string === "/" || $uri_string === "login" || $uri_string === "admin/checkAdmin") {
                
            } 
            else {
                app_redirect("/login");
            }
        }
        // if (empty($session->get('id')) && ($uri_string == '' || $uri_string == '/' || $uri_string == 'login'))
        
    }
}

/**
 * get users ip address
 * 
 * @return ip
 */
if (!function_exists('get_real_ip')) {

    function get_real_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

/**
 * check if it's localhost
 * 
 * @return boolean
 */
if (!function_exists('is_localhost')) {

    function is_localhost()
    {
        $known_localhost_ip = array(
            '127.0.0.1',
            '::1'
        );
        if (in_array(get_real_ip(), $known_localhost_ip)) {
            return true;
        }
    }
}

/**
 * link the css files 
 * 
 * @param array $array
 * @return print css links
 */
if (!function_exists('load_css')) {

    function load_css(array $array)
    {
        $version = get_setting("app_version");

        foreach ($array as $uri) {
            echo "<link rel='stylesheet' type='text/css' href='" . base_url($uri) . "?v=$version' />";
        }
    }
}


/**
 * link the javascript files 
 * 
 * @param array $array
 * @return print js links
 */
if (!function_exists('load_js')) {

    function load_js(array $array)
    {
        $version = get_setting("app_version");

        foreach ($array as $uri) {
            echo "<script type='text/javascript'  src='" . base_url($uri) . "?v=$version'></script>";
        }
    }
}

/**
 * create a encoded id for sequrity pupose 
 * 
 * @param string $id
 * @param string $salt
 * @return endoded value
 */
if (!function_exists('encode_id')) {

    function encode_id($id, $salt)
    {
        $encrypter = get_encrypter();
        $id = bin2hex($encrypter->encrypt($id . $salt));
        $id = str_replace("=", "~", $id);
        $id = str_replace("+", "_", $id);
        $id = str_replace("/", "-", $id);
        return $id;
    }
}

if (!function_exists('get_encrypter')) {

    function get_encrypter()
    {
        $config = new \Config\Encryption();
        $config->key = config('App')->encryption_key;
        $config->driver = 'OpenSSL';

        return \Config\Services::encrypter($config);
    }
}

/**
 * decode the id which made by encode_id()
 * 
 * @param string $id
 * @param string $salt
 * @return decoded value
 */
if (!function_exists('decode_id')) {

    function decode_id($id, $salt)
    {
        $encrypter = get_encrypter();
        if ($id) {
            $id = str_replace("_", "+", $id);
            $id = str_replace("~", "=", $id);
            $id = str_replace("-", "/", $id);
        }


        try {
            $id = $encrypter->decrypt(hex2bin($id));

            if ($id && strpos($id, $salt) != false) {
                return str_replace($salt, "", $id);
            } else {
                return "";
            }
        } catch (\Exception $ex) {
            return "";
        }
    }
}

/**
 * use this to print link location
 *
 * @param string $uri
 * @return print url
 */
if (!function_exists('echo_uri')) {

    function echo_uri($uri = "")
    {
        echo get_uri($uri);
    }
}

/**
 * prepare uri
 * 
 * @param string $uri
 * @return full url 
 */
if (!function_exists('get_segments')) {

    function get_segments()
    {
        $uri = service('uri');
        return $uri->getSegments();
    }
}

/**
 * prepare uri
 * 
 * @param string $uri
 * @return full url 
 */
if (!function_exists('get_uri')) {

    function get_uri($uri = "")
    {
        $index_page = config("App")->indexPage;
        return base_url($index_page) . '/' . $uri;
    }
}

/**
 * use this to print file path
 * 
 * @param string $uri
 * @return full url of the given file path
 */
if (!function_exists('get_file_uri')) {

    function get_file_uri($uri = "")
    {
        return base_url($uri);
    }
}


/**
 * redirect to a location within the app
 * 
 * @param string $url
 * @return void
 */
if (!function_exists('app_redirect')) {

    function app_redirect($url, $global_link = false)
    {
        $url = base_url($url);
        header("Location:$url");
        exit;
    }
}

/**
 * set session flash data
 * 
 * @param string $string
 * @return 
 */
if (!function_exists('set_flashdata')) {

    function set_flashdata($key, $data)
    {
        $session = \Config\Services::session();
        $session->setFlashdata($key, $data);
    }
}

/**
 * set session flash data
 * 
 * @param string $string
 * @return 
 */
if (!function_exists('get_flashdata')) {

    function get_flashdata($key)
    {
        $session = \Config\Services::session();
        if ($session->getFlashdata($key)) {
            if ($key == 'formSuccess')
                return $session->getFlashdata($key);
            else
                return $session->getFlashdata($key);
        } else
            return false;
    }
}

function dropdownStr($valArray, $select = '', $label = '')
{
    if ($label == "")
        $label = "Select";

    $option = '<option value="">' . $label . '</option>';
    if (count($valArray) > 0) {
        foreach ($valArray as $k => $v) {
            //$k = $value['_id']->{'$id'};
            $sel = '';
            if ($select == $k)
                $sel = 'selected="selected"';
            $option .= '<option ' . $sel . ' value="' . $k . '">' . $v . '</option>';
        }
    }
    return $option;
}


// function getAvailableFund($b_id)
// {
  
//     $db = \Config\Database::connect();      

//     $result = $db->table('rpt_ledger')
//             ->select('available_balance')
//             ->where('branch_id', $b_id)
//             ->orderBy('ts', 'DESC')
//             ->orderBy('id', 'DESC')
//             ->limit(1)
//             ->get()
//             ->getRow();
//     return number_format($result ? $result->available_balance : 0, 2, '.', '');

//     // return $result ? $result->available_balance : 0;
// }

function getAvailableFund($b_id = null)
{
    $db = \Config\Database::connect();
    $isAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
    $branchId = session()->get('branch_id');

    // Super Admin: Get latest available_balance per branch, then sum
    if (!empty($isAdmin) && $isAdmin > 0) {
        $builder = $db->query("
            SELECT rl1.branch_id, rl1.available_balance
            FROM rpt_ledger rl1
            INNER JOIN (
                SELECT branch_id, MAX(ts) AS max_ts
                FROM rpt_ledger
                GROUP BY branch_id
            ) latest ON rl1.branch_id = latest.branch_id AND rl1.ts = latest.max_ts
        ");

        $total = 0;
        foreach ($builder->getResult() as $row) {
            $total += (float)$row->available_balance;
        }
        return number_format($total, 2, '.', '');
    }

    // Regular branch user
    if (!$b_id) {
        return number_format(0, 2, '.', '');
    }

    $latest = $db->table('rpt_ledger')
        ->select('available_balance')
        ->where('branch_id', $b_id)
        ->orderBy('ts', 'DESC')
        ->orderBy('id', 'DESC')
        ->limit(1)
        ->get()
        ->getRow();

    return number_format($latest ? $latest->available_balance : 0, 2, '.', '');
}


function getAvailableFundBranch($b_id = null)
{
    $db = \Config\Database::connect();
    $isAdmin = session()->has('is_admin') ? session()->get('is_admin') : null;
    $branchId = session()->get('branch_id');

    // If branch ID is not provided, return 0
    if (empty($b_id)) {
        return number_format(0, 2, '.', '');
    }

    // Get the latest available_balance for the given branch
    $latest = $db->table('rpt_ledger')
        ->select('available_balance')
        ->where('branch_id', $b_id)
        ->orderBy('ts', 'DESC')
        ->orderBy('id', 'DESC')
        ->limit(1)
        ->get()
        ->getRow();

    // Return the formatted available balance (or 0 if no record)
    return number_format($latest ? (float) $latest->available_balance : 0, 2, '.', '');
}


function dropdownStrforValue(array $valArray, string $select = '', string $label = 'Select'): string
{
    $options = '<option value="">' . htmlspecialchars($label) . '</option>';

    if (count($valArray) > 0) {
        foreach ($valArray as $value) {
            $selected = ($select == $value) ? 'selected="selected"' : '';
            $options .= '<option ' . $selected . ' value="' . htmlspecialchars($value) . '">' . htmlspecialchars($value) . '</option>';
        }
    }
    return $options;
}
	
function dropdownStrMultiselect($valArray,$select = [], $label='')
{
   
    // if($label)
    //     $option = '<option value="">'.$label.'</option>';
    // else
    //     $label = "";
    if ($label == "")
        $label = "Select";  
    
    $option = '<option value="">' . $label . '</option>';

    if(count($valArray) > 0)
    {
        foreach($valArray as $k=>$v)
        {
            
            if ($select && in_array($k, $select)) {
                $sel = 'selected="selected"';
            } else {
                $sel = '';
            }
            $option .= '<option '.$sel.' value="'.$k.'">'.$v.'</option>';
        }
    }
    return  $option;
}


/**
 * set session data by key
 * 
 * @param string $string
 * @return 
 */
if (!function_exists('get_session')) {
    function get_session($key)
    {
        $session = \Config\Services::session();
        return $session->get($key);
    }
}

/**
 * create directory and get permission
 * 
 * @param string $string
 * @return 
 */
if (!function_exists('isDirectory')) {
    function isDirectory($path)
    {
        // Ensure path has trailing slash
        $path = rtrim($path, '/') . '/';

        // Check if the directory exists
        if (!file_exists($path)) {
            // Check if the parent directory is writable
            $parentDir = dirname($path);
            if (!is_writable($parentDir)) {
                // If not writable, log the error and return false
                error_log("Directory creation failed: Parent directory is not writable.");
                return false;
            }

            // Try to create the directory
            if (mkdir($path, 0777, true)) {
                // Set permissions for the directory
                chmod($path, 0777);
                
                // Create an index.html file to prevent directory listing
                fopen($path . 'index.html', 'w');
            } else {
                // Log if mkdir fails
                error_log("Failed to create directory: $path");
                return false;
            }
        }

        // Return true if the directory exists, false otherwise
        return file_exists($path);
    }
}

/*
if (!function_exists('isDirectory')) {
    function isDirectory($path)
    {
        
        if (substr($path, -1) == '/')
            $path = $path;
        else
            $path = $path . '/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
            fopen($path . '/index.html', 'w');
        }

        if (file_exists($path))
            return true;
        else
            return false;
    }
}
*/

if (!function_exists('keyValueArray')) {
    function keyValueArray($array)
    {
        $data = [];
        if ($array) {
            foreach ($array as $key => $value) {
                $data[$value['id']] = $value['name'];
            }
        }
        return $data;
    }
}

function debugging($payload)
{
    $CI = \Config\Database::connect();
    $router = service('router');
    $data = [
        "module_name" => $router->controllerName(),
        "function" => $router->methodName(),
        "payload" => @$payload,
        "created_at" => date("Y-m-d H:i:s")
    ];
    $builder = $CI->table("debugging");
    $builder->insert($data);
}


/**
 * Mail function with Multiple CC BCC and Attachments
 * @param string $key
 * @return config value
 */
if (!function_exists('send_mail_func')) {

    function send_mail_func($to, $subject, $body, $optionsData, $invoice_pdf=null, $is_update = null)
    {
        // p($optionsData);exit;
        $email_config = new PHPMailer(true);
        try {
            // $email_config->SMTPDebug = 2;
            // $email_config->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $email_config->isSMTP();                                            //Send using SMTP
            $email_config->Host = HOST;
            $email_config->SMTPAuth = true;
            $email_config->Username = USER_NAME;
            $email_config->Password = PASSWORD;
            $email_config->SMTPSecure = SMTP_SECURE;
            $email_config->Port = SMTP_PORT;
            $email_config->isHTML(true);

            $email_config->setFrom(USER_NAME, 'Boostmytalent');
            $email_config->addAddress($to);     //Add a recipient

            //add attachment
            if($optionsData) {
                foreach($optionsData as $value) {
                  $email_config->AddAttachment(trim($value));
                }
              }
            
            if($invoice_pdf) {
                $email_config->addStringAttachment($invoice_pdf["pdfContent"], $invoice_pdf["file_name"], 'base64','application/pdf');
            }
            
            //check reply-to
            $reply_to = get_array_value($optionsData, "ReplyTo");
            if ($reply_to) {
                $email_config->AddReplyTo("Reply-To", $reply_to);
            }

            $email_config->Subject = $subject;
            $email_config->Body = $body;
            $status="0";
            if ($email_config->send()) {
                $status="1";
            } 
            //else {
            //     echo "Message could not be sent. Mailer Error: {$email_config->ErrorInfo}";
            // }
            $CI = \Config\Database::connect();
            if($is_update == 1) {}
            else {
                $log_array=[
                    "subject" => $subject,
                    "email" => $to,
                    "msg" => $body,
                    "status" => $status,
                    "ts" => date("Y-m-d H:i:s")
                ];
                
                $builder = $CI->table("ms_emaillog");
                $builder->insert($log_array);
            }

            return $status;
        } catch (\Exception $e) {
            debugging($e->getMessage());
        }
    }
}

function mail_data_replace($mail_body) {
    // $logo = '<img src="' . base_url("assets/images/logo.png") . '" style="width: 330px">';
    // $mail_body = str_replace("{LOGO_IMG}", $logo, $mail_body);
    $mail_body = str_replace("{SIGNATURE}", MAIL_SIGNATURE, $mail_body);
    $mail_body = str_replace("{SUPPORT_EMAIL}", SUPPORT_EMAIL, $mail_body);
    $mail_body = str_replace("{SUPPORT_MOBILE}", SUPPORT_MOBILE, $mail_body);
    return $mail_body;
}


/**
 * Check if staff user has permission
 * @param  string  $permission permission shortname
 * @param  mixed  $staffid if you want to check for particular staff
 * @return boolean
 */
//function has_permission($permission, $staffid = '', $can = '')
function checkPermission($module_name, $is_check = "", $user_id = "")
{

    $session = \Config\Services::session();
    // all access to admin
    $CI = \Config\Database::connect();
    $builder = $CI->table("admin");
    $builder->select('is_admin');
    $builder->where(["id" => $session->get("id")]);
    $query = $builder->get();
    $result = $query->getRowArray();

    if (@$result['is_admin'] > 0) {
        return 1;
        exit();
    }

    try {
        $joins = [["table" => "ms_modules m", "condition" => 'u.module_id=m.id', "jointype" => 'left']];

        if ($is_check == "is_add") {
            $arr = ["u.is_add" => 1];
        }
        if ($is_check == "is_view") {
            $arr = ["u.is_view" => 1];
        }
        if ($is_check == "is_edit") {
            $arr = ["u.is_edit" => 1];
        }
        if (!empty($user_id)) {
            $uid = $user_id;
        } else {
            $uid = $session->get('id');
        }
        $where = array_merge(["m.slug" => $module_name, "u.staffid" => $uid], $arr);

        $CI = \Config\Database::connect();
        $builder = $CI->table("ms_module_permissions u");
        $builder->select('u.id,u.is_add,u.is_edit,u.is_view');

        if (count($where) > 0) {
            foreach ($where as $col => $val) {
                $builder->where($col, $val);
            }
        }

        if (is_array($joins) && count($joins) > 0) {
            foreach ($joins as $k => $v) {
                $builder->join($v['table'], $v['condition'], $v['jointype']);
            }
        }

        $query = $builder->get();
        $result = $query->getRowArray();
    } catch (Exception $e) {
        // logError(
        //     'common_helper.php/getRecordOnId',
        //     'Could not fetch record. Table: ' . $table,
        //     json_encode($e->getUserMessage())
        // );
        return $e->getMessage();
    }
    return isset($result[$is_check]) ? $result[$is_check] : 0;
}



/**
 * Check if staff user has permission
 * @param  string  $permission permission shortname
 * @param  mixed  $staffid if you want to check for particular staff
 * @return boolean
 */
//function has_permission($permission, $staffid = '', $can = '')
function check_centerPermission($module_name, $is_check = "", $user_id = "")
{

    $session = \Config\Services::session();
    // all access to admin
    $CI = \Config\Database::connect();
    $builder = $CI->table("admin");
    $builder->select('is_admin');
    if($user_id)
        $builder->where(["id" => $user_id]);
    else 
        $builder->where(["id" => $session->get("id")]);
    $query = $builder->get();
    $result = $query->getRowArray();

    if (@$result['is_admin'] > 0) {
        return 1;
        exit();
    }

    try {
        $joins = [["table" => "ms_modules m", "condition" => 'u.module_id=m.id', "jointype" => 'left']];  

        if ($is_check == "is_add") {
            $arr = ["u.is_add" => 1];
        }
        if ($is_check == "is_view") {
            $arr = ["u.is_view" => 1];
        }
        if ($is_check == "is_edit") {
            $arr = ["u.is_edit" => 1];
        }
        if (!empty($user_id)) {
            $uid = $user_id;
        } else {
            $uid = $session->get('id');
        }
        $where = array_merge(["m.slug" => $module_name, "u.staffid" => $uid], $arr);

        $CI = \Config\Database::connect();
        $builder = $CI->table("ms_module_permissions u");
        $builder->select('u.id,u.is_add,u.is_edit,u.is_view');

        if (count($where) > 0) {
            foreach ($where as $col => $val) {
                $builder->where($col, $val);
            }
        }

        if (is_array($joins) && count($joins) > 0) {
            foreach ($joins as $k => $v) {
                $builder->join($v['table'], $v['condition'], $v['jointype']);
            }
        }

        $query = $builder->get();
        $result = $query->getRowArray();
        
    } catch (Exception $e) {
        debugging($e->getMessage());
    }
    return isset($result[$is_check]) ? $result[$is_check] : 0;
}
/**
 * use this to print file path
 * 
 * @param string $uri
 * @return full url of the given file path
 */
if (!function_exists('get_date_diff')) {

    function get_date_diff($from_date, $to_date)
    {
        $from_date = strtotime($from_date);
        $to_date = strtotime($to_date);
        $datediff = $to_date - $from_date;
        return round($datediff / (60 * 60 * 24));
    }
}


function getIndianCurrency(float $number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'rupees ' : '') . $paise;
}


if (!function_exists('whatsapp_date_format')) {

    function whatsapp_date_format($date)
    {
        if($date)
            return date("d M, Y", strtotime($date));
        else
            return '';
    }
}



if (!function_exists('get_actual_controller_name')) {

    function get_actual_controller_name($router)
    {
        $controller_name = $router->controllerName();
        $controller_name = explode("\\", $controller_name);
        return end($controller_name);
    }
}

/* ############################################################################################# */
/* ################################### SMS GATEWAY ############################################# */
/* ############################################################################################# */

if (!function_exists('sendSMS')) {

    function sendSMS($phone,$template_id,$msg)
    {
        // Capture start time
        $start_time = microtime(true);
        
        $sender_id = 'SONJWL';
        $username = 'sonacash';
        $apikey = 'C033F-7AFE6';
        $uri = 'https://alots.in/sms-panel/api/http/index.php';
        $data = array(
        'username'=> $username,
        'apikey'=> $apikey,
        'apirequest'=>'Text',
        'sender'=> $sender_id,
        'route'=>'TRANS',
        'format'=>'JSON',
        'message'=> $msg,
        'mobile'=> $phone,
        'TemplateID' => $template_id,
        );
        

        // String username = "sonacash";
        // String apikey = "C033F-7AFE6";
        // String mobile = "9890098900";
        // String message = "Dear Customer, Thank you for visiting Sona Cash For Gold. Your payment of Rs. {#var#} has been processed. Your OTP is {#var#} for transaction weight {#var#}. For any queries, contact us at 8383930310. SONA JEWELLERS";
        // String sender = "SONJWL";
        // String apirequest = "Text";
        // String route = "TRANS";   

        $ch = curl_init($uri);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        
        $resp = curl_exec($ch);

        // return $resp;
        
        $error = curl_error($ch);
        
        // Capture end time
        $end_time = microtime(true);
        
        // Calculate request and response time
        $request_response_time = $end_time - $start_time;
        
        // Get server information
        $server_info = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        
        // Log request and response
        $logMessage = "Request:\n" . $server_info . "\n\n" . json_encode($data) . "\n\n";
        $logMessage .= "Response:\n" . $resp . "\n\n";
        $logMessage .= "Request/Response Time: " . $request_response_time . " seconds\n\n";
        
        // Write log message to file
        $logFile = fopen('curl_log.txt', 'a'); // Open log file for appending
        fwrite($logFile, $logMessage);
        fclose($logFile);
        
        curl_close ($ch);
        // echo json_encode(compact('resp', 'error'));
        return true;
    }
}

/*
 *=================================================================================================
 *===============================   BELOW CODE IS NOT IN USE  =====================================
 *=================================================================================================
*/







/**
 * decode html data which submited using a encode method of encodeAjaxPostData() function
 * 
 * @param string $html
 * @return htmle
 */
if (!function_exists('decode_ajax_post_data')) {

    function decode_ajax_post_data($html)
    {
        $html = str_replace("~", "=", $html);
        $html = str_replace("^", "&", $html);
        return $html;
    }
}

/**
 * check if fields has any value or not. and generate a error message for null value
 * 
 * @param array $fields
 * @return throw error for bad value
 */
if (!function_exists('check_required_hidden_fields')) {

    function check_required_hidden_fields($fields = array())
    {
        $has_error = false;
        foreach ($fields as $field) {
            if (!$field) {
                $has_error = true;
            }
        }
        if ($has_error) {
            echo json_encode(array("success" => false, 'message' => app_lang('something_went_wrong')));
            exit();
        }
    }
}


function validate_email($email, $slug)
{
    if (!empty($email) && isset($email)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array("error" => false, 'message' => $slug . ' is not a valid email address.'));
            exit;
        }
    }
}

function validate_url($url, $slug)
{
    if (!empty($url) && isset($url)) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            echo json_encode(array("error" => false, 'message' => $slug . ' is invalid, please enter correct url.'));
            exit;
        }
    }
}

function validate_mobile($mobile, $slug)
{
    if (!empty($mobile) && isset($mobile)) {
        if (!preg_match('/^[0-9]{10}+$/', $mobile)) {
            echo json_encode(array("error" => false, 'message' => 'Invalid mobile, please try again'));
            exit;
        }
    }
}


function displayDateFormat($date){
    $date = str_replace('/', '-', $date);
    if($date != '0000-00-00' && $date != "")
        return date('d/m/Y', strtotime($date));
}

function saveDateFormat($date){
    $date = str_replace('/', '-', $date);
    if($date != '0000-00-00' && $date != "")
    return date('Y-m-d', strtotime($date));
}

function displayDateFormat_monthname($date){
    $date = str_replace('/', '-', $date);
    if($date != '0000-00-00' && $date != "")
        return date('M d, Y', strtotime($date));
}

/**
 * check the array key and return the value 
 * 
 * @param array $array
 * @return extract array value safely
 */
if (!function_exists('get_array_value')) {

    function get_array_value($array, $key)
    {
        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }
    }
}

/**
 * create directory and get permission
 * 
 * @param string $string
 * @return 
 */
if (!function_exists('generate_slug')) {
    function generate_slug($title_string)
    {
        $name = str_replace('&', 'and', $title_string);
		$newname = str_replace(' ', '-', $name);
		$nname = str_replace('(', '-', $newname);
		return strtolower(str_replace(')', '-', $nname));
    }
}


// Get callyzer call logs
if (!function_exists('get_calllog')) {
    function get_calllog($APIName,$request_data)
    {
        // API URL
        $url = "https://api1.callyzer.co/api/v2/$APIName";
        // Bearer token
        $token = "b983d9f7-4587-4938-919e-c2972d70a919";

        // Raw JSON data to be sent in the body
        $data = json_encode($request_data);

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // Set to true if you need to send a POST request
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            die('cURL error: ' . $error);
        }

        // Close cURL session
        curl_close($ch);

        // Decode the response (assuming the response is in JSON format)
        $responseData = json_decode($response, true);

        // Process the response data
        if (json_last_error() === JSON_ERROR_NONE) {
            // Successfully decoded the JSON response
            // var_dump($responseData);
            return $responseData;
        } else {
            // JSON decode error
            die('JSON decode error: ' . json_last_error_msg());
        }
    }
}

// Get callyzer call logs
if (!function_exists('download_logfile')) {
    function download_logfile($filePath, $fileUrl=null)
    {

        $parsedUrl = parse_url($fileUrl, PHP_URL_PATH);
        $fileName = basename($parsedUrl);

        // Path to save the downloaded file
        $path = MP3_FOLDER . $filePath;
        // echo $path; exit;
        isDirectory($path);
        $saveTo = "$path/$fileName";

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $fileUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects if any

        // Execute cURL session
        $response = curl_exec($ch);

        // Check for cURL errors
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            die('cURL error: ' . $error);
        }

        // Check HTTP response code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            curl_close($ch);
            die('Error: HTTP response code ' . $httpCode);
        }

        // Close cURL session
        curl_close($ch);

        // Write the response to a file
        $fileHandle = fopen($saveTo, 'w');
        if ($fileHandle === false) {
            die('Error: Unable to open file for writing');
        }

        fwrite($fileHandle, $response);
        fclose($fileHandle);

        // echo "File downloaded successfully!";
        return $fileName;

    }
}


function deleteDirectory($dir) {
    // Check if the directory exists
    if (is_dir($dir)) {
        // Get all files and folders in the directory
        $files = scandir($dir);
        
        foreach ($files as $file) {
            // Skip '.' and '..' which represent the current and parent directories
            if ($file !== '.' && $file !== '..') {
                $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                
                // Recursively delete directories or files
                if (is_dir($filePath)) {
                    deleteDirectory($filePath);
                } else {
                    unlink($filePath);  // Delete the file
                }
            }
        }
        
        // Remove the directory itself
        rmdir($dir);
        return true;
    } else {
        return false;
    }
}


function maskEmail($email) {
    if($email) {
        $email_parts = explode("@", $email);
        $name = $email_parts[0];
        $len = strlen($name);
        
        if ($len <= 2) {
            $masked_name = str_repeat("*", $len);
        } else {
            $masked_name = substr($name, 0, 1) . str_repeat("*", $len - 2) . substr($name, -1);
        }
    
        return $masked_name . "@" . $email_parts[1];
    } else {
        return '';
    }
}

function maskMobileNumber($number) {
    if($number) {
        $len = strlen($number);
        
        if ($len <= 4) {
            return str_repeat("*", $len);
        } else {
            return str_repeat("*", $len - 4) . substr($number, -4);
        }
    } else {
        return '';
    }
}

function timeDifference_minutes($from_date, $to_date=null) {
        // Define two dates
    if($to_date)
        $to_date = $to_date;
    else 
        $to_date = date("Y-m-d H:i:s");
    $date1 = new DateTime($from_date);
    $date2 = new DateTime($to_date);
    // $date1 = new DateTime("2024-10-01 12:30:00");
    // $date2 = new DateTime("2024-10-14 15:45:00");

    // Calculate the difference
    $interval = $date1->diff($date2);

    // Convert the difference to total minutes
    return $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

    // Display the total difference in minutes
    echo "Difference: " . $totalMinutes . " minutes.";
}

function removeSpacesFromArray($data) {
    return array_map(function ($value) {
        return is_string($value) ? trim($value) : $value;
    }, $data);
}




function Const_Lead_Status() {
    return $subStatus = emptyStatus + HighStatus + LowStatus + NIStatus;
}




