<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Files\File;
use stdClass;

//extend from this model to execute basic db operations
class Crud_model extends Model
{

    protected $table;
    protected $db;
    protected $db_builder = null;
    private $log_activity = false;
    private $log_type = "";
    private $log_for = "";
    private $log_for_key = "";
    private $log_for2 = "";
    private $log_for_key2 = "";
    protected $allowedFields = array();
    private $Activity_logs_model;
    protected $helpers = ['form'];

    function __construct($table = null)
    {
        //$this->Activity_logs_model = model("App\Models\Activity_logs_model");
        $this->db = db_connect('default');
        //$this->db->query("SET sql_mode = ''");
        //$this->use_table($table);
    }


    protected function use_table($table)
    {
        $db_prefix = $this->db->getPrefix();
        $this->table = $db_prefix . $table;
        $this->db_builder = $this->db->table($this->table);
    }

    protected function disable_log_activity()
    {
        $this->log_activity = false;
    }

    protected function init_activity_log($log_type = "", $log_type_title_key = "", $log_for = "", $log_for_key = 0, $log_for2 = "", $log_for_key2 = 0)
    {
        if ($log_type) {
            $this->log_activity = true;
            $this->log_type = $log_type;
            $this->log_type_title_key = $log_type_title_key;
            $this->log_for = $log_for;
            $this->log_for_key = $log_for_key;
            $this->log_for2 = $log_for2;
            $this->log_for_key2 = $log_for_key2;
        }
    }

    function get_one($id = 0)
    {
        return $this->get_one_where(array('id' => $id));
    }

    function get_one_where($where = array())
    {
        $where = $this->escape_array($where);
        $result = $this->db_builder->getWhere($where, 1);

        if ($result->getRow()) {
            return $result->getRow();
        } else {
            $db_fields = $this->db->getFieldNames($this->table);
            $fields = new \stdClass();
            foreach ($db_fields as $field) {
                $fields->$field = "";
            }

            return $fields;
        }
    }

    function get_all($include_deleted = false)
    {
        $where = array("deleted" => 0);
        if ($include_deleted) {
            $where = array();
        }
        return $this->get_all_where($where);
    }


    /**
     * get any row from any table
     * @param string $table table name
     * @param string $where where condition array
     * @return array  single row from table
     * use like - getRecordOnId('users', ['id' => 5],"id,name,phone");
     * @author vp
     */
    function getRecordOnId($table, $where = [], $cols = '', $joins = [])
    {
        $result = [];
        $builder = $this->db->table($table);
        try {
            if ($cols != '') {
                $builder->select($cols);
            } else {
                $builder->select('*');
            }
            if (is_array($where) && count($where) > 0) {
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
                //  echo "<pre>";
                // print_r($this->db->getLastQuery()->getQuery());
                // print_r($result->getRowArray());
                // die;
            if (isset($query)) {
                $result = $query->getRowArray();
            }
        } catch (\Exception $e) {
            // logError(
            //     'common_helper.php/getRecordOnId',
            //     'Could not fetch record. Table: ' . $table,
            //     json_encode($e->getUserMessage())
            // );
            return $result;
        }
        // echo "<pre>";print_r($result);die;
        return $result;
    }

    /**
     * update single rowe in any table
     * @param string $table table name
     * @param array $update_data update data array
     * @param array $where where condition array
     * @return inserted id insert id of a row on success else 0
     * use like - updateRowInDB('table_name', $update_data, ['id' => 5]);
     * @author vp
     */
    function updateRowInDB($table, $update_data, $where = [])
    {

        $error_code = -1;
        $builder = $this->db->table($table);

        try {
            if (count($where) > 0) {
                foreach ($where as $col => $val) {
                    if(is_array($val))
                        $builder->whereIn($col, $val);
                    else
                        $builder->where($col, $val);
                }
            }

            $update = $builder->update($update_data);     
            // echo "<pre>";
            // print_r($this->db->getLastQuery()->getQuery());
            // die;
            if ($this->db->affectedRows()) {
                $error_code = $this->db->affectedRows();
            } else {
                $error_code = 0;
            }
        } catch (\Exception $e) {
            //echo $e->getMessage();
            // debug(); to save exceptions if occur
            // echo json_encode($e->getMessage()); add this to debug table
        }

        return $error_code;
    }


    /**
     * get all rows from any table
     * @param string $table table name
     * @param string $cols comma seperated column names to select values
     * @param array $where where conditions array
     * @param array $joins join array, if you want to use right or left join then DO NOT USE this function - use a custom one
     * @return array  single row from table
     * use like -
     *  $joins = [
     *           ['table' => 'users u','condition' => 'u.id = m.from_id','jointype' => ''],
     *           ['table' => 'users u1','condition' => 'u1.id = m.to_id','jointype' => 'LEFT']
     *       ];
     *	$get_messages = getAllRecords(
     *		'messages m',
     *		'm.from_id,u.user_type as from_type,concat(u.fname," ",u.lname) as from_name,m.to_id,u1.user_type as to_type,concat(u1.fname," ",u1.lname) as to_name,m.message,m.sent_at,m.seen_status',
     *		['inquiry_id' => $inquiry_id],
     *		$joins,
     *		['m.sent_at'=>'ASC']
     *	);
     * @author vp
     * Where in or where in in group -- IMPORTANT
     */
    function getAllRecords(
        $table,
        $cols = '',
        $where = [],
        $joins = [],
        $group = [],
        $order = [],
        $limit = null,
        $where_in = [],
        $where_not_in = [],
        $or_where_in = [],
        $use_group = 0,
        $offset = null
    ) {

        $builder = $this->db->table($table);
        try {
            if ($cols != '') {
                $builder->select($cols, false);
            } else {
                $builder->select('*');
            }

            if (is_array($where) && count($where) > 0) {
                foreach ($where as $col => $val) {
                    $builder->where($col, $val);
                }
            }

            if ($use_group) {
                $builder->group_start();
            }
            if (is_array($where_in) && count($where_in) > 0) {
                foreach ($where_in as $col => $ids) {
                    $builder->whereIn($col, $ids);
                }
            }

            if (is_array($or_where_in) && count($or_where_in) > 0) {
                foreach ($or_where_in as $col => $ids) {
                    $builder->orWhereIn($col, $ids);
                }
            }
            if ($use_group) {
                $builder->group_end();
            }

            if (is_array($where_not_in) && count($where_not_in) > 0) {
                foreach ($where_not_in as $col => $ids) {
                    $builder->whereNotIn($col, $ids);
                }
            }

            if (is_array($joins) && count($joins) > 0) {
                foreach ($joins as $k => $v) {
                    $builder->join($v['table'], $v['condition'], $v['jointype']);
                }
            }

            if (is_array($group) && count($group) > 0) {
                foreach ($group as $group_by) {
                    $builder->groupBy($group_by);
                }
            }

            if (is_array($order) && count($order) > 0) {
                foreach ($order as $col => $direction) {
                    $builder->orderBy($col, $direction);
                }
            }

            if ($limit != '') {
                if ($offset != "") {
                    $builder->limit($limit, $offset);
                } else {
                    $builder->limit($limit);
                }
            }
            // echo "<pre>";
            // echo $builder->getCompiledSelect();
            // exit;

            $data = $builder->get();
        

            $query = $builder->countAll();
            if ($query !== false && $query > 0) {
                $result = $data->getResultArray();
                return $result;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            // logError(
            //     'common_helper.php/getAllRecords',
            //     'Could not fetch record. Table: ' . $table,
            //     json_encode($e->getUserMessage())
            // );
            return [];
        }
    }

    /**
     * insert single rowe in any table
     * @param string $table table name
     * @param array $insert_data insert data array
     * @return inserted id insert id of a row on success else 0
     * use like - insertRowInDB('table_name', ['id' => 5]);
     * @author vp
     */
    function insertRowInDB($table, $insert_data)
    {
    
        $builder = $this->db->table($table);
        $inserted_id = 0;
        try {
            $builder->insert($insert_data);
            

            // print_r($this->db->getLastQuery()->getQuery());
            // // print_r($result->getRowArray());
            $inserted_id = $this->db->insertID();
            
        } catch (\Exception $e) {
            debugging($e->getMessage());
            // logError(
            //     'common_helper.php/insertRowInDB',
            //     'Could not insert to db. Table: ' . $table,
            //     json_encode($e->getUserMessage())
            // );
        }
        return $inserted_id;
    }

    function deleteRowFromDB($table, $table_ids, $primary_field, $primary_id){
        try{
            $builder = $this->db->table($table);
            if($table_ids) {
                $builder->whereNotIn('id', $table_ids);
            }
            $builder->where($primary_field, $primary_id);
            $builder->delete();

            $affectedRows = $this->db->affectedRows();
            if ($affectedRows > 0) {
                return $affectedRows; // Successfully deleted rows
            } elseif ($affectedRows === 0) {
                return 0; // No rows matched for deletion
            } else {
                return false; // Failure in execution
            }


        }catch(\Exception $e){
            debugging($e->getMessage());
        }
    }

    function getSumOfRecords($table, $where, $column_str)
    {
        $builder = $this->db->table($table);
        try {
            $builder->select_sum($column_str);
            if (is_array($where) && count($where) > 0) {
                foreach ($where as $col => $val) {
                    $builder->getWhere($col, $val);
                }
            }
            $query = $builder->get();
            return $query->getResultArray();
        } catch (\Exception $e) {
            // logError(
            //     'common_helper.php/getSumOnId',
            //     'Could not fetch sum. Table: ' . $table,
            //     json_encode($e->getUserMessage())
            // );
            return 0;
        }
        return 0;
    }


    /**
     * Upload any one image 
     * @param string $file
     * @param string $input box name 
     * @param int $filesize ($filesize = 2000000) 2MB
     * @author vp
     * */
    function uploadOneFile($file, $filename, $filesize, $upload_path,$new_fileName=null)
    {
        $response = array();
        $file_mime = $file->getMimeType();
        $validated = 0;
        $data = array();
        // echo "<pre>";print_r(basename($file))."=============="."<br>";
        if ($upload_path) {
            isDirectory($upload_path);
        } else {
            isDirectory("uploads/other/");
        }

        if ($file_mime == "image/jpg" || $file_mime == "image/jpeg" || $file_mime == "image/gif" || $file_mime == "image/png" || $file_mime == "image/webp" || $file_mime == "image/svg+xml" || $file_mime == "application/pdf") {
            $validated = 1;
        } else {
            $validated = 1;
            $data = ["error" => "File is not an image"];
            return $data;
            exit;
        }

        if ($file->getSize() > $filesize) {
            $validated = 1;
            $data = ["error" => "Max file size is 1MB"];
            return $data;
            exit;
        }

        $img = $file;

        if (!$img->hasMoved()) {
            $path =  array();
            $ext = $img->guessExtension();
            // Get random file name
            if($new_fileName) {
                $newName = $new_fileName;
            } else {
                $timeStamp = $this->get_milliseconds();
                $newName = date("dmyyis").$timeStamp . "." . $ext;                
            }
                


            $img->move($upload_path, $newName);
            // File path to display preview
            $filepath = $newName;
        }
        $response = ['success' => 1, 'filename' => $filepath];
        return $response;
    }

    public function get_milliseconds()
    {
        $timeofday = gettimeofday();
        return sprintf('%d%d', $timeofday["sec"], $timeofday["usec"] / 1000);
    }
   

    
    //============ COMMON DROPDOWN LISTING ==================
    function getBranchList(){
        $builder = $this->db->table("branch");
        $builder->select('id, branch_name as name')->where('status', '1')->orderBy("branch_name");
        $query = $builder->get();
        if (isset($query)) {
            return keyValueArray($query->getResultArray());
        } else
            return false;
    }
    
    function get_roleList(){
        $builder = $this->db->table("designation");
        $builder->select('id, name')->where('status', '1')->orderBy("name");
        $query = $builder->get();
        if (isset($query)) {
            return keyValueArray($query->getResultArray());
        } else
            return false;
    }

    function get_modulesList($where = array())
    {
        $builder = $this->db->table("ms_modules");
        $builder->select('*');
        $builder->where("status ", "1");
        $query = $builder->get();
        if (isset($query)) {
            return $query->getResultArray();
        } else
            return false;
    }

    function get_customerList($where = array())
    {
        $builder = $this->db->table("ms_customer");
        $builder->select("id, CONCAT_WS(' - ', name, mobile) as name")->where('status', '1');
        $query = $builder->get();
        if (isset($query)) {
            return keyValueArray($query->getResultArray());
        } else
            return false;
    }

    function get_ItemList($where = array())
    {
        $builder = $this->db->table("ms_item");
        $builder->select("id,item_name as name")->where('status', '1')->orderBy("item_name");
        $query = $builder->get();
        if (isset($query)) {
            return keyValueArray($query->getResultArray());
        } else
            return false;
    }
    
    function get_monthArray() {
        $monthArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthArray[str_pad($i, 2, '0', STR_PAD_LEFT)] = date('F', mktime(0, 0, 0, $i, 1));
        }
        return $monthArray;
    }

}




   
