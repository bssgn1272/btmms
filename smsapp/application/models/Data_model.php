<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Data_model extends CI_Model {
    function __construct(){
        parent::__construct();
    }

    public function insert_sms_task($msisdn, $msg, $status_code, $task_type, $extra, $user_id, $job_id = NULL){
        $msg = $this->db->escape($msg);
        $query = $this->db->query("INSERT INTO smstasks (msisdn, msg, status_code, task_type, user_id, extra, job_id) 
                                    VALUES ('$msisdn', $msg, '$status_code', '$task_type', '$user_id', '$extra', '$job_id')");
    }

    public function insert_sms_batch_job($name, $job_type, $extra, $user_id){
        /*$query = $this->db->query("INSERT INTO smsbatchjobs (name, job_type, user_id, extra) 
                                    VALUES ('$name', '$job_type', '$user_id', '$extra')");*/

        $input = ['name'=>$name, 'job_type'=>$job_type, 'user_id'=>$user_id, 'extra'=>$extra];
        $this->db->insert('smsbatchjobs', $input);
        return $this->db->insert_id();
    }

    public function update_sms_batch_job($job_id, $processed_numbers, $ignored_numbers, $runtime){
        /*$query = $this->db->query("INSERT INTO smsbatchjobs (name, job_type, user_id, extra) 
                                    VALUES ('$name', '$job_type', '$user_id', '$extra')");*/

        $input = ['processed_numbers'=>$processed_numbers, 'ignored_numbers'=>$ignored_numbers, 'runtime'=>$runtime];
        $this->db->set($input);
        $this->db->where('id', $job_id);
        $this->db->update('smsbatchjobs', $input);
    }

    public function get_phonenumbers($group){
        if($group == 'TRAVELER' || $group == 'TRAVELER,'){
            $query = $this->db->query("SELECT DISTINCT mobile_number mobile FROM btmms.probase_tbl_tickets");
        }
        else{
            $query = $this->db->query("SELECT mobile FROM btmms.probase_tbl_users WHERE account_status IN ('ACTIVE', 'OTP')
                                    AND operator_role = '$group'");
        }
        return $query->result();
    }
}