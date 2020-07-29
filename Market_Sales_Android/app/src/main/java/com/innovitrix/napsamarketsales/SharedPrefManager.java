package com.innovitrix.napsamarketsales;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.widget.TextView;

import com.innovitrix.napsamarketsales.models.User;


import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_CUSTOMER_ID;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_DOB;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_EMAIL;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_FIRSTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_GENDER;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LASTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_NRC;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRADER_ID;


public class SharedPrefManager {

    //This would be the name of our shared preferences
    public static final String SHARED_PREF_NAME_SESSION = "SharedPrefSession";


    //We will this to store the boolean in sharedpreference to track user is loggedin or not
    public static final String LOGGEDIN_SHARED_PREF = "loggedin";

    private static Context mContext;
    private static SharedPrefManager mInstance;

    private SharedPrefManager(Context context) {
        mContext = context;
    }

    public static synchronized SharedPrefManager getInstance(Context context) {
        if (mInstance == null) {
            mInstance = new SharedPrefManager(context);
        }
        return mInstance;
    }


    //this method will store the user data in shared preferences
    public void storeCurrentUser(User user) {
        SharedPreferences sharedPreferences = mContext.getSharedPreferences(SHARED_PREF_NAME_SESSION, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(KEY_TRADER_ID, user.getTrader_id());
        editor.putString(KEY_FIRSTNAME, user.getFirstname());
        editor.putString(KEY_LASTNAME, user.getLastname());
//        editor.putString(KEY_EMAIL, user.getEmail());
        editor.putString(KEY_MOBILE, user.getMobile_number());
//        editor.putString(KEY_DOB, customer.getDate_of_birth());
//        editor.putString(KEY_PASSWORD, customer.getPassword());
        editor.apply();
    }

    //this method will checker whether user is already logged in or not
    public boolean isLoggedIn() {
        SharedPreferences sharedPreferences = mContext.getSharedPreferences(SHARED_PREF_NAME_SESSION, Context.MODE_PRIVATE);
        return sharedPreferences.getInt(KEY_TRADER_ID, 0) != 0;
    }

//    public int getCustomerID(){
//        SharedPreferences sharedPreferences = mContext.getSharedPreferences(SHARED_PREF_NAME_FIREBASE,Context.MODE_PRIVATE);
//        return sharedPreferences.getInt(KEY_CUSTOMER_ID,-1);
//    }

    //this method will give the logged in user
    public User getUser() {

        //int trader_id, String firstname, String lastname, String nrc, String gender, String mobile_number
        SharedPreferences sharedPreferences = mContext.getSharedPreferences(SHARED_PREF_NAME_SESSION, Context.MODE_PRIVATE);
        return new User(
                sharedPreferences.getString(KEY_TRADER_ID, null),
                sharedPreferences.getString(KEY_FIRSTNAME, null),
                sharedPreferences.getString(KEY_LASTNAME, null),
                sharedPreferences.getString(KEY_NRC, null),
                // sharedPreferences.getString(KEY_GENDER, null),
                sharedPreferences.getString(KEY_MOBILE, null)
        );
    }

    //this method will logout the user
    public void logout() {
        SharedPreferences sharedPreferences = mContext.getSharedPreferences(SHARED_PREF_NAME_SESSION, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.clear();
        editor.apply();
//        mContext.startActivity(new Intent(mContext, StartActivity.class));
    }

    public String getTranactionDate() {
        //String dateTime;
        //Date transaction_date= null;;
        Calendar calendar = Calendar.getInstance();
        SimpleDateFormat simpleDateFormat = new SimpleDateFormat("yyyy-MM-dd");
        String transaction_date = simpleDateFormat.format(calendar.getTime());


//        try {
//            transaction_date = simpleDateFormat.parse(dateTime);
//
//        } catch (ParseException e) {
//            e.printStackTrace();
//        }
        return transaction_date;

    }

    public String getTranactionDate2() {
        //String dateTime;
        //Date transaction_date= null;;
        Calendar calendar = Calendar.getInstance();
        SimpleDateFormat simpleDateFormat = new SimpleDateFormat("EEE, d MMM yyyy");//formating according to my need
        String transaction_date = simpleDateFormat.format(calendar.getTime());


//        try {
//            transaction_date = simpleDateFormat.parse(dateTime);
//
//        } catch (ParseException e) {
//            e.printStackTrace();
//        }
        return transaction_date;

    }



    public String ConvertStringToDate(String strDate) {

        String transaction_date = "";

        try {
            SimpleDateFormat format =  new SimpleDateFormat("yyyy-MM-dd");
            SimpleDateFormat simpleDateFormat = new SimpleDateFormat("EEE, d MMM yyyy");
            Date date = format.parse(strDate);
            transaction_date = simpleDateFormat.format(date);
        } catch (ParseException e) {
            e.printStackTrace();
        }

        return transaction_date;

    }

    public String ConvertDateToString(Date dateDate) {

        String transaction_date = "";
        try {
            SimpleDateFormat  simpleDateFormat =  new SimpleDateFormat("yyyy-MM-dd");
            transaction_date = simpleDateFormat.format(dateDate);
        } catch (Exception e) {
            e.printStackTrace();
        }
        return transaction_date;

    }

}