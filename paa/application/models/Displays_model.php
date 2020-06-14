<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Displays_model extends CI_Model {
    function __construct(){
        parent::__construct();
    }
    public function get_departures($start = FALSE, $limit = FALSE)
    {
        $query = NULL;
        if(!$start){
            $query = $this->db->query("SELECT b.license_plate bus_number,
                                            c.end_route route_destination,
                                            b.company company_name,
                                            a.slot bay_name,
                                            CASE WHEN a.`status` = 'A' THEN 'Scheduled'
                                            WHEN a.`status` = 'C' THEN 'Cancelled' 
                                            WHEN a.`status` = 'B' THEN 'Boarding'
                                            WHEN a.`status` = 'D' THEN 'Departed'
                                            WHEN a.`status` = 'PC' THEN 'Pending Cancellation' END status_message,
                                            a.time
                                        FROM ed_reservations a
                                        JOIN probase_tbl_bus b ON a.bus_id = b.id
                                        JOIN probase_tbl_travel_routes c ON a.route = c.id
                                        /*WHERE DATE(a.reserved_time) >= DATE(NOW())*/
                                        ORDER BY a.time");
        }
        else{
            $query = $this->db->query("SELECT b.license_plate bus_number,
                                            c.end_route route_destination,
                                            b.company company_name,
                                            a.slot bay_name,
                                            CASE WHEN a.`status` = 'A' THEN 'Approved'
                                            WHEN a.`status` = 'C' THEN 'Cancelled' 
                                            WHEN a.`status` = 'PC' THEN 'Pending Cancellation' END status_message,
                                            a.time
                                        FROM ed_reservations a
                                        JOIN probase_tbl_bus b ON a.bus_id = b.id
                                        JOIN probase_tbl_travel_routes c ON a.route = c.id
                                        /*WHERE DATE(a.reserved_time) >= DATE(NOW())*/
                                        ORDER BY a.time
                                        LIMIT $start, $limit");
        }
        return $query->result();
    }

    
     public function get_arrivals($start = FALSE, $limit = FALSE)
    {
        $query = NULL;
        if(!$start){




$query = $this->db->query("SELECT b.license_plate bus_number,
                                            c.end_route route_destination,
                                            b.company company_name,
                                            a.slot bay_name,
                                            CASE WHEN a.`status` = 'A' THEN 'Scheduled'
                                            WHEN a.`status` = 'C' THEN 'Cancelled' 
                                            WHEN a.`status` = 'B' THEN 'Boarding'
                                            WHEN a.`status` = 'D' THEN 'Departed'
                                            WHEN a.`status` = 'PC' THEN 'Pending Cancellation' END status_message,
                                            a.time
                                        FROM ed_reservations a
                                        JOIN probase_tbl_bus b ON a.bus_id = b.id
                                        JOIN probase_tbl_travel_routes c ON a.route = c.id
                                        /*WHERE DATE(a.reserved_time) >= DATE(NOW())*/
                                        ORDER BY a.time");
        }
        else{
            $query = $this->db->query("SELECT b.license_plate bus_number,
                                            c.end_route route_destination,
                                            b.company company_name,
                                            a.slot bay_name,
                                            CASE WHEN a.`status` = 'A' THEN 'Approved'
                                            WHEN a.`status` = 'C' THEN 'Cancelled' 
                                            WHEN a.`status` = 'PC' THEN 'Pending Cancellation' END status_message,
                                            a.time
                                        FROM ed_reservations a
                                        JOIN probase_tbl_bus b ON a.bus_id = b.id
                                        JOIN probase_tbl_travel_routes c ON a.route = c.id
                                        /*WHERE DATE(a.reserved_time) >= DATE(NOW())*/
                                        ORDER BY a.time
                                        LIMIT $start, $limit");
        }
        return $query->result();
    }

 public function get_reservations($start = FALSE, $limit = FALSE)
    {
        $query = NULL;
        if(!$start){
            $query = $this->db->query("SELECT b.license_plate bus_number,
                                            c.end_route route_destination,
                                            b.company company_name,
                                            a.slot bay_name,
                                            CASE WHEN a.`status` = 'A' THEN 'Scheduled'
                                            WHEN a.`status` = 'C' THEN 'Cancelled' 
                                            WHEN a.`status` = 'B' THEN 'Boarding'
                                            WHEN a.`status` = 'D' THEN 'Departed'
                                            WHEN a.`status` = 'PC' THEN 'Pending Cancellation' END status_message,
                                            a.time
                                        FROM ed_reservations a
                                        JOIN probase_tbl_bus b ON a.bus_id = b.id
                                        JOIN probase_tbl_travel_routes c ON a.route = c.id
                                        /*WHERE DATE(a.reserved_time) >= DATE(NOW())*/
                                        ORDER BY a.time");
        }
        else{
            $query = $this->db->query("SELECT b.license_plate bus_number,
                                            c.end_route route_destination,
                                            b.company company_name,
                                            a.slot bay_name,
                                            CASE WHEN a.`status` = 'A' THEN 'Approved'
                                            WHEN a.`status` = 'C' THEN 'Cancelled' 
                                            WHEN a.`status` = 'PC' THEN 'Pending Cancellation' END status_message,
                                            a.time
                                        FROM ed_reservations a
                                        JOIN probase_tbl_bus b ON a.bus_id = b.id
                                        JOIN probase_tbl_travel_routes c ON a.route = c.id
                                        /*WHERE DATE(a.reserved_time) >= DATE(NOW())*/
                                        ORDER BY a.time
                                        LIMIT $start, $limit");
        }
        return $query->result();
    }

}