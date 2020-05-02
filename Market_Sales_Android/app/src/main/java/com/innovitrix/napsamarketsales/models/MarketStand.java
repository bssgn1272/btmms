package com.innovitrix.napsamarketsales.models;

public class MarketStand {
    private String stand_number;

    public String getStand_number() {
        return stand_number;
    }

    public void setStand_number(String stand_number) {
        this.stand_number = stand_number;
    }

    public double getStand_price() {
        return stand_price;
    }

    public void setStand_price(double stand_price) {
        this.stand_price = stand_price;
    }

    public MarketStand(String stand_number, double stand_price) {
        this.stand_number = stand_number;
        this.stand_price = stand_price;
    }

    private double stand_price;
}
