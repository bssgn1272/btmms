package com.innovitrix.napsamarketsales.utils;

public class UrlEndpoints {

    private static final String URL_PRODUCTION = "https://";
    private static final String URL_TEST = "http://18.188.249.56/MarketSalesAPI";
    private static final String URL_LOCAL = "http://localhost:8081/MarketSalesAPI";

    private static final String URL_LOCAL1 = "+-";



    private static final String API_VERSION = "v1";

    //API SETUP
    private static final String URL = URL_TEST ;


    //ENDPOINTS
    public static final String URL_ENDPOINT_TEST = URL + "/" + API_VERSION + "/test";

    public static final String URL_REGISTER = URL + "/" + API_VERSION + "/register";
    public static final String URL_LOGIN = URL + "/" + API_VERSION + "/login";
    public static final String URL_USERS = URL + "/" + API_VERSION + "/users";

    public static final String URL_PRODUCT_CATEGORIES = URL + "/" + API_VERSION + "/product_categories";
    public static final String URL_PRODUCTS = URL + "/" + API_VERSION + "/products";
    public static final String URL_MEASURES = URL + "/" + API_VERSION + "/measures";
    public static final String URL_MARKETEER_PRODUCTS = URL + "/" + API_VERSION + "/marketeer_products";
    public static final String URL_PAYMENT_METHODS = URL + "/" + API_VERSION + "/payment_methods";
    public static final String URL_TOKEN_PROCUREMENT = URL + "/" + API_VERSION + "/token_procurement";
    public static final String URL_TOKEN_REDEMPTION = URL + "/" + API_VERSION + "/token_redemption";
    public static final String URL_TRANSACTIONS = URL + "/" + API_VERSION + "/transactions";
    public static final String URL_CHECK_BALANCE = URL + "/" + API_VERSION + "/balance";

    public static final String URL_ROUTES = URL + "/" + API_VERSION + "/routes";


    public static final String URL_CHAR_QUESTION = "?";
    public static final String URL_CHAR_AMPERSAND = "&";
    public static final String URL_PARAM_USER_ID = "user_id=";
    public static final String URL_PARAM_MOBILE_NUMBER = "mobile_number=";


    public static final String API_KEY = "";
}
