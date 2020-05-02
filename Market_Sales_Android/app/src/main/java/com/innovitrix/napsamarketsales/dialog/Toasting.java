package com.innovitrix.napsamarketsales.dialog;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

/**
 * Created by Techgeek n Gamerboy on 07-Sep-17.
 */

public class Toasting {

    public static void Log(String message){
        Log.d("Look out","" + message);
    }

    public static void Toast_Short(Context context, String message){
        Toast.makeText(context, message + "", Toast.LENGTH_SHORT).show();
    }

    public static void Toast_Long(Context context, String message){
        Toast.makeText(context, message + "", Toast.LENGTH_LONG).show();
    }

}
