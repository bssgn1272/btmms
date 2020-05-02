package com.innovitrix.napsamarketsales.models;
public class User {
    private String trader_id;
    private String firstname;
    private String lastname;
    private String nrc;
    private String gender;
    private String mobile_number;
    private double balance;
    private String status;


    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public double getBalance() {
        return balance;
    }

    public void setBalance(double balance) {
        this.balance = balance;
    }

    public User(String trader_id, double balance) {
        this.trader_id = trader_id;
        this.balance = balance;
    }

    public User(String trader_id, String firstname, String lastname, String nrc, String gender, String mobile_number, double balance) {
        this.trader_id = trader_id;
        this.firstname = firstname;
        this.lastname = lastname;
        this.nrc = nrc;
        this.gender = gender;
        this.mobile_number = mobile_number;
        this.balance = balance;
    }

    public User(String trader_id, String firstname, String lastname, String nrc, String gender, String mobile_number, double balance, String status) {
        this.trader_id = trader_id;
        this.firstname = firstname;
        this.lastname = lastname;
        this.nrc = nrc;
        this.gender = gender;
        this.mobile_number = mobile_number;
        this.balance = balance;
        this.status = status;
    }

    public User() {
    }

    public String getTrader_id() {
        return trader_id;
    }

    public void setTrader_id(String trader_id) {
        this.trader_id = trader_id;
    }

    public String getFirstname() {
        return firstname;
    }

    public void setFirstname(String firstname) {
        this.firstname = firstname;
    }

    public String getLastname() {
        return lastname;
    }

    public void setLastname(String lastname) {
        this.lastname = lastname;
    }

    public String getNrc() {
        return nrc;
    }

    public void setNrc(String nrc) {
        this.nrc = nrc;
    }

    public String getGender() {
        return gender;
    }

    public void setGender(String gender) {
        this.gender = gender;
    }

    public String getMobile_number() {
        return mobile_number;
    }

    public void setMobile_number(String mobile_number) {
        this.mobile_number = mobile_number;
    }

    public User(String trader_id, String firstname, String lastname, String nrc, String mobile_number) {
        this.trader_id = trader_id;
        this.firstname = firstname;
        this.lastname = lastname;
        this.nrc = nrc;
       this.mobile_number = mobile_number;
    }
    public User(String trader_id,  String mobile_number) {
        this.trader_id = trader_id;
        this.mobile_number = mobile_number;
    }
}
