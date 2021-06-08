<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        include APPPATH . 'third_party/base32/base32.php';
		$this->load->model('Data_model');
    }

    public function processcsv($file, $message, $user_id)
    {
        $time_start = microtime(true);
        $message = $this->base16_to_base64($message);
        $message = base64_decode($message);
        $file = FCPATH . '\csv\\' . $file;
        $handle = fopen($file, "r");
        $i = 0;
        $j = 0;

        $job_id = $this->Data_model->insert_sms_batch_job($file, 'CSVFILE', 'CSVFILE', $user_id);
        while (($data = fgetcsv($handle)) !== FALSE){
            $phonenumber = preg_replace("/[^0-9]/", "", $data[0] );

            if($this->validate($phonenumber)){
                $httpcode = $this->send($phonenumber, $message);
                $this->Data_model->insert_sms_task($phonenumber, $message, $httpcode, 'CSVFILE', 'CSVFILE', $user_id, $job_id);
                $i++;
            }
            else{
                $j++;
            }
        }
        
        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $this->Data_model->update_sms_batch_job($job_id, $i, $j, $time);

        $iters = number_format($i);
        echo "\nProcesscsv sent $iters messages in $time seconds\n";
    }

    public function processgroup($groups, $message, $user_id)
    {
        $time_start = microtime(true);
        $message = $this->base16_to_base64($message);
        $message = base64_decode($message);
        $groups = $this->base16_to_base64($groups);
        $groups = base64_decode($groups);
        $groups_arr = explode(',', $groups);
        $i = 0;
        $j = 0;

        $job_id = $this->Data_model->insert_sms_batch_job(MD5($time_start), 'GROUP', $groups, $user_id);
        foreach($groups_arr as $group){
            $phonenumbers = $this->Data_model->get_phonenumbers($group);

            foreach($phonenumbers as $phonenumber){
                $phonenumber = preg_replace("/[^0-9]/", "", $phonenumber->mobile);
                if(strlen($phonenumber) == 10 && strpos($phonenumber, '0') === 0){
                    $phonenumber = '26' . $phonenumber;
                }

                if($this->validate($phonenumber)){
                    echo $group . ': ' . $phonenumber . "\n";
                    $httpcode = $this->send($phonenumber, $message);
                    $this->Data_model->insert_sms_task($phonenumber, $message, $httpcode, 'GROUP', $groups, $user_id, $job_id);
                    $i++;
                }
                else{
                    $j++;
                }
            }
        }
        
        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $this->Data_model->update_sms_batch_job($job_id, $i, $j, $time);

        $iters = number_format($i);
        echo "\nProcessgroup sent $iters messages in $time seconds\n";
    }

    public function validate($phonenumber){
        if($phonenumber >= 260750000000 && $phonenumber <= 260779999999){
            return TRUE;
        }

        if($phonenumber >= 260950000000 && $phonenumber <= 260979999999){
            return TRUE;
        }

        return FALSE;
    }

    public function send($phonenumber, $message){
        $msg = urlencode($message);
        $url = "https://apps.zamtel.co.zm/bsms/api/v2.1/action/send/api_key/1a181f928b9a5bb39fe816853db46202/contacts/[$phonenumber]/senderId/LMBMC/message/$msg";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $contents = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpcode;
    }

    public function base16_to_base64($base16) {
        return base64_encode(pack('H*', $base16));
    }
}
