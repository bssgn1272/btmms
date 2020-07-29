package com.innovitrix.napsamarketsales;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;

import com.google.android.material.textfield.TextInputLayout;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.text.Editable;
import android.text.InputFilter;
import android.text.InputType;
import android.text.Selection;
import android.text.Spanned;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.text.method.NumberKeyListener;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
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

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRANSACTION_CHANNEL;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_VOLLEY_SOCKET_TIMEOUT_MS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_TRANSACTIONS;

import android.widget.Toast;


public class MakeSell extends AppCompatActivity {

    @TargetApi(Build.VERSION_CODES.O)
    private ProgressDialog progressDialog;
    ProgressBar progressBar;
    RequestQueue queue;
    Button btnPay;
    private TextInputLayout textInputLayout_Mobile_Number, textInputLayout_Amount;

    String blockCharacterSet = "123456789";

    int trader_id;
    int mobile_number_char;
    int email_or_mobile_char;
    Double dblAmount;

    String seller_id;
    String seller_first_name;
    String seller_last_name;
    String seller_mobile_number;
    String buyer_id;
    String buyer_first_name;
    String buyer_last_name;
    String buyer_mobile_number;
    Double amount_due;
    String device_serial;
    String transaction_date;
    TextView textViewUsername;
    TextView textViewDate;
    String required_text_char;
    private long backPressedTime;
    private Toast backToast;
    private static final String TAG = MakeSell.class.getName();

    TextInputLayout textInputLayoutMobileno;

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_make_sell);
        getSupportActionBar().setSubtitle("make a sale");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);


        //  TextInputLayout textInputLayout = (TextInputLayout)findViewById(R.id.text_input_layout);
        // String bb =textInputLayout.getHint()+ CHAR_REQUIRED;

        //textInputLayout.setHint(Html.fromHtml(bb));

        textViewUsername = (TextView) findViewById(R.id.textViewUsername);
        textViewUsername.setText("Logged in as " + SharedPrefManager.getInstance(MakeSell.this).getUser().getFirstname() + " " + SharedPrefManager.getInstance(MakeSell.this).getUser().getLastname());
        textViewDate = (TextView) findViewById(R.id.textViewDate);
        textViewDate.setText(SharedPrefManager.getInstance(MakeSell.this).getTranactionDate2());

        progressBar = (ProgressBar) findViewById(R.id.progressBar);
        progressDialog = new ProgressDialog(MakeSell.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);


        btnPay = findViewById(R.id.buttonPay);
        btnPay.setEnabled(true);

        textInputLayout_Mobile_Number = (TextInputLayout) findViewById(R.id.mobile_number_TextInputLayout);
        textInputLayout_Amount = (TextInputLayout) findViewById(R.id.amount_TextInputLayout);

        textInputLayout_Mobile_Number.getEditText().addTextChangedListener(new MakeSell.PhoneNumberTextWatcher());
        textInputLayout_Mobile_Number.getEditText().setFilters(new InputFilter[]{new MakeSell.PhoneNumberFilter(), new InputFilter.LengthFilter(10)});

        textInputLayout_Mobile_Number.requestFocus();
        textInputLayout_Amount.setCounterEnabled(false);

        //etAmount = findViewById(R.id.textAMT);
        // mBuyer_Mobile = SharedPrefManager.getInstance(BuyFromTrader.this).getCustomer().getMobile_number();

        //mobile_number = mBuyer_Mobile;
        // etAmount.setFilters(new InputFilter[] { new AmountFilter(), new InputFilter.LengthFilter(12) });


        seller_id = SharedPrefManager.getInstance(MakeSell.this).getUser().getTrader_id();
        seller_mobile_number = SharedPrefManager.getInstance(MakeSell.this).getUser().getMobile_number();
        seller_first_name = SharedPrefManager.getInstance(MakeSell.this).getUser().getFirstname();
        seller_last_name = SharedPrefManager.getInstance(MakeSell.this).getUser().getLastname();

        mobile_number_char = 0;

        textInputLayout_Amount.getEditText().addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
             /*   if (s.length() < 1) {
                    textInputLayout.setErrorEnabled(true);
                    textInputLayout.setError("Please enter a value");
                }

                if (s.length() > 0) {
                    textInputLayout.setError(null);
                    textInputLayout.setErrorEnabled(false);
                }
*/
                textInputLayout_Amount.setError(null);

            }

            @Override
            public void afterTextChanged(Editable s) {

            }
        });

        btnPay.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                String string_buyer_mobile_number = textInputLayout_Mobile_Number.getEditText().getText().toString().trim();
                String string_amount = textInputLayout_Amount.getEditText().getText().toString().trim();

                mobile_number_char = string_buyer_mobile_number.length();

                if (TextUtils.isEmpty(string_buyer_mobile_number) || mobile_number_char != 10) {
                    textInputLayout_Mobile_Number.setErrorEnabled(true);
                    textInputLayout_Mobile_Number.setError("Enter a 10 digit mobile number (0xxxxxxxxx).");
                    textInputLayout_Mobile_Number.requestFocus();
                    return;
                }
                if (TextUtils.isEmpty(string_amount)) {
                    textInputLayout_Amount.setErrorEnabled(true);
                    textInputLayout_Amount.setError("Enter a valid amount.");
                    textInputLayout_Amount.requestFocus();
                    return;
                }
                buyer_mobile_number = "26" + string_buyer_mobile_number;
                buyer_mobile_number = buyer_mobile_number.replace("-", "");
                amount_due = Double.valueOf(string_amount);

                if (amount_due == 0) {
                    textInputLayout_Amount.setErrorEnabled(true);
                    textInputLayout_Amount.setError("Enter a valid amount.");
                    textInputLayout_Amount.requestFocus();
                    return;
                }

                if (mobile_number_char == 10) {

                    if (buyer_mobile_number.equals(seller_mobile_number)) {
                        //seller_mobile_number ="";
                       final AlertDialog.Builder builder = new AlertDialog.Builder(MakeSell.this);
                        builder.setCancelable(false);

                        builder.setMessage("You can not buy from yourself. Change the number?");
                        builder.setPositiveButton("Yes",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                        dialog.cancel();
                                        textInputLayout_Mobile_Number.requestFocus();

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
                        builder.setCancelable(false);
                        builder.setMessage("Confirm sales worth ZMW " + string_amount + " to " + buyer_mobile_number + "?");
                        builder.setPositiveButton("Yes",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                        progressBar.setVisibility(View.VISIBLE);
                                        device_serial = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
                                        sendInformation(1, seller_id, seller_first_name, seller_last_name, seller_mobile_number, buyer_id, buyer_first_name, buyer_last_name, buyer_mobile_number, device_serial, amount_due);

                                        dialog.cancel();
                                        progressBar.setVisibility(View.GONE);
                                        AlertDialog.Builder builder = new AlertDialog.Builder(MakeSell.this);
                                        builder.setCancelable(false);
                                        builder.setMessage("Ask the customer to check their phone with mobile number "+ buyer_mobile_number+" to approve the payment. An SMS will notify you of the transaction status.");
                                        builder.setPositiveButton("Yes",
                                                new DialogInterface.OnClickListener() {
                                                    public void onClick(DialogInterface dialog, int id) {
                                                        Intent intent = new Intent(MakeSell.this, MainActivity.class);
                                                        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                                        startActivity(intent);
                                                        finish();
                                                        dialog.cancel();
                                                    }
                                                });

                                        builder.create().show();


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
                } else {
                    textInputLayout_Mobile_Number.setErrorEnabled(true);
                    textInputLayout_Mobile_Number.setError("Enter a 10 digit mobile number (0xxxxxxxxx).");
                    textInputLayout_Mobile_Number.requestFocus();

                }
            }
        });


    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                //do whatever
                Intent intent = new Intent(MakeSell.this, MainActivity.class);
                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
                finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }}

    @Override
    public void onBackPressed() {
        // Toast.makeText(getApplication(),"Use the in app controls to navigate.",Toast.LENGTH_SHORT).show();
        Intent intent = new Intent(MakeSell.this, MainActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
    }
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
                    String buyer_mobile_no,
                    String device_serial,
                    double amount_due
            ) {

        // String result = format.format(cal.getTime());

        //progressDialog.show();
        progressBar.setVisibility(View.VISIBLE);
        transaction_date = SharedPrefManager.getInstance(MakeSell.this).getTranactionDate();

        ///prepare your JSONObject which you want to send in your web service request

        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("transaction_type_id", transaction_type_id);
            jsonObject.put("seller_id", seller_id);
            jsonObject.put("seller_firstname", seller_first_name);
            jsonObject.put("seller_lastname", seller_last_name);
            jsonObject.put("seller_mobile_number", seller_mobile_number);
            jsonObject.put("buyer_id", buyer_id);
            jsonObject.put("buyer_firstname", buyer_first_name);
            jsonObject.put("buyer_lastname", buyer_last_name);
            jsonObject.put("buyer_mobile_number", buyer_mobile_no);
            jsonObject.put("buyer_email", null);
            jsonObject.put("amount_due", amount_due);
            jsonObject.put("device_serial", device_serial);
            jsonObject.put("transaction_date", transaction_date);
            jsonObject.put("route_code", null);
            jsonObject.put("transaction_channel", KEY_TRANSACTION_CHANNEL);
            jsonObject.put("bus_schedule_id", null);
            jsonObject.put("id_type", null);
            jsonObject.put("passenger_id", null);
            jsonObject.put("travel_date", null);
            jsonObject.put("travel_time", null);
            jsonObject.put("stand_number", null);


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
                        progressBar.setVisibility(View.GONE);

                        try {
                            Log.d("Response", response.getString(KEY_MESSAGE));
                            progressBar.setVisibility(View.GONE);
//                            AlertDialog.Builder builder = new AlertDialog.Builder(MakeSell.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("Ask the customer to check their phone with mobile number "+ buyer_mobile_number+" to approve the payment. An SMS will notify you of the transaction status.");
//                            builder.setPositiveButton("Yes",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//                                            Intent intent = new Intent(MakeSell.this, MainActivity.class);
//                                            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
//                                            startActivity(intent);
//                                            finish();
//                                            // dialog.cancel();
//                                        }
//                                    });
//
//                            builder.create().show();
                        } catch (JSONException e) {
                            e.printStackTrace();

                            progressBar.setVisibility(View.GONE);
//                            AlertDialog.Builder builder = new AlertDialog.Builder(MakeSell.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("An error occurred while processing the sale, kindly check your internet connection and try again.");
//                            builder.setPositiveButton("Ok",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//                                            //Intent intent = new Intent(ResetPin.this,LoginActivity.class);
//                                            // startActivity(intent);
//                                        }
//                                    });
//                            builder.create().show();
                        }
                    }
                }, new Response.ErrorListener() {


            @Override
            public void onErrorResponse(VolleyError error) {
                //Handle Errors here
                progressDialog.dismiss();
                Log.d("Error.Response", error.toString());
                //Log.d("Error.Response", error.getMessage());
                progressBar.setVisibility(View.GONE);
//                AlertDialog.Builder builder = new AlertDialog.Builder(MakeSell.this);
//                builder.setCancelable(false);
//                builder.setMessage("Connection failure, kindly check your internet connection and try again.");
//                builder.setPositiveButton("Ok",
//                        new DialogInterface.OnClickListener() {
//                            public void onClick(DialogInterface dialog, int id) {
//                                //Intent intent = new Intent(ResetPin.this,LoginActivity.class);
//                                // startActivity(intent);
//                            }
//                        });
//                builder.create().show();
//                //DialogBox.mLovelyStandardDialog( MakeSell.this, error.toString());
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
        jsonObjectRequest.setRetryPolicy(new DefaultRetryPolicy(KEY_VOLLEY_SOCKET_TIMEOUT_MS,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonObjectRequest);

        // add it to the RequestQueue
        //  queue.add(jsonObjectRequest);
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

            textInputLayout_Mobile_Number.setError(null);
            buyer_mobile_number = textInputLayout_Mobile_Number.getEditText().getText().toString().trim();

            if (buyer_mobile_number.length()== 0)
                blockCharacterSet = "123456789";

            else
                blockCharacterSet = "";
            if (buyer_mobile_number.length() == 1)
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
