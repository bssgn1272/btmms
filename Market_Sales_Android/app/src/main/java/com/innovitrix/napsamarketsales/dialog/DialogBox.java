package com.innovitrix.napsamarketsales.dialog;

import android.content.Context;
import android.content.DialogInterface;
import android.support.v7.app.AlertDialog;
import android.view.View;

import com.innovitrix.napsamarketsales.R;
import com.yarolegovich.lovelydialog.LovelyInfoDialog;
import com.yarolegovich.lovelydialog.LovelyStandardDialog;

/**
 * Created by Techgeek n Gamerboy on 24-Oct-17.
 */

public class DialogBox {

    public static void mLovelyStandardDialog(Context context, int message){
        new LovelyStandardDialog(context)
                .setTopColorRes(R.color.colorPrimary)
                .setButtonsColorRes(R.color.colorAccent)
                .setIcon(R.drawable.ic_notifications_active_black_24dp)
                .setTitle("")
                .setMessage(message)
                .setPositiveButton(android.R.string.ok, new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        //Toast.makeText(getApplicationContext(), "positive clicked", Toast.LENGTH_SHORT).show();
                    }
                })
                .show();
    }


    public static void mLovelyStandardDialog(Context context, String message){
        new LovelyStandardDialog(context)
                .setTopColorRes(R.color.colorPrimary)
                .setButtonsColorRes(R.color.colorAccent)
                .setIcon(R.drawable.ic_notifications_active_black_24dp)
                .setTitle("")
                .setMessage(message)
                .setPositiveButton(android.R.string.ok, new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        //Toast.makeText(getApplicationContext(), "positive clicked", Toast.LENGTH_SHORT).show();
                    }
                })
                .show();
    }

    public static void mLovelyStandardDialog(Context context, String subject , String message){
        new LovelyStandardDialog(context)
                .setTopColorRes(R.color.colorPrimary)
                .setButtonsColorRes(R.color.colorAccent)
                .setIcon(R.drawable.ic_notifications_active_black_24dp)
                .setTitle(subject)
                .setMessage(message)
                .setPositiveButton(android.R.string.ok, new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        //Toast.makeText(getApplicationContext(), "positive clicked", Toast.LENGTH_SHORT).show();
                    }
                })
                .show();
    }

    public static void mLovelyInfoDialog(Context context, String subject , String message){
        new LovelyInfoDialog(context)
                .setTopColorRes(R.color.colorPrimary)
                .setIcon(R.drawable.ic_notifications_active_black_24dp)
                .setTitle(subject)
                .setMessage(message)
                .show();
    }

    public static void AletDialog(final Context context){
        new AlertDialog.Builder(context)
                //.setIcon(R.drawable.ic_info_outline_white_36dp)
                .setTitle("Are you sure?")
                .setMessage("Do you definitely want to do this?")
                .setPositiveButton("Yes", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        Toasting.Toast_Short(context,"It's Done!");
                    }
                })
                .setNegativeButton("No",null)
                .show();
    }


}
