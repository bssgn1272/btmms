package com.innovitrix.napsamarketsales.utils;

public class UrlEndpoints {

    //private static final String URL_PRODUCTION = "http://10.10.1.57:4000";
    //private static final String URL_PRODUCTION = "http://10.70.3.55:4000";
    //private static final String URL_PRODUCTION = "http://728c5b51.ngrok.io";
 //private static final String URL_PRODUCTION = "http://192.168.8.101:4000";
  //  private static final String URL_PRODUCTION = "http://192.168.8.253:4000";
    //private static final String URL_TEST = "http://18.188.249.56/MarketSalesAPI";

 //PUBLIC
   private static final String URL_PRODUCTION = "http://165.56.2.3:4000"; // TODO this the public IP
   private static final String URL_TEST = "http://165.56.2.3/MarketSalesAPI";
//LOCAL
 //private static final String URL_PRODUCTION = "http://10.70.3.55:4000"; // TODO this the public IP
 //private static final String URL_TEST = "http://10.70.3.55/MarketSalesAPI";


    private static final String URL_LOCAL = "http://localhost:8081/MarketSalesAPI";

    private static final String URL_LOCAL1 = "+-";

    private static final String API_VERSION = "v1";

    //API SETUP
    private static final String URL = URL_TEST ;
    private static final String URL1=  URL_PRODUCTION ;
 //  private static final String URL1 = URL_TEST ;

    //ENDPOINTS
    public static final String URL_ENDPOINT_TEST = URL + "/" + API_VERSION + "/test";

    public static final String URL_REGISTER = URL + "/" + API_VERSION + "/register";

    public static final String URL_AUTHENTICATE_MARKETER = URL1 + "/api/v1/btms/market/secured/authenticate_marketer";
    public static final String URL_MARKETER_KYC = URL1 + "/api/v1/btms/market/secured/marketer_kyc";
    public static final String URL_ROUTES = URL1 +  "/api/v1/btms/travel/secured/routes";
    public static final String URL_DESTINATION = URL1 +  "/api/v1/btms/travel/secured/internal/locations/destinations";
    public static final String URL_UPDATE_PIN = URL1 + "/api/v1/btms/market/secured/update_pin";
    public static final String URL_RESET_PIN = URL1 +"/api/v1/btms/market/secured/reset_pin";
    public static final String URL_MARKETEER_KYC_ALL = URL1 + "/api/v1/btms/market/secured/all_marketer_kyc";


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
    public static final String URL_MARKETER_KYC_SINGLE = URL  + "/" + API_VERSION + "/marketeer_kyc_single";
    public static final String URL_MARKET_FEE = URL  + "/" + API_VERSION + "/market_fee";

    public static final String URL_SUMMARY_TRANSACTIONS = URL + "/" + API_VERSION +"/summary_transactions";
    //public static final String URL_ROUTES = URL + "/" + API_VERSION + "/routes";


    public static final String URL_CHAR_QUESTION = "?";
    public static final String URL_CHAR_AMPERSAND = "&";
    public static final String URL_PARAM_USER_ID = "user_id=";
    public static final String URL_PARAM_MOBILE_NUMBER = "mobile_number=";
    public static final String URL_PARAM_SELLER_MOBILE_NUMBER = "seller_mobile_number=";
    public static final String URL_PARAM_MOBILE_NO = "mobile=";
    public static final String URL_PARAM_PIN = "pin=";
    public static final String URL_PARAM_ROUTE_ID = "route_id=";
    public static final String URL_PARAM_PERIOD = "period=";

    public static final String API_KEY = "";
}
