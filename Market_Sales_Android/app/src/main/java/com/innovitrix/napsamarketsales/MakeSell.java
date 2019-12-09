package com.innovitrix.napsamarketsales;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
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

import java.util.HashMap;
import java.util.Map;

import com.android.volley.toolbox.Volley;

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
    Button btnBack;
    String blockCharacterSet = "123456789";
    EditText textMobileno;
    EditText etAmount;
    int trader_id;
    int mobile_number_char ;

    Double dblAmount;
    private static final String TAG = MainActivity.class.getName();


    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_make_sell);

        progressDialog  = new ProgressDialog(MakeSell.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);


        btnPay = findViewById(R.id.buttonPay);
        btnPay.setEnabled(true);
        btnBack = findViewById(R.id.btnBack);
        btnBack.setEnabled(true);
        textMobileno = findViewById(R.id.textMobileNo);
        textMobileno.addTextChangedListener(new PhoneNumberTextWatcher());
        textMobileno.setFilters(new InputFilter[] { new PhoneNumberFilter(), new InputFilter.LengthFilter(10) });

        etAmount = findViewById(R.id.textAMT);
       // mBuyer_Mobile = SharedPrefManager.getInstance(BuyFromTrader.this).getCustomer().getMobile_number();

        //mobile_number = mBuyer_Mobile;
        // etAmount.setFilters(new InputFilter[] { new AmountFilter(), new InputFilter.LengthFilter(12) });

        mobile_number_char =0 ;

        trader_id = 16;
        btnBack.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view) {
                finish();
            }
        });
        btnPay.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                AlertDialog.Builder   builder = new AlertDialog.Builder(MakeSell.this);


                builder.setMessage("Are you want to make this payment?");
                builder.setPositiveButton("Yes",
                        new DialogInterface.OnClickListener()
                        {
                            public void onClick(DialogInterface dialog, int id)
                            {
                                dblAmount = Double.valueOf(etAmount.getText().toString());
                                sendInformation(trader_id, textMobileno.getText().toString(), dblAmount);
                                // dialog.cancel();
                            }
                        });

                builder.setNegativeButton("No",
                        new DialogInterface.OnClickListener()
                        {
                            public void onClick(DialogInterface dialog, int id)
                            {

                                dialog.cancel();
                            }
                        });

                builder.create().show();
            }
        });
    }

    public void sendInformation(int trader_id,  String mobile_number, double amount) {

        progressDialog.show();

        ///prepare your JSONObject which you want to send in your web service request
        String serialNumber = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);

trader_id = 16;
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put( "marketeer_id", trader_id);
            jsonObject.put("buyer_mobile_number",mobile_number);
            jsonObject.put("amount_due", amount);
            jsonObject.put("token_tendered", amount);
            jsonObject.put("device_serial", serialNumber );
            jsonObject.put("transaction_date", "2019-12-14");
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

                        //                        try {
                        //
                        //                            //Do stuff here
                        //                            // display response
                        //                            progressDialog.dismiss();
                        //                            Log.d("Response", response.toString());
                        //
                        //
                        //
                        //                        } catch (JSONException e) {
                        //                            e.printStackTrace();
                        //                        }

                    }
                }, new Response.ErrorListener() {


            @Override
            public void onErrorResponse(VolleyError error) {
                //Handle Errors here
                progressDialog.dismiss();
                Log.d("Error.Response", error.toString());
                Log.d("Error.Response", error.getMessage());

            }
        }) {

            /* Passing some request headers */
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
            if(textMobileno.getText().toString().length()==0)
                blockCharacterSet="123456789";

            else
                blockCharacterSet="";
            if( textMobileno.getText().toString().length()==1)
                blockCharacterSet="123456780";
        }
    }

    public class PhoneNumberFilter extends NumberKeyListener {

        @Override
        public int getInputType() {
            return InputType.TYPE_CLASS_PHONE;
        }

        @Override
        protected char[] getAcceptedChars() {
            return new char[] { '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-' };
        }

        @Override
        public CharSequence filter(CharSequence source, int start, int end,
                                   Spanned dest, int dstart, int dend) {

            try {
                // Don't let phone numbers start with 1


                if ( source != null && blockCharacterSet.contains("" + source.charAt(0)))
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
            }catch (StringIndexOutOfBoundsException e){

            }
            return null;
        }
    }



}
