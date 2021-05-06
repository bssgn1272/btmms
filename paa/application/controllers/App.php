<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		if(!$this->aauth->is_loggedin()){
			//redirect('login', 'refresh');
		}
	}
	
	public function index(){
		/* $this->load->view('header');
		$this->load->view('index');
		$this->load->view('footer'); */
		redirect("displays/departures", "refresh");
	}

	public function hello(){
		//$this->aauth->update_user(1, $email = FALSE, $pass = 'password', $username = FALSE);
		echo 'Hello World!';
	}
}
