<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller{

	public function __construct(){
        parent::__construct();
        if(!$this->aauth->is_loggedin()){
			redirect('login', 'refresh');
		}
		$this->load->model('Data_model');
    }

	public function index(){
		$this->load->view('home');
	}

	public function single(){
		$this->form_validation->set_rules('phonenumber', 'Phone Number', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

		if($this->form_validation->run() == TRUE){
			extract($_POST);
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

			if($httpcode == 202){
				$this->session->set_flashdata('singlesuccess', 'SMS accepted for delivery.');
			}
			else if($httpcode == 422){
				$this->session->set_flashdata('singleerror', "Invalid receipient's number.");
			}
			else{
				//$httpcode = 500;
				$this->session->set_flashdata('singleerror', "General failure, please try again later. (If this persists contact your system administrator.)");
			}
			$user_id = $this->aauth->get_user_id();
			$this->Data_model->insert_sms_task($phonenumber, $message, $httpcode, 'SINGLE', 'SINGLE', $user_id);
		}
		else{
			$this->session->set_flashdata('singleerror', validation_errors());
		}

		redirect('/home', 'refresh');
	}

	public  function csvfile(){
		//$this->form_validation->set_rules('csvfile', 'CSV File', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

		if($this->form_validation->run() == TRUE){
			extract($_POST);
			$config['upload_path'] = FCPATH . 'csv/';
			$config['allowed_types'] = 'csv';
			$config['file_ext_tolower'] = TRUE;
			$config['file_name'] = md5(microtime(true)) . '.csv';

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('csvfile')){
				$this->session->set_flashdata('csverror', $this->upload->display_errors());
			}
			else{
				$data = $this->upload->data();
				$user_id = $this->aauth->get_user_id();

				$param = $config['file_name'];
				$message = base64_encode($message);
				$message = $this->base64_to_base16($message);
				$command = "php ".FCPATH . "index.php tools processcsv $param $message $user_id";
				$out = [];
				exec($command, $out);

				$this->session->set_flashdata('csvsuccess', 'File submitted for processing.');
				//$this->session->set_flashdata('csvsuccess', $command);
			}
		}
		else{
			$this->session->set_flashdata('csverror', validation_errors());
		}
		redirect('/home', 'refresh');
	}

	public  function group(){
		//$this->form_validation->set_rules('groups', 'Groups', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

		if($this->form_validation->run() == TRUE){
			extract($_POST);

			$user_id = $this->aauth->get_user_id();

			$tmp = '';
			foreach($groups as $group){
				$tmp .= $group . ',';
			}

			$param = $tmp;
			$param = base64_encode($param);
			$param = $this->base64_to_base16($param);
			$message = base64_encode($message);
			$message = $this->base64_to_base16($message);
			$command = "php ".FCPATH . "index.php tools processgroup $param $message $user_id";
			$out = [];
			exec($command, $out);

			$this->session->set_flashdata('groupsuccess', 'SMS submitted for delivery.');
			//$this->session->set_flashdata('groupsuccess', $command);
		}
		else{
			$this->session->set_flashdata('grouperror', validation_errors());
		}
		redirect('/home', 'refresh');
	}

	public function base64_to_base16($base64) {
		return implode('', unpack('H*', base64_decode($base64)));
	}
}
