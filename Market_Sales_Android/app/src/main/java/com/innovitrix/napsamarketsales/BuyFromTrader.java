package com.innovitrix.napsamarketsales;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.provider.Settings;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.text.Editable;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import androidx.constraintlayout.widget.ConstraintLayout;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

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
import com.google.android.material.textfield.TextInputLayout;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_FIRSTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LASTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRADER_ID;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRANSACTION_CHANNEL;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_VOLLEY_SOCKET_TIMEOUT_MS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_TRANSACTIONS;


public class BuyFromTrader extends AppCompatActivity {
    @TargetApi(Build.VERSION_CODES.O)
    private ProgressDialog progressDialog;
    ProgressBar progressBar;

    RequestQueue queue;
    ConstraintLayout layoutSupplierFind;
    ConstraintLayout layoutSupplier;
    Button btnFindSupplier;
    Button btnBack;
    Button btnPay;
    Button btnCancel;
    TextInputLayout textInputLayout_Seller_First_Name, textInputLayout_Seller_Last_Name, textInputLayout_Seller_Mobile_Number, textInputLayout_Amount;

    int mobile_number_char;
    String blockCharacterSet = "123456789";
    int userObjectLength;
    Double dblAmount;
    String mBuyer_Mobile;
    String stringTrader_id;

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
    com.innovitrix.napsamarketsales.models.User mUser;
    private static final String TAG = BuyFromTrader.class.getName();
    Handler handler = new Handler();
    private long backPressedTime;
    private Toast backToast;

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_buy_from_trader);
        getSupportActionBar().setSubtitle("make an order");
        // setDate();
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        progressDialog = new ProgressDialog(BuyFromTrader.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);

        textViewUsername = (TextView) findViewById(R.id.textViewUsername);
        textViewUsername.setText("Logged in as " + SharedPrefManager.getInstance(BuyFromTrader.this).getUser().getFirstname() + " " + SharedPrefManager.getInstance(BuyFromTrader.this).getUser().getLastname());
        textViewDate = (TextView) findViewById(R.id.textViewDate);
        textViewDate.setText(SharedPrefManager.getInstance(BuyFromTrader.this).getTranactionDate2());

        textInputLayout_Seller_First_Name = (TextInputLayout) findViewById(R.id.seller_First_Name_TextInputLayout);
        textInputLayout_Seller_Last_Name = (TextInputLayout) findViewById(R.id.seller_Last_Name_TextInputLayout);
        textInputLayout_Seller_Mobile_Number = (TextInputLayout) findViewById(R.id.mobile_number_TextInputLayout);
        textInputLayout_Amount = (TextInputLayout) findViewById(R.id.amount_TextInputLayout);


        textInputLayout_Seller_First_Name.setCounterEnabled(false);
        textInputLayout_Seller_Last_Name.setCounterEnabled(false);
        textInputLayout_Seller_Mobile_Number.setCounterEnabled(false);
        textInputLayout_Amount.setCounterEnabled(false);

        buyer_id = SharedPrefManager.getInstance(BuyFromTrader.this).getUser().getTrader_id();
        buyer_mobile_number = SharedPrefManager.getInstance(BuyFromTrader.this).getUser().getMobile_number();
        buyer_first_name = SharedPrefManager.getInstance(BuyFromTrader.this).getUser().getFirstname();
        buyer_last_name = SharedPrefManager.getInstance(BuyFromTrader.this).getUser().getLastname();

        seller_first_name = getIntent().getStringExtra(KEY_FIRSTNAME);
        seller_last_name = getIntent().getStringExtra(KEY_LASTNAME);
        seller_id = getIntent().getStringExtra(KEY_TRADER_ID);
        seller_mobile_number = getIntent().getStringExtra(KEY_MOBILE);

        textInputLayout_Amount.requestFocus();

        disableEditText();

        btnPay = findViewById(R.id.btnPay);

        btnPay.setEnabled(true);

        mobile_number_char = 0;
        userObjectLength = 0;

        progressBar = (ProgressBar) findViewById(R.id.progressBar);

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
                device_serial = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
                String string_amount = textInputLayout_Amount.getEditText().getText().toString().trim();

                if (TextUtils.isEmpty(string_amount)) {
                    textInputLayout_Amount.setErrorEnabled(true);
                    textInputLayout_Amount.setError("Enter a valid amount.");
                    textInputLayout_Amount.requestFocus();
                    return;
                }

                amount_due = Double.valueOf(string_amount);

                if (amount_due == 0) {
                    textInputLayout_Amount.setErrorEnabled(true);
                    textInputLayout_Amount.setError("Enter a valid amount.");
                    textInputLayout_Amount.requestFocus();
                    return;
                }

                    AlertDialog.Builder builder = new AlertDialog.Builder(BuyFromTrader.this);
                    builder.setCancelable(false);
                    builder.setMessage("Confirm order worth ZMW "+  string_amount+" from " + seller_first_name +" "+ seller_last_name +"?");
                    builder.setPositiveButton("Yes",
                            new DialogInterface.OnClickListener() {
                                public void onClick(DialogInterface dialog, int id) {
                                    progressBar.setVisibility(View.VISIBLE);
                                    sendInformation(2, seller_id, seller_first_name, seller_last_name, seller_mobile_number, buyer_id, buyer_first_name, buyer_last_name, buyer_mobile_number, device_serial, amount_due);
                                    dialog.cancel();
                                    AlertDialog.Builder builder = new AlertDialog.Builder(BuyFromTrader.this);
                                    builder.setCancelable(false);
                                    builder.setMessage("Check your phone that has mobile number "+  buyer_mobile_number+" to approve the payment. An SMS will notify you of the transaction status.");
                                    builder.setPositiveButton("Ok",
                                            new DialogInterface.OnClickListener() {
                                                public void onClick(DialogInterface dialog, int id) {
                                                    Intent intent = new Intent(BuyFromTrader.this, MainActivity.class);
                                                    intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                                    startActivity(intent);
                                                    finish();

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

        });
    }


    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                //do whatever
                Intent intent = new Intent(BuyFromTrader.this,FindTrader.class);
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
        Intent intent = new Intent(BuyFromTrader.this,FindTrader.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
    }

    public void disableEditText() {

        textInputLayout_Seller_First_Name.setFocusable(false);
        textInputLayout_Seller_Last_Name.setFocusable(false);
        textInputLayout_Seller_Mobile_Number.setFocusable(false);
        textInputLayout_Seller_First_Name.setFocusableInTouchMode(false);
        textInputLayout_Seller_Last_Name.setFocusableInTouchMode(false);
        textInputLayout_Seller_Mobile_Number.setFocusableInTouchMode(false);

//    editText_Seller_First_Name.setInputType(TYPE_NULL);
//    editText_Seller_Last_Name.setInputType(TYPE_NULL);
//    editText_Seller_Mobile_Number.setInputType(TYPE_NULL);


        textInputLayout_Seller_First_Name.getEditText().setText(seller_first_name);
        textInputLayout_Seller_Last_Name.getEditText().setText(seller_last_name);
        textInputLayout_Seller_Mobile_Number.getEditText().setText(seller_mobile_number);
        textInputLayout_Amount.requestFocus();
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
        transaction_date = SharedPrefManager.getInstance(this).getTranactionDate();

        // progressDialog.show();
        progressBar.setVisibility(View.VISIBLE);

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
            jsonObject.put("buyer_mobile_number",buyer_mobile_no);
            jsonObject.put("buyer_email", null);
            jsonObject.put("amount_due", amount_due);
            jsonObject.put("device_serial",device_serial);
            jsonObject.put("transaction_date",transaction_date);
            jsonObject.put( "route_code",null);
            jsonObject.put( "transaction_channel",KEY_TRANSACTION_CHANNEL);
            jsonObject.put( "bus_schedule_id",null);
            jsonObject.put( "id_type", null);
            jsonObject.put( "passenger_id",null);
            jsonObject.put( "travel_date",null);
            jsonObject.put("travel_time",null);
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

                            //Toast.makeText(getApplicationContext(),response.getString(KEY_MESSAGE), Toast.LENGTH_SHORT).show();

                            // DialogBox.mLovelyStandardDialog(BuyFromTrader.this, response.getString(KEY_MESSAGE)); //response.getString(KEY_MESSAGE)
                            Log.d("Response", response.getString(KEY_MESSAGE));
                            progressBar.setVisibility(View.GONE);
//                            AlertDialog.Builder builder = new AlertDialog.Builder(BuyFromTrader.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("Check your phone that has mobile number "+  buyer_mobile_number+" to approve the payment. An SMS will notify you of the transaction status.");
//                            builder.setPositiveButton("Ok",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//                                            Intent intent = new Intent(BuyFromTrader.this, MainActivity.class);
//                                            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
//                                            startActivity(intent);
//                                            finish();
//
//                                        }
//                                    });
//                            builder.create().show();
                        } catch (JSONException e) {
                            e.printStackTrace();
                            progressBar.setVisibility(View.GONE);
//                            AlertDialog.Builder builder = new AlertDialog.Builder(BuyFromTrader.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("An error occurred while processing the order, kindly check your internet connection and try again.");
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
                progressBar.setVisibility(View.GONE);
                Log.d("Error.Response", error.toString());
//                AlertDialog.Builder builder = new AlertDialog.Builder(BuyFromTrader.this);
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
        //queue.add(jsonObjectRequest);
    }

}
