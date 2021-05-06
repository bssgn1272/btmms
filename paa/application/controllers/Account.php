<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {
	function __construct() {
		parent::__construct();
    }

    public function login(){
        $this->load->view('account/login');
    }

    public function signin(){
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() == FALSE){
                redirect('login', 'refresh');
        }
        else{
            $remember = FALSE;
            extract($_POST);
            $this->aauth->login($email, $password, $remember);
            redirect("app");
        }
    }

    public function logout(){
        $this->aauth->logout();
        redirect("app");
    }
}