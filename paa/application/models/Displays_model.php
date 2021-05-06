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
                                            c.route_name route_destination,
                                            b.company company_name,
                                            e.gate bay_name,
											a.ed_bus_route_id,
                                            CASE WHEN a.`reservation_status` = 'A' THEN 'Scheduled'
                                            WHEN a.`reservation_status` = 'C' THEN 'Cancelled' 
                                            WHEN a.`reservation_status` = 'B' THEN 'Boarding'
                                            WHEN a.`reservation_status` = 'D' THEN 'Departed'
											WHEN a.`reservation_status` = 'DL' THEN 'Delayed'
                                            WHEN a.`reservation_status` = 'PC' THEN 'Pending Cancellation' END status_message,
                                            a.time,
                                            CASE WHEN (d.vehicle_capacity - d.tickets_sold) IS NULL THEN d.vehicle_capacity
                                            ELSE (d.vehicle_capacity - d.tickets_sold) END seats_available
                                        FROM ed_reservations a
                                        JOIN probase_tbl_bus b ON a.bus_id = b.id
                                        JOIN ed_bus_routes c ON a.ed_bus_route_id = c.id
                                        JOIN ed_slot_mappings e ON a.slot = e.slot
                                        LEFT JOIN ed_vw_sold_tickets d ON a.id = d.bus_schedule_id
                                        WHERE DATE(a.reserved_time) = DATE(NOW())
                                        ORDER BY a.time");
        }
        else{
            $query = $this->db->query("SELECT b.license_plate bus_number,
                                            c.route_name route_destination,
                                            b.company company_name,
                                            e.gate bay_name,
											a.ed_bus_route_id,
                                            CASE WHEN a.`status` = 'A' OR a.`status` = 'p' THEN 'Scheduled'
                                            WHEN a.`status` = 'C' THEN 'Cancelled' 
											WHEN a.`status` = 'D' THEN 'Departed'
											WHEN a.`status` = 'DL' THEN 'Delayed'
                                            WHEN a.`status` = 'PC' THEN 'Pending Cancellation' END status_message,
                                            a.time,
                                            CASE WHEN (d.vehicle_capacity - d.tickets_sold) IS NULL THEN d.vehicle_capacity
                                            ELSE (d.vehicle_capacity - d.tickets_sold) END seats_available
                                        FROM ed_reservations a
                                        JOIN probase_tbl_bus b ON a.bus_id = b.id
                                        JOIN ed_bus_routes c ON a.ed_bus_route_id = c.id
                                        JOIN ed_slot_mappings e ON a.slot = e.slot
                                        LEFT JOIN ed_vw_sold_tickets d ON a.id = d.bus_schedule_id
                                        WHERE DATE(a.reserved_time) = DATE(NOW())
                                        ORDER BY a.time
                                        LIMIT $start, $limit");
        }
        return $query->result();
    }

    public function get_arrivals($start = FALSE, $limit = FALSE)
    {
        $query = NULL;
        if(!$start){
            $query = $this->db->query("SELECT *
										FROM
										(
										SELECT a.bus_detail bus_number,
										 b.end_route route_destination,
										 d.username company_name,
										 c.gate bay_name, CASE WHEN a.`reservation_status` = 'A' THEN 'Scheduled' WHEN a.`reservation_status` = 'C' THEN 'Cancelled' WHEN a.`status` = 'B' THEN 'Offloading' WHEN a.`status` = 'D' THEN 'Departed' WHEN a.`status` = 'DL' THEN 'Delayed' WHEN a.`status` = 'PC' THEN 'Pending Cancellation' END status_message,
										 a.time
										FROM ed_ar_reservations a
										JOIN ed_bus_routes b ON a.ed_bus_route_id = b.id
										JOIN ed_slot_mappings c ON a.slot = c.slot
										JOIN probase_tbl_users d ON a.user_id = d.id
										WHERE DATE(a.reserved_time) = DATE(NOW()) UNION ALL
										SELECT b.license_plate bus_number,
										 c.end_route route_destination,
										 b.company company_name,
										 e.gate bay_name, CASE WHEN a.`reservation_status` = 'A' THEN 'Scheduled' WHEN a.`status` = 'C' THEN 'Cancelled' WHEN a.`status` = 'D' THEN 'Departed' WHEN a.`status` = 'DL' THEN 'Delayed' WHEN a.`status` = 'PC' THEN 'Pending Cancellation' END status_message,
										 a.time
										FROM ed_ar_reservations a
										JOIN probase_tbl_bus b ON a.bus_id = b.id
										JOIN ed_bus_routes c ON a.ed_bus_route_id = c.id
										JOIN ed_slot_mappings e ON a.slot = e.slot
										WHERE DATE(a.reserved_time) = DATE(NOW())) a
										ORDER BY a.time");
        }
        else{
            $query = $this->db->query("SELECT b.license_plate bus_number,
                                            c.end_route route_destination,
                                            b.company company_name,
                                            e.gate bay_name,
                                            CASE WHEN a.`status` = 'A' THEN 'Scheduled'
                                            WHEN a.`status` = 'C' THEN 'Cancelled' 
											WHEN a.`status` = 'D' THEN 'Departed'
											WHEN a.`status` = 'DL' THEN 'Delayed'
                                            WHEN a.`status` = 'PC' THEN 'Pending Cancellation' END status_message,
                                            a.time
                                        FROM ed_ar_reservations a
                                        JOIN probase_tbl_bus b ON a.bus_id = b.id
                                        JOIN probase_tbl_travel_routes c ON a.route = c.id
                                        JOIN ed_slot_mappings e ON a.slot = e.slot
                                        WHERE DATE(a.reserved_time) = DATE(NOW())
                                        ORDER BY a.time
                                        LIMIT $start, $limit");
        }
        return $query->result();
    }
	
	public function get_sub_routes($start = FALSE, $limit = FALSE)
    {
		$query = $this->db->query("SELECT * FROM ed_sub_routes ORDER BY ed_bus_route_id, `order`;");
        
        return $query->result();
    }

    
}