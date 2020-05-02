package com.innovitrix.napsamarketsales.models;

public class Route {

    private int route_id;
    private String route_code;
    private String route_name;
    private String source_state;
    private String start_route;
    private String end_route;

    public Route() {

    }
    public Route(int route_id, String route_code, String route_name, String source_state, String start_route, String end_route) {
        this.route_id = route_id;
        this.route_code = route_code;
        this.route_name = route_name;
        this.source_state = source_state;
        this.start_route = start_route;
        this.end_route = end_route;
    }
    public Route( String end_route) {

        this.end_route = end_route;
    }
    public int getRoute_id() {
        return route_id;
    }

    public void setRoute_id(int route_id) {
        this.route_id = route_id;
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

    public String getSource_state() {
        return source_state;
    }

    public void setSource_state(String source_state) {
        this.source_state = source_state;
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
