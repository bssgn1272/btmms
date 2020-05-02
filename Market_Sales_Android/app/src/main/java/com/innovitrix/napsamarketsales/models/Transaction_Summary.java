package com.innovitrix.napsamarketsales.models;

public class Transaction_Summary {
    public String getSeller_id() {
        return seller_id;
    }

    public void setSeller_id(String seller_id) {
        this.seller_id = seller_id;
    }

    public String getSeller_firstname() {
        return seller_firstname;
    }

    public void setSeller_firstname(String seller_firstname) {
        this.seller_firstname = seller_firstname;
    }

    public String getSeller_lastname() {
        return seller_lastname;
    }

    public void setSeller_lastname(String seller_lastname) {
        this.seller_lastname = seller_lastname;
    }

    public String getSeller_mobile_number() {
        return seller_mobile_number;
    }

    public void setSeller_mobile_number(String seller_mobile_number) {
        this.seller_mobile_number = seller_mobile_number;
    }

    public int getToday_num_of_sales() {
        return today_num_of_sales;
    }

    public void setToday_num_of_sales(int today_num_of_sales) {
        this.today_num_of_sales = today_num_of_sales;
    }

    public String getToday_revenue() {
        return today_revenue;
    }

    public void setToday_revenue(String today_revenue) {
        this.today_revenue = today_revenue;
    }

    public int getWeek_num_of_sales() {
        return week_num_of_sales;
    }

    public void setWeek_num_of_sales(int week_num_of_sales) {
        this.week_num_of_sales = week_num_of_sales;
    }

    public String getWeek_revenue() {
        return week_revenue;
    }

    public void setWeek_revenue(String week_revenue) {
        this.week_revenue = week_revenue;
    }

    public int getMonth_num_of_sales() {
        return month_num_of_sales;
    }

    public void setMonth_num_of_sales(int month_num_of_sales) {
        this.month_num_of_sales = month_num_of_sales;
    }

    public String getMonth_revenue() {
        return month_revenue;
    }

    public void setMonth_revenue(String month_revenue) {
        this.month_revenue = month_revenue;
    }

    private String seller_id; //": "2020-3676257032-2-8166-20",
    private String seller_firstname; //": "Gladys",
    private String seller_lastname; //: "Chibwe",
    private String seller_mobile_number; //": "260964692323",

    private int today_num_of_sales; //": 7,
    private String today_revenue;// ": "ZMW 21"

    private int week_num_of_sales; //": 7,
    private String week_revenue;// ": "ZMW 21"

    private int month_num_of_sales; //": 7,
    private String month_revenue;// ": "ZMW 21"

    public Transaction_Summary(String seller_id, String seller_firstname, String seller_lastname, String seller_mobile_number, int today_num_of_sales, String today_revenue, int week_num_of_sales, String week_revenue, int month_num_of_sales, String month_revenue) {
        this.seller_id = seller_id;
        this.seller_firstname = seller_firstname;
        this.seller_lastname = seller_lastname;
        this.seller_mobile_number = seller_mobile_number;
        this.today_num_of_sales = today_num_of_sales;
        this.today_revenue = today_revenue;
        this.week_num_of_sales = week_num_of_sales;
        this.week_revenue = week_revenue;
        this.month_num_of_sales = month_num_of_sales;
        this.month_revenue = month_revenue;
    }
}
