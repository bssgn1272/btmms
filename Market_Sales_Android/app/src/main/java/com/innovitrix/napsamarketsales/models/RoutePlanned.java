package com.innovitrix.napsamarketsales.models;

public class RoutePlanned {
    private int available_seats;
    private String company;
    private String license_plate;
    private int operator_id;
    private int vehicle_capacity;
    private int bus_schedule_id;
    private String  departure_date;
    private String departure_time;
    private double fare;
    private String route_code;
    private String route_name;


    public RoutePlanned() {
    }

    public RoutePlanned(int available_seats, String company, String license_plate, int operator_id, int vehicle_capacity, int bus_schedule_id, String departure_date, String departure_time, double fare, String route_code, String route_name) {
        this.available_seats = available_seats;
        this.company = company;
        this.license_plate = license_plate;
        this.operator_id = operator_id;
        this.vehicle_capacity = vehicle_capacity;
        this.bus_schedule_id = bus_schedule_id;
        this.departure_date = departure_date;
        this.departure_time = departure_time;
        this.fare = fare;
        this.route_code = route_code;
        this.route_name = route_name;
    }

    public int getAvailable_seats() {
        return available_seats;
    }

    public void setAvailable_seats(int available_seats) {
        this.available_seats = available_seats;
    }

    public String getCompany() {
        return company;
    }

    public void setCompany(String company) {
        this.company = company;
    }

    public String getLicense_plate() {
        return license_plate;
    }

    public void setLicense_plate(String license_plate) {
        this.license_plate = license_plate;
    }

    public int getOperator_id() {
        return operator_id;
    }

    public void setOperator_id(int operator_id) {
        this.operator_id = operator_id;
    }

    public int getVehicle_capacity() {
        return vehicle_capacity;
    }

    public void setVehicle_capacity(int vehicle_capacity) {
        this.vehicle_capacity = vehicle_capacity;
    }

    public int getBus_schedule_id() {
        return bus_schedule_id;
    }

    public void setBus_schedule_id(int bus_schedule_id) {
        this.bus_schedule_id = bus_schedule_id;
    }

    public String getDeparture_date() {
        return departure_date;
    }

    public void setDeparture_date(String departure_date) {
        this.departure_date = departure_date;
    }

    public String getDeparture_time() {
        return departure_time;
    }

    public void setDeparture_time(String departure_time) {
        this.departure_time = departure_time;
    }

    public double getFare() {
        return fare;
    }

    public void setFare(double fare) {
        this.fare = fare;
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
}

