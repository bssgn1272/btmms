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
        redirect("displays/departures", "refresh");
    }
	
	
	public function departures1(){
		$data['departures'] = $this->Displays_model->get_departures();
        $limit = 10;
        $data['total_pages'] = ceil(count($data['departures'])/$limit);
        $this->load->view('displays/departures', $data);
	}
    public function departures(){
		$sub_routes = $this->Displays_model->get_sub_routes();
		$departures = $this->Displays_model->get_departures();
		
		foreach($departures as $departure){
			$departure->sub_routes = "";
			foreach($sub_routes as $sub_route){
				if($sub_route->ed_bus_route_id == $departure->ed_bus_route_id){
					$departure->sub_routes = $departure->sub_routes . '    |    ' . $sub_route->end_route;
				}
			}
			$departure->sub_routes = $departure->sub_routes . "    |";
		}
		
        $data['departures'] = $departures;
        $limit = 10;
        $data['total_pages'] = ceil(count($data['departures'])/$limit);
        //header('Refresh: 30; URL=departures');
        $this->load->view('displays/departures', $data);
    }

    public function departures_json(){
		$sub_routes = $this->Displays_model->get_sub_routes();
		$departures = $this->Displays_model->get_departures();
		
		foreach($departures as $departure){
			$departure->sub_routes = "";
			foreach($sub_routes as $sub_route){
				if($sub_route->ed_bus_route_id == $departure->ed_bus_route_id){
					$departure->sub_routes = $departure->sub_routes . '    |    ' . $sub_route->end_route;
				}
			}
			$departure->sub_routes = $departure->sub_routes . "    |";
		}
		
        $data['departures'] = $departures;
        $limit = 10;
        $data['total_pages'] = ceil(count($data['departures'])/$limit);

        echo json_encode($data['departures']);
    }

    public function arrivals(){
        $data['arrivals'] = $this->Displays_model->get_arrivals();
        $limit = 10;
        $data['total_pages'] = ceil(count($data['arrivals'])/$limit);
        //header('Refresh: 30; URL=arrivals');
        $this->load->view('displays/arrivals', $data);
    }

    public function arrivals_json(){
        $data['arrivals'] = $this->Displays_model->get_arrivals();
        $limit = 10;
        $data['total_pages'] = ceil(count($data['arrivals'])/$limit);

        echo json_encode($data['arrivals']);
    }
}