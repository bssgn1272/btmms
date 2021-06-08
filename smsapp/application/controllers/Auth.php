<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller{

	public function index(){
		$this->load->view('auth/login');
	}

    public function login(){
        //$this->aauth->update_user(1, FALSE, "password", $username = FALSE);
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');

        if($this->form_validation->run() == TRUE){
            extract($_POST);
            if($this->aauth->login($email, $password)){
                redirect('/', 'refresh');
            }
            else{
                $this->session->set_flashdata('error', 'Email & Password Donot Match');
                redirect('auth', 'refresh');
            }
        }
        else{
            $this->session->set_flashdata('error', validation_errors());
            redirect('auth', 'refresh');
        }
	}

    public function logout(){
        $this->aauth->logout();
        redirect('/', 'refresh');
    }
}
