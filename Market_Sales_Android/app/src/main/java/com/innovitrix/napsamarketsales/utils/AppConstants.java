package com.innovitrix.napsamarketsales.utils;

import java.util.regex.Pattern;

public class AppConstants {

    //API
    public static final String KEY_USERNAME_API = "";
    public static final String KEY_SERVICE_TOKEN = "";
    public static final int KEY_VOLLEY_SOCKET_TIMEOUT_MS = 10000;
    public static final String KEY_TRANSACTION_CHANNEL = "MOBILE";

    public static final String KEY_CUSTOMER_ID = "customer_id";
    public static final String KEY_TRADER_ID = "trader_id";
    public static final String KEY_USERNAME = "username";
    public static final String KEY_FIRSTNAME = "firstname";
    public static final String KEY_LASTNAME = "lastname";
    public static final String KEY_EMAIL = "email";
    public static final String KEY_MOBILE = "mobile";
    public static final String KEY_EMAIL_OR_PHONE = "emailorPhone";
    public static final String KEY_PASSWORD = "password";
    public static final String KEY_ANDROID_ID = "android_id";
    public static final String KEY_DEVICE_SERIAL = "android_serial";
    public static final String KEY_SERIAL_NUMBER = "serial_number";
    public static final String KEY_DEVICE_NAME = "device_name";
    public static final String KEY_DOB = "date_of_birth";
    public static final String KEY_NRC = "nrc";
    public static final String KEY_GENDER = "gender";
    public static final String KEY_OLD_PASSWORD = "old_password";
    public static final String KEY_NEW_PASSWORD = "new_password";
    public static final String KEY_CONF_PASSWORD = "conf_password";
    public static final String KEY_PIN = "pin";
    public static final String KEY_ROUTE_CODE = "route_code";
    public static final String KEY_ROUTE_NAME= "route_name";
    public static final String KEY_TRAVEL_DATE = "travel_date";
    public static final String KEY_START_ROUTE= "start_route";
    public static final String KEY_END_ROUTE= "end_route";
    public static final String KEY_DEPARTURE_DATE= "departure_date";
    public static final String KEY_DEPARTURE_TIME= "departure_time";
    public static final String KEY_BUS_OPERATOR_NAME= "bus_operator_name";
    public static final String KEY_BUS_LICENSE_PLATE= "bus_license_plate";
    public static final String KEY_BUS_FARE= "bus_fare";
    public static final String KEY_BUS_SCHEDULE_ID= "bus_schedule_id";
    public static final String KEY_AMOUNT = "amount";
    public static final String KEY_MARKET_STAND_NUMBER = "market_stand_number";
    //IDs
    public static final String TAG_VOLLEY_RESPONSE = "VOLLEY_RESPONSE";
    public static final String TAG_VOLLEY_ERROR = "VOLLEY_ERROR";

    public static final String KEY_ACTIVITY = "activity";
    public static final String KEY_ENTITY_NAME = "entity_name";

    public static final String MAPVIEW_BUNDLE_KEY = "MapViewBundleKey";
    public static final int ERROR_DIALOG_REQUEST = 9001;
    public static final int PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION = 9002;
    public static final int PERMISSIONS_REQUEST_ENABLE_GPS = 9003;

    public static final String KEY_ERROR = "error";
    public static final String KEY_MESSAGE = "message";
    public static final String KEY_LOGIN_STATUS = "login_status";
    public static final String KEY_STATUS = "status";

    public static final String CHAR_REQUIRED = "<p style=\"color:red\">*</p>";

    private static final Pattern PASSWORD_PATTERN =
            Pattern.compile("^" +
                    "(?=.*[0-9])" +         //at least 1 digit
                    "(?=.*[a-z])" +         //at least 1 lower case letter
                    "(?=.*[A-Z])" +         //at least 1 upper case letter
                    "(?=.*[a-zA-Z])" +      //any letter
                    "(?=.*[@#$%^&+=])" +    //at least 1 special character
                    "(?=\\S+$)" +           //no white spaces
                    ".{4,}" +               //at least 4 characters
                    "$");
    private static final Pattern PIN_PATTERN =
            Pattern.compile("^" +
                    "(?=.*[0-9])" +         //at least 1 digit
                    "(?=.*[a-z])" +         //at least 1 lower case letter
                    "(?=.*[A-Z])" +         //at least 1 upper case letter
                    "(?=.*[a-zA-Z])" +      //any letter
                    "(?=.*[/])" +    //at least 1 special character
                    "(?=\\S+$)" +           //no white spaces
                    ".{5,}" +               //at least 4 characters
                    "$");
    // public static final String KEY_ROUTE_ID = "route_id";

}
