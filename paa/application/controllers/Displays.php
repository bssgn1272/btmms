<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Displays extends CI_Controller {
	function __construct() {
        parent::__construct();
        
        if(!$this->aauth->is_loggedin()){
			//redirect('login', 'refresh');
        }
        $this->load->model('Displays_model');
    }

    public function index(){
        redirect("departures", "refresh");
    }

    public function departures(){
        $data['departures'] = $this->Displays_model->get_departures();
        $limit = 10;
        $data['total_pages'] = ceil(count($data['departures'])/$limit);
        /*if (isset($_GET["page"])) {
            $page  = $_GET["page"]; 
            $nextpage = $page + 1;
            $data['departures'] = $this->Displays_model->get_departures(($page * $limit), $limit);
          if($nextpage <= $total_pages){
              header('Refresh: 30; URL='.$_SERVER['SERVER_ADDR'] . $_SERVER['REQUEST_URI'].'?page='.$nextpage);
          }else{
            header('Refresh: 30; URL=departures.php');
          }
        }
        else{ 
            $page = 1;
            $nextpage = $page + 1;
            if($nextpage <= $total_pages){
                header('Refresh: 30; URL='.$_SERVER['PHP_SELF'].'?page='.$nextpage);
            }else{
                header('Refresh: 30; URL=departures.php');
            }
          }*/
        header('Refresh: 30; URL=arrivals');
        $this->load->view('displays/departures', $data);
    }
    function arrivals(){

         $data['departures'] = $this->Displays_model->get_arrivals();
        $limit = 10;
        $data['total_pages'] = ceil(count($data['departures'])/$limit);
         header('Refresh: 30; URL=reservations');
        $this->load->view('displays/arrivals', $data);
    }
    function reservations(){

         $data['departures'] = $this->Displays_model->get_reservations();
        $limit = 10;
        $data['total_pages'] = ceil(count($data['departures'])/$limit);
         header('Refresh: 30; URL=departures');
        $this->load->view('displays/reservations', $data);
    }
}