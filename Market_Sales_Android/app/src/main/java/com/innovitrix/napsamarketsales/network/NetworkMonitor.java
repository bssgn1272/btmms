package com.innovitrix.napsamarketsales.network;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;

/**
 * Created by Techgeek n Gamerboy on 05-Mar-18.
 */

public class NetworkMonitor extends BroadcastReceiver{
    @Override
    public void onReceive(Context context, Intent intent) {

    }

    //method to check network connection
    //returns true if there is a internet connection
    public static boolean checkNetworkConnection(Context context){

        ConnectivityManager connectivityManager = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo networkInfo = connectivityManager.getActiveNetworkInfo();

        //when device is in airplane mode then networkInfo is null
        return (networkInfo != null && networkInfo.isConnected());
    }
}
