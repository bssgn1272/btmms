package com.innovitrix.napsamarketsales;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Build;
import android.provider.Settings;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;
import android.text.Editable;
import android.text.InputFilter;
import android.text.InputType;
import android.text.Selection;
import android.text.Spanned;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.text.method.NumberKeyListener;
import android.util.Log;
import android.util.Patterns;
import android.view.MenuItem;
import android.view.View;
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
import com.android.volley.toolbox.Volley;
import com.google.android.material.textfield.TextInputLayout;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.GregorianCalendar;
import java.util.HashMap;
import java.util.Map;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_BUS_FARE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_BUS_LICENSE_PLATE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_BUS_OPERATOR_NAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_BUS_SCHEDULE_ID;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_DEPARTURE_DATE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_DEPARTURE_TIME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_END_ROUTE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_CODE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_NAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_START_ROUTE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRANSACTION_CHANNEL;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRAVEL_DATE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_VOLLEY_SOCKET_TIMEOUT_MS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_TRANSACTIONS;

public class BusBuyTicketBuyerDetails extends AppCompatActivity {

    @TargetApi(Build.VERSION_CODES.O)
    ProgressDialog progressDialog;
    private ProgressBar progressBar;
    RequestQueue queue;
    TextInputLayout textInputLayout_Buyer_First_Name, textInputLayout_Buyer_Last_Name, textInputLayout_Buyer_NRC, textInputLayout_Buyer_Mobile_Number, textInputLayout_Buyer_Email_Address, textInputLayout_Amount;

    TextView bus_Schedule_Details;
    String firstname;
    String lastname;
    String nrc;
    String mobilenumber;
    String route_name;
    String departure_date;
    String departure_time;
    String bus_operator_name;
    String bus_license_plate;
    String bus_fare;
    String route_code;
    String bus_schedule_id;
    private String travel_date;
    private String start_route;
    private String end_route;

    Button btnSubmit;

    String emailPattern = "(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|\"(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21\\x23-\\x5b\\x5d-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x21-\\x5a\\x53-\\x7f]|\\\\[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f])+)\\])";
    String blockCharacterSet = "123456789";
    String device_serial;
    String seller_id;
    String seller_first_name;
    String seller_last_name;
    String seller_mobile_number;
    String buyer_id;
    String buyer_first_name;
    String buyer_last_name;
    String buyer_nrc;
    String buyer_mobile_number;
    String buyer_email;
    String string_amount;
    Double amount_due;
    String transaction_date;
    GregorianCalendar cal;// = new GregorianCalendar();
    SimpleDateFormat format;// = new SimpleDateFormat("yyyy-MM-dd");
    int buyer_first_name_char;
    int  buyer_last_name_char ;
    int buyer_mobile_number_char;
    int buyer_nrc_char;
    TextView textViewUsername;
    TextView textViewDate;
    private long backPressedTime;
    private Toast backToast;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_bus_buy_ticket_buyer_details);
        getSupportActionBar().setSubtitle("bus ticket details");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
         textViewUsername = (TextView)findViewById(R.id.textViewUsername);
        textViewUsername.setText("Logged in as "+SharedPrefManager.getInstance(BusBuyTicketBuyerDetails.this).getUser().getFirstname()+ " "+SharedPrefManager.getInstance(BusBuyTicketBuyerDetails.this).getUser().getLastname());
        textViewDate = (TextView) findViewById(R.id.textViewDate);
        textViewDate.setText(SharedPrefManager.getInstance(BusBuyTicketBuyerDetails.this).getTranactionDate2());

        progressBar = (ProgressBar) findViewById(R.id.progressBar);
        progressDialog = new ProgressDialog(BusBuyTicketBuyerDetails.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);
        cal = new GregorianCalendar();
        format = new SimpleDateFormat("yyyy-MM-dd");
        format.setCalendar(cal);

        seller_id = SharedPrefManager.getInstance(BusBuyTicketBuyerDetails.this).getUser().getTrader_id();
        seller_mobile_number = SharedPrefManager.getInstance(BusBuyTicketBuyerDetails.this).getUser().getMobile_number();
        seller_first_name = SharedPrefManager.getInstance(BusBuyTicketBuyerDetails.this).getUser().getFirstname();
        seller_last_name = SharedPrefManager.getInstance(BusBuyTicketBuyerDetails.this).getUser().getLastname();

        textInputLayout_Buyer_First_Name = (TextInputLayout) findViewById(R.id.first_Name_TextInputLayout);
        textInputLayout_Buyer_Last_Name = (TextInputLayout) findViewById(R.id.last_Name_TextInputLayout);
        textInputLayout_Buyer_Mobile_Number = (TextInputLayout) findViewById(R.id.mobile_Number_TextInputLayout);
        textInputLayout_Buyer_NRC =  (TextInputLayout) findViewById(R.id.nrc_TextInputLayout);
        textInputLayout_Buyer_Email_Address = (TextInputLayout) findViewById(R.id.email_address_TextInputLayout);
        textInputLayout_Buyer_First_Name.requestFocus();


        textInputLayout_Buyer_First_Name.setCounterEnabled(false);
        textInputLayout_Buyer_Last_Name.setCounterEnabled(false);
        textInputLayout_Buyer_NRC.setCounterEnabled(false);
        textInputLayout_Buyer_Email_Address.setCounterEnabled(false);


        route_name = getIntent().getStringExtra(KEY_ROUTE_NAME);
        route_code = getIntent().getStringExtra(KEY_ROUTE_CODE);
        departure_date = getIntent().getStringExtra(KEY_DEPARTURE_DATE);
        departure_time = getIntent().getStringExtra(KEY_DEPARTURE_TIME);
        bus_operator_name = getIntent().getStringExtra(KEY_BUS_OPERATOR_NAME);
        bus_license_plate = getIntent().getStringExtra(KEY_BUS_LICENSE_PLATE);
        bus_fare = getIntent().getStringExtra(KEY_BUS_FARE);
        bus_schedule_id = getIntent().getStringExtra(KEY_BUS_SCHEDULE_ID);
        start_route = getIntent().getStringExtra(KEY_START_ROUTE);
        end_route = getIntent().getStringExtra(KEY_END_ROUTE);
        travel_date = getIntent().getStringExtra(KEY_TRAVEL_DATE);
        btnSubmit = (Button) findViewById(R.id.buttonSubmit);

        InputFilter string_filter = new InputFilter() {
            public CharSequence filter(CharSequence source, int start, int end,
                                       Spanned dest, int dstart, int dend) {
                for (int i = start; i < end; i++) {
                    if (!Character.isLetter(source.charAt(i))) {
                        return "";
                    }
                }
                return null;
            }
        };
        InputFilter alphaNumeric_filter = new InputFilter() {
            public CharSequence filter(CharSequence source, int start, int end,
                                       Spanned dest, int dstart, int dend) {
                for (int i = start; i < end; i++) {
                    if (!Character.isLetterOrDigit(source.charAt(i))) {
                        return "";
                    }
                }
                return null;
            }
        };


        textInputLayout_Buyer_First_Name.getEditText().setFilters(new InputFilter[] { string_filter });
        textInputLayout_Buyer_Last_Name.getEditText().setFilters(new InputFilter[] { string_filter });
        //  textInputLayout_Buyer_NRC.getEditText().setFilters(new InputFilter[] {alphaNumeric_filter });
        textInputLayout_Buyer_Mobile_Number.getEditText().addTextChangedListener(new BusBuyTicketBuyerDetails.PhoneNumberTextWatcher());
        textInputLayout_Buyer_Mobile_Number.getEditText().setFilters(new InputFilter[]{new BusBuyTicketBuyerDetails.PhoneNumberFilter(), new InputFilter.LengthFilter(10)});
        textInputLayout_Buyer_First_Name.requestFocus();

        textInputLayout_Buyer_First_Name.getEditText().addTextChangedListener(new TextWatcher() {
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
                textInputLayout_Buyer_First_Name.setError(null);
                       }

            @Override
            public void afterTextChanged(Editable s) {

            }
        });

        textInputLayout_Buyer_Last_Name.getEditText().addTextChangedListener(new TextWatcher() {
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
                textInputLayout_Buyer_Last_Name.setError(null);

            }

            @Override
            public void afterTextChanged(Editable s) {

            }
        });

        textInputLayout_Buyer_NRC.getEditText().addTextChangedListener(new TextWatcher() {
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
                textInputLayout_Buyer_NRC.setError(null);
              }

            @Override
            public void afterTextChanged(Editable s) {

            }
        });

        textInputLayout_Buyer_Email_Address.getEditText().addTextChangedListener(new TextWatcher() {
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
                textInputLayout_Buyer_Email_Address.setError(null);
              }

            @Override
            public void afterTextChanged(Editable s) {

            }
        });



        btnSubmit.setOnClickListener(new View.OnClickListener() {
            public void onClick(View view) {
                        if (!validateFirstName() || !validateLastName() || !validateNRC() | !validateMobileNumber() || !validateEmail()) {
                return;
            } else {

                String start_route_format = start_route.substring(0, 1).toUpperCase() + start_route.substring(1).toLowerCase();
                String end_route_format = end_route.substring(0, 1).toUpperCase() + end_route.substring(1).toLowerCase();
                AlertDialog.Builder builder = new AlertDialog.Builder(BusBuyTicketBuyerDetails.this);
                            builder.setCancelable(false);
                builder.setMessage("Confirm booking for " + buyer_first_name.trim() + " " + buyer_last_name.trim() + " from " + start_route_format + " to " + end_route_format + "?");
                builder.setPositiveButton("Yes",
                        new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {

                                device_serial = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
                                amount_due = Double.valueOf(bus_fare.toString());
                                buyer_id = buyer_nrc.trim();
                                buyer_mobile_number = "26" + buyer_mobile_number.trim();
                                route_name = getIntent().getStringExtra(KEY_ROUTE_NAME);
                                route_code = getIntent().getStringExtra(KEY_ROUTE_CODE);
                                departure_date = getIntent().getStringExtra(KEY_DEPARTURE_DATE);
                                departure_time = getIntent().getStringExtra(KEY_DEPARTURE_TIME);
                                bus_operator_name = getIntent().getStringExtra(KEY_BUS_OPERATOR_NAME);
                                bus_license_plate = getIntent().getStringExtra(KEY_BUS_LICENSE_PLATE);
                                bus_fare = getIntent().getStringExtra(KEY_BUS_FARE);
                                bus_schedule_id = getIntent().getStringExtra(KEY_BUS_SCHEDULE_ID);

                                transaction_date = SharedPrefManager.getInstance(BusBuyTicketBuyerDetails.this).getTranactionDate();

                                sendInformation
                                        (
                                                3,
                                                route_code,
                                                KEY_TRANSACTION_CHANNEL,
                                                "NRC",
                                                buyer_nrc,
                                                bus_schedule_id,
                                                departure_date,
                                                departure_time,
                                                seller_id,
                                                seller_first_name,
                                                seller_last_name,
                                                seller_mobile_number,
                                                buyer_id,
                                                buyer_first_name,
                                                buyer_last_name,
                                                buyer_mobile_number,
                                                buyer_email,
                                                amount_due,
                                                device_serial,
                                                transaction_date
                                        );
                                dialog.cancel();
                                progressBar.setVisibility(View.GONE);
                                AlertDialog.Builder builder = new AlertDialog.Builder(BusBuyTicketBuyerDetails.this);
                                builder.setCancelable(false);
                                builder.setMessage("Check your phone to approve the payment. An SMS will notify you of the transaction status.");
                                builder.setPositiveButton("Ok",
                                        new DialogInterface.OnClickListener() {
                                            public void onClick(DialogInterface dialog, int id) {
                                                Intent intent = new Intent(BusBuyTicketBuyerDetails.this, MainActivity.class);
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
        }
    });
}
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                //do whatever
                Intent intent = new Intent(BusBuyTicketBuyerDetails.this, BusSchedule.class);
                intent.putExtra(KEY_ROUTE_NAME, route_name);
                intent.putExtra(KEY_TRAVEL_DATE, travel_date);
                intent.putExtra(KEY_START_ROUTE, start_route);
                intent.putExtra(KEY_END_ROUTE, end_route);
             //   intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
                finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }


    public void onBackPressed() {
        // Toast.makeText(getApplication(),"Use the in app controls to navigate.",Toast.LENGTH_SHORT).show();
        Intent intent = new Intent(BusBuyTicketBuyerDetails.this, BusSchedule.class);
        intent.putExtra(KEY_ROUTE_NAME, route_name);
        intent.putExtra(KEY_TRAVEL_DATE, travel_date);
        intent.putExtra(KEY_START_ROUTE, start_route);
        intent.putExtra(KEY_END_ROUTE, end_route);
       // intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
    }



    public void sendInformation
            (
                    int transaction_type_id,
                    String route_code,
                    String transaction_channel,
                    String id_type,
                    String passenger_id,
                    String bus_schedule_id,
                    String travel_date,
                    String travel_time,
                    String seller_id,
                    String seller_first_name,
                    String seller_last_name,
                    String seller_mobile_number,
                    String buyer_id,
                    String buyer_first_name,
                    String buyer_last_name,
                    String buyer_mobile_number,
                    String buyer_email,
                    double amount_due,
                    String device_serial,
                    String transaction_Date
            ) {
        progressBar.setVisibility(View.VISIBLE);
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
            jsonObject.put("buyer_email", buyer_email);
            jsonObject.put("amount_due", amount_due);
            jsonObject.put("device_serial",device_serial);
            jsonObject.put("transaction_date",transaction_Date);
            jsonObject.put( "route_code",route_code);
            jsonObject.put( "transaction_channel",KEY_TRANSACTION_CHANNEL);
            jsonObject.put( "bus_schedule_id",bus_schedule_id);
            jsonObject.put( "id_type", id_type);
            jsonObject.put( "passenger_id",passenger_id);
            jsonObject.put( "travel_date",travel_date);
            jsonObject.put("travel_time",travel_time);
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
                        progressDialog.dismiss();
                        Log.d("Response", response.toString());
                        progressBar.setVisibility(View.GONE);

                        try {

                            // DialogBox.mLovelyStandardDialog(BusBuyTicketBuyerDetails.this, response.getString(KEY_MESSAGE)); //response.getString(KEY_MESSAGE)
                            // Toast.makeText(getApplicationContext(),response.getString(KEY_MESSAGE), Toast.LENGTH_LONG).show();
                            Log.d("Response", response.getString(KEY_MESSAGE));

//                            progressBar.setVisibility(View.GONE);
//                            AlertDialog.Builder builder = new AlertDialog.Builder(BusBuyTicketBuyerDetails.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("Check your phone to approve the payment. An SMS will notify you of the transaction status.");
//                            builder.setPositiveButton("Ok",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//                                            Intent intent = new Intent(BusBuyTicketBuyerDetails.this, MainActivity.class);
//                                            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
//                                            startActivity(intent);
//                                            finish();
//                                        }
//                                    });
//                            builder.create().show();

                        } catch (JSONException e) {
                            e.printStackTrace();


                            progressBar.setVisibility(View.GONE);
//                            AlertDialog.Builder builder = new AlertDialog.Builder(BusBuyTicketBuyerDetails.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("An error occurred while processing a bus ticket, kindly check your internet connection and try again.");
//                            builder.setPositiveButton("Ok",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//                                            //Intent intent = new Intent(BusBuyTicketBuyerDetailsE.this,BusSearch.class);
//                                            // intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
//                                            //startActivity(intent);
//                                            // finish();
//                                            dialog.cancel();
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

//                progressBar.setVisibility(View.GONE);
//                AlertDialog.Builder builder = new AlertDialog.Builder(BusBuyTicketBuyerDetails.this);
//                builder.setCancelable(false);
//                builder.setMessage("Connection failure, kindly check your internet connection and try again.");
//                builder.setPositiveButton("Ok",
//                        new DialogInterface.OnClickListener() {
//                            public void onClick(DialogInterface dialog, int id) {
//                                //Intent intent = new Intent(BusBuyTicketBuyerDetailsE.this, BusScheduleE.class);
//                                // intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
//                                //startActivity(intent);
//                                // finish();
//                                dialog.cancel();
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

        // add it to the RequestQueue
        //       queue.add(jsonObjectRequest);

        jsonObjectRequest.setRetryPolicy(new DefaultRetryPolicy(KEY_VOLLEY_SOCKET_TIMEOUT_MS,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonObjectRequest);
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

            textInputLayout_Buyer_Mobile_Number.setError(null);
            textInputLayout_Buyer_Mobile_Number.setErrorEnabled(false);
            buyer_mobile_number = textInputLayout_Buyer_Mobile_Number.getEditText().getText().toString().trim();

            if (buyer_mobile_number.length() == 0)
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


    private boolean validateFirstName() {
        buyer_first_name = textInputLayout_Buyer_First_Name.getEditText().getText().toString().trim();
        if (buyer_first_name.isEmpty() | buyer_first_name.length() < 2) {
            textInputLayout_Buyer_First_Name.setErrorEnabled(true);
            textInputLayout_Buyer_First_Name.setError("Enter at least two letter for the first name.");
            textInputLayout_Buyer_First_Name.requestFocus();
            return false;

        } else {
            textInputLayout_Buyer_First_Name.setError(null);
            return true;
        }
    }

    private boolean validateLastName() {
        buyer_last_name = textInputLayout_Buyer_Last_Name.getEditText().getText().toString().trim();
        if (buyer_last_name.isEmpty() | buyer_last_name.length() < 2) {
            textInputLayout_Buyer_Last_Name.setErrorEnabled(true);
            textInputLayout_Buyer_Last_Name.setError("Enter at least two letter for the last name.");
            textInputLayout_Buyer_Last_Name.requestFocus();
            return false;

        } else {
            textInputLayout_Buyer_First_Name.setError(null);
            return true;
        }
    }

    private boolean validateNRC() {
        buyer_nrc = textInputLayout_Buyer_NRC.getEditText().getText().toString().trim();
        if (buyer_nrc.isEmpty() | buyer_nrc.length() < 5) {
            textInputLayout_Buyer_NRC.setErrorEnabled(true);
            textInputLayout_Buyer_NRC.setError("Enter at least five characters for the ID.");
            textInputLayout_Buyer_NRC.requestFocus();
            return false;

        } else {
            textInputLayout_Buyer_NRC.setError(null);
            return true;
        }
    }

    private boolean validateMobileNumber() {
        buyer_mobile_number = textInputLayout_Buyer_Mobile_Number.getEditText().getText().toString().trim();
        if (buyer_mobile_number.isEmpty() | buyer_mobile_number.length() < 10) {
            textInputLayout_Buyer_Mobile_Number.setErrorEnabled(true);
            textInputLayout_Buyer_Mobile_Number.setError("Enter a 10 digit mobile number (0xxxxxxxxx).");
            textInputLayout_Buyer_Mobile_Number.requestFocus();
            return false;

        } else {
            textInputLayout_Buyer_Mobile_Number.setError(null);
            return true;
        }
    }

    private boolean validateEmail() {
        buyer_email = textInputLayout_Buyer_Email_Address.getEditText().getText().toString().trim();
        if (!buyer_email.isEmpty()) {
            if (!Patterns.EMAIL_ADDRESS.matcher(buyer_email).matches()) {
                textInputLayout_Buyer_Email_Address.setError("Enter a valid email address");
                return false;
            } else {
                textInputLayout_Buyer_Email_Address.setError(null);
                return true;
            }
        } else {
            textInputLayout_Buyer_Email_Address.setError(null);
            return true;
        }
    }

}
