package com.innovitrix.napsamarketsales;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;

import com.innovitrix.napsamarketsales.models.User;


import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_CUSTOMER_ID;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_DOB;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_EMAIL;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_FIRSTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_GENDER;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LASTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_NRC;




public class SharedPrefManager {

    //This would be the name of our shared preferences
    public static final String SHARED_PREF_NAME_SESSION = "SharedPrefSession";


    //We will this to store the boolean in sharedpreference to track user is loggedin or not
    public static final String LOGGEDIN_SHARED_PREF = "loggedin";

    private static Context mContext;
    private static SharedPrefManager mInstance;

    private SharedPrefManager(Context context){
        mContext = context;
    }

    public static synchronized SharedPrefManager getInstance(Context context){
        if (mInstance == null){
            mInstance = new SharedPrefManager(context);
        }
        return mInstance;
    }




    //this method will store the user data in shared preferences
    public void storeCurrentUser(User user) {
        SharedPreferences sharedPreferences = mContext.getSharedPreferences(SHARED_PREF_NAME_SESSION, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putInt(KEY_CUSTOMER_ID, user.getTrader_id());
//        editor.putString(KEY_FIRSTNAME, customer.getFirst_name());
//        editor.putString(KEY_LASTNAME, customer.getLast_name());
//        editor.putString(KEY_EMAIL, customer.getEmail());
          editor.putString(KEY_MOBILE, user.getMobile_number());
//        editor.putString(KEY_DOB, customer.getDate_of_birth());
//        editor.putString(KEY_PASSWORD, customer.getPassword());
        editor.apply();
    }

    //this method will checker whether user is already logged in or not
    public boolean isLoggedIn() {
        SharedPreferences sharedPreferences = mContext.getSharedPreferences(SHARED_PREF_NAME_SESSION, Context.MODE_PRIVATE);
        return sharedPreferences.getInt(KEY_CUSTOMER_ID, 0) != 0;
    }

//    public int getCustomerID(){
//        SharedPreferences sharedPreferences = mContext.getSharedPreferences(SHARED_PREF_NAME_FIREBASE,Context.MODE_PRIVATE);
//        return sharedPreferences.getInt(KEY_CUSTOMER_ID,-1);
//    }

    //this method will give the logged in user
    public User getCustomer() {

        //int trader_id, String firstname, String lastname, String nrc, String gender, String mobile_number
        SharedPreferences sharedPreferences = mContext.getSharedPreferences(SHARED_PREF_NAME_SESSION, Context.MODE_PRIVATE);
        return new User(
                sharedPreferences.getInt(KEY_CUSTOMER_ID, -1),
                sharedPreferences.getString(KEY_FIRSTNAME, null),
                sharedPreferences.getString(KEY_LASTNAME, null),
                sharedPreferences.getString(KEY_NRC, null),
                sharedPreferences.getString(KEY_GENDER, null),
                sharedPreferences.getString(KEY_MOBILE, null)
        );
    }

    //this method will logout the user
    public void logout() {
        SharedPreferences sharedPreferences = mContext.getSharedPreferences(SHARED_PREF_NAME_SESSION, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.clear();
        editor.apply();
        mContext.startActivity(new Intent(mContext, LoginActivity.class));
    }

}