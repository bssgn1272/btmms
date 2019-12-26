package com.innovitrix.napsamarketsales.models;

import io.realm.RealmObject;

public class Route extends RealmObject {


    private int route_id;
    private String name;
    private String origin;
    private String destination;
    private double fare;

    public Route() {

    }

    public Route(int route_id, String name, String origin, String destination, double fare) {
        this.route_id = route_id;
        this.name = name;
        this.origin = origin;
        this.destination = destination;
        this.fare = fare;
    }

    public int getRoute_id() {
        return route_id;
    }

    public void setRoute_id(int route_id) {
        this.route_id = route_id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getOrigin() {
        return origin;
    }

    public void setOrigin(String origin) {
        this.origin = origin;
    }

    public String getDestination() {
        return destination;
    }

    public void setDestination(String destination) {
        this.destination = destination;
    }

    public double getFare() {
        return fare;
    }

    public void setFare(double fare) {
        this.fare = fare;
    }
}
