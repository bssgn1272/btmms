package com.innovitrix.napsamarketsales.models;

public class RoutePlanned {
    private String company;
    private String bus_license_plate;
    private int operator_id;
    private double bus_fare;
    private String route_code;
    private String route_name;
    private String start_route;
    private String end_route;

    public RoutePlanned() {
    }
    public RoutePlanned(String company, String bus_license_plate, int operator_id, double bus_fare, String route_code, String route_name, String start_route, String end_route) {
        this.company = company;
        this.bus_license_plate = bus_license_plate;
        this.operator_id = operator_id;
        this.bus_fare = bus_fare;
        this.route_code = route_code;
        this.route_name = route_name;
        this.start_route = start_route;
        this.end_route = end_route;
    }

    public String getCompany() {
        return company;
    }

    public void setCompany(String company) {
        this.company = company;
    }

    public String getBus_license_plate() {
        return bus_license_plate;
    }

    public void setBus_license_plate(String bus_license_plate) {
        this.bus_license_plate = bus_license_plate;
    }

    public int getOperator_id() {
        return operator_id;
    }

    public void setOperator_id(int operator_id) {
        this.operator_id = operator_id;
    }

    public double getBus_fare() {
        return bus_fare;
    }

    public void setBus_fare(double bus_fare) {
        this.bus_fare = bus_fare;
    }

    public String getRoute_code() {
        return route_code;
    }

    public void setRoute_code(String route_code) {
        this.route_code = route_code;
    }

    public String getRoute_name() {
        return route_name;
    }

    public void setRoute_name(String route_name) {
        this.route_name = route_name;
    }

    public String getStart_route() {
        return start_route;
    }

    public void setStart_route(String start_route) {
        this.start_route = start_route;
    }

    public String getEnd_route() {
        return end_route;
    }

    public void setEnd_route(String end_route) {
        this.end_route = end_route;
    }
}

