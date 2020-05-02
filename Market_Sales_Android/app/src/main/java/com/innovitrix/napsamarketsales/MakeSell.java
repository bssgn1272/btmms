package com.innovitrix.napsamarketsales;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.provider.Settings;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;

import android.text.Editable;
import android.text.InputFilter;
import android.text.InputType;
import android.text.Selection;
import android.text.Spanned;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.text.method.NumberKeyListener;
import android.util.Log;
import android.view.View;
import android.support.constraint.ConstraintLayout;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;


import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.dialog.DialogBox;
import com.yarolegovich.lovelydialog.LovelyStandardDialog;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_MOBILE_NUMBER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_TRANSACTIONS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_USERS;

import android.widget.Toast;


public class MakeSell extends AppCompatActivity {

    @TargetApi(Build.VERSION_CODES.O)
    private ProgressDialog progressDialog;
    RequestQueue queue;

    Button btnPay;

    String blockCharacterSet = "123456789";
    EditText textMobileno;
    EditText etAmount;
    int trader_id;
    int mobile_number_char;




    Double dblAmount;


    String seller_id;
    String seller_first_name;
    String seller_last_name;
    String seller_mobile_number;
    String buyer_id;
    String  buyer_first_name;
    String  buyer_last_name;
    String buyer_mobile_number;
    Double amount_due;
    String device_serial;

    private static final String TAG = MakeSell.class.getName();


    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_make_sell);
        getSupportActionBar().setSubtitle("Make a sale");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        //setDate();
        progressDialog = new ProgressDialog(MakeSell.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);


        btnPay = findViewById(R.id.buttonPay);
        btnPay.setEnabled(true);

        textMobileno = findViewById(R.id.textMobileNo);
        textMobileno.addTextChangedListener(new PhoneNumberTextWatcher());
        textMobileno.setFilters(new InputFilter[]{new PhoneNumberFilter(), new InputFilter.LengthFilter(10)});

        etAmount = findViewById(R.id.textAMT);
        // mBuyer_Mobile = SharedPrefManager.getInstance(BuyFromTrader.this).getCustomer().getMobile_number();

        //mobile_number = mBuyer_Mobile;
        // etAmount.setFilters(new InputFilter[] { new AmountFilter(), new InputFilter.LengthFilter(12) });


        seller_id=  SharedPrefManager.getInstance(MakeSell.this).getUser().getTrader_id();
        seller_mobile_number = SharedPrefManager.getInstance(MakeSell.this).getUser().getMobile_number();
        seller_first_name= SharedPrefManager.getInstance(MakeSell.this).getUser().getFirstname();
        seller_last_name = SharedPrefManager.getInstance(MakeSell.this).getUser().getLastname();

        mobile_number_char = 0;


        btnPay.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {



                String string_buyer_mobile_number =  textMobileno.getText().toString();
                String string_amount = etAmount.getText().toString();


                if (TextUtils.isEmpty(string_buyer_mobile_number)) {
                    textMobileno.setError("Please enter a mobile number.");
                    textMobileno.requestFocus();
                    return;
                }
                if (TextUtils.isEmpty(string_amount)) {
                    etAmount.setError("Please enter an amount.");
                    etAmount.requestFocus();
                    return;
                }
                buyer_mobile_number = "26" + string_buyer_mobile_number;
                buyer_mobile_number = buyer_mobile_number.replace("-", "");
                amount_due = Double.valueOf(string_amount);

                if (amount_due==0) {
                   etAmount.setError("Please enter valid amount");
                    etAmount.requestFocus();
                    return;
                }

                if (mobile_number_char == 10) {


                    if (buyer_mobile_number.equals(seller_mobile_number)) {
                        //seller_mobile_number ="";
                        AlertDialog.Builder builder = new AlertDialog.Builder(MakeSell.this);

                        builder.setMessage("You can not buy from yourself. Change the number?");
                        builder.setPositiveButton("Yes",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                        dialog.cancel();
                                    }
                                });

                        builder.setNegativeButton("No",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                        dialog.cancel();
                                        finish();

                                    }
                                });

                        builder.create().show();
                    } else {

                        AlertDialog.Builder builder = new AlertDialog.Builder(MakeSell.this);
                        builder.setMessage("Confirm sale?");
                        builder.setPositiveButton("Yes",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {

                                        device_serial = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
                                         sendInformation(1, seller_id, seller_first_name,seller_last_name,seller_mobile_number, buyer_id, buyer_first_name, buyer_last_name,    buyer_mobile_number, device_serial,amount_due);
                                        textMobileno.setText("");
                                        etAmount.setText("");
//
                                        // dialog.cancel();
                                    }
                                });

                        builder.setNegativeButton("No",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {

                                        dialog.cancel();
                                    }
                                });

                        builder.create().show();
                    }
                }
                else
                {
                    textMobileno.setError("Enter a 10 digit mobile number.");
                    textMobileno.requestFocus();
                }
            }});}

    public void sendInformation
            (
            int transaction_type_id,
            String seller_id,
            String seller_first_name,
            String seller_last_name,
            String seller_mobile_number,
            String buyer_id,
            String buyer_first_name,
            String buyer_last_name,
            String buyer_mobile_number,
            String device_serial,
            double amount_due
            )
    {

        progressDialog.show();

        ///prepare your JSONObject which you want to send in your web service request

        JSONObject jsonObject = new JSONObject();
        try {

            jsonObject.put( "transaction_type_id",transaction_type_id);
            jsonObject.put("seller_id", seller_id);
            jsonObject.put("seller_firstname",seller_first_name);
            jsonObject.put("seller_lastname",seller_last_name);
            jsonObject.put("seller_mobile_number",seller_mobile_number);
            jsonObject.put("buyer_id",buyer_id);
            jsonObject.put("buyer_firstname",buyer_first_name);
            jsonObject.put("buyer_lastname",buyer_last_name);
            jsonObject.put("buyer_mobile_number",buyer_mobile_number);
            jsonObject.put("buyer_email", null);
            jsonObject.put("amount_due", amount_due);
            jsonObject.put("device_serial",device_serial);
            jsonObject.put("transaction_date", Calendar.getInstance().getTime());
            jsonObject.put( "route_code",null);
            jsonObject.put( "transaction_channel","API");
            jsonObject.put( "id_type",null);
            jsonObject.put( "passenger_id",null);
            jsonObject.put( "travel_date",null);
            /// Log.d("Error.Response", jsonObject.toString());
        } catch (JSONException e) {
            e.printStackTrace();
        }

        // prepare the Request
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, URL_TRANSACTIONS, jsonObject,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        //Do stuff here
                        // display response
                        progressDialog.dismiss();
                        Log.d("Response", response.toString());
                        try {
                            etAmount.requestFocus();
                            DialogBox.mLovelyStandardDialog(MakeSell.this, response.getString(KEY_MESSAGE));
                            //startActivity(new Intent( MakeSell.this,MainActivity.class));

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener() {


            @Override
            public void onErrorResponse(VolleyError error) {
                //Handle Errors here
                progressDialog.dismiss();
                Log.d("Error.Response", error.toString());
                Log.d("Error.Response", error.getMessage());

                DialogBox.mLovelyStandardDialog( MakeSell.this, error.toString());
            }
        }) {

            /** Passing some request headers* */
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                Map<String, String> headers = new HashMap<>();
                headers.put("Content-Type", "application/json");
                headers.put("apiKey", "xxxxxxxxxxxxxxx");
                return headers;
            }

        };

        // add it to the RequestQueue
        queue.add(jsonObjectRequest);
    }



//    public void sendInformation(int trader_id, String mobile_number, double amount) {
//
//        progressDialog.show();
//
//        ///prepare your JSONObject which you want to send in your web service request
//        String serialNumber = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
//
//        trader_id = 16;
//        JSONObject jsonObject = new JSONObject();
//        try {
//            jsonObject.put("marketeer_id", trader_id);
//            jsonObject.put("buyer_mobile_number", mobile_number);
//            jsonObject.put("amount_due", amount);
//            jsonObject.put("token_tendered", amount);
//            jsonObject.put("device_serial", serialNumber);
//            jsonObject.put("transaction_date", "2019-12-14");
//            /// Log.d("Error.Response", jsonObject.toString());
//        } catch (JSONException e) {
//            e.printStackTrace();
//        }
//
//        // prepare the Request
//        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, URL_TRANSACTIONS, jsonObject,
//                new Response.Listener<JSONObject>() {
//                    @Override
//                    public void onResponse(JSONObject response) {
//
//                        //Do stuff here
//                        // display response
//                        progressDialog.dismiss();
//                        Log.d("Response", response.toString());
//
//                        try {
//
//
//                            new LovelyStandardDialog(MakeSell.this)
//                                    .setTopColorRes(R.color.colorPrimary)
//                                    .setButtonsColorRes(R.color.colorAccent)
//                                    .setIcon(R.drawable.ic_notifications_active_black_24dp)
//                                    .setTitle("")
//                                    .setMessage(response.getString(KEY_MESSAGE))
//                                    .setPositiveButton(android.R.string.ok, new View.OnClickListener() {
//                                        @Override
//                                        public void onClick(View v) {
//                                            startActivity(new Intent( MakeSell.this,MakeSell.class));
//                                        }
//                                    })
//                                    .show();
//
//
//
//                        } catch (JSONException e) {
//                            e.printStackTrace();
//                        }
//
//                    }
//                }, new Response.ErrorListener() {
//
//
//            @Override
//            public void onErrorResponse(VolleyError error) {
//                //Handle Errors here
//                progressDialog.dismiss();
//                Log.d("Error.Response", error.toString());
//                Log.d("Error.Response", error.getMessage());
//
//            }
//        }) {
//
//            /* Passing some request headers */
//            @Override
//            public Map<String, String> getHeaders() throws AuthFailureError {
//                Map<String, String> headers = new HashMap<>();
//                headers.put("Content-Type", "application/json");
//                headers.put("apiKey", "xxxxxxxxxxxxxxx");
//                return headers;
//            }
//
//        };
//
//        // add it to the RequestQueue
//        queue.add(jsonObjectRequest);
//    }

//    public void setDate()
//    {
//        Date today = Calendar.getInstance().getTime();//getting date
//        SimpleDateFormat formatter = new SimpleDateFormat("EEE, d MMM yyyy");//formating according to my need
//        String date = formatter.format(today);
//        TextView txtViewDate = (TextView)findViewById(R.id.textViewDate);
//        txtViewDate.setText(date);
//    }
    public class PhoneNumberTextWatcher implements TextWatcher {

        private boolean isFormatting;
        private boolean deletingHyphen;
        private int hyphenStart;
        private boolean deletingBackward;

        @Override
        public void afterTextChanged(Editable text) {
            if (isFormatting)
                return;

            isFormatting = true;

            // If deleting hyphen, also delete character before or after it
            if (deletingHyphen && hyphenStart > 0) {
                if (deletingBackward) {
                    if (hyphenStart - 1 < text.length()) {
                        text.delete(hyphenStart - 1, hyphenStart);
                    }
                } else if (hyphenStart < text.length()) {
                    text.delete(hyphenStart, hyphenStart + 1);
                }
            }
            if (text.length() == 4 || text.length() == 8) {
                text.append('-');
            }

            isFormatting = false;
        }

        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            if (isFormatting)
                return;

            // Make sure user is deleting one char, without a selection
            final int selStart = Selection.getSelectionStart(s);
            final int selEnd = Selection.getSelectionEnd(s);
            if (s.length() > 1 // Can delete another character
                    && count == 1 // Deleting only one character
                    && after == 0 // Deleting
                    && s.charAt(start) == '-' // a hyphen
                    && selStart == selEnd) { // no selection
                deletingHyphen = true;
                hyphenStart = start;
                // Check if the user is deleting forward or backward
                if (selStart == start + 1) {
                    deletingBackward = true;
                } else {
                    deletingBackward = false;
                }
            } else {
                deletingHyphen = false;
            }
        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {

            mobile_number_char = textMobileno.getText().toString().length();
            if (textMobileno.getText().toString().length() == 0)
                blockCharacterSet = "123456789";

            else
                blockCharacterSet = "";
            if (textMobileno.getText().toString().length() == 1)
                blockCharacterSet = "0";
        }
    }

    public class PhoneNumberFilter extends NumberKeyListener {

        @Override
        public int getInputType() {
            return InputType.TYPE_CLASS_PHONE;
        }

        @Override
        protected char[] getAcceptedChars() {
            return new char[]{'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-'};
        }

        @Override
        public CharSequence filter(CharSequence source, int start, int end,
                                   Spanned dest, int dstart, int dend) {

            try {
                // Don't let phone numbers start with 1


                if (source != null && blockCharacterSet.contains("" + source.charAt(0)))
                    return "";


                //if (dstart == 0 && source.equals("1"))
                //   return "";

                if (end > start) {
                    String destTxt = dest.toString();
                    String resultingTxt = destTxt.substring(0, dstart) + source.subSequence(start, end) + destTxt.substring(dend);

                    // Phone number must match xxx-xxx-xxxx
                    if (!resultingTxt.matches("^\\d{0,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}?)?)?)?)?)?)?)?)?)?")) {
                        //   if (!resultingTxt.matches("^\\d{1,1}(\\d{1,1}(\\d{1,1}(\\-(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\-(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}?)?)?)?)?)?)?)?)?)?)?)?")) {

                        return "";
                    }
                }
                return null;
            } catch (StringIndexOutOfBoundsException e) {

            }
            return null;
        }
    }


}
