package com.innovitrix.napsamarketsales;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Build;
import android.provider.Settings;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
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
import android.view.Menu;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.dialog.DialogBox;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_BUS_FARE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_BUS_LICENSE_PLATE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_BUS_OPERATOR_NAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_DEPARTURE_DATE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_DEPARTURE_TIME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_CODE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_NAME;

import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_TRANSACTIONS;

public class BusBuyTicketBuyerDetails extends AppCompatActivity {

    @TargetApi(Build.VERSION_CODES.O)
    ProgressDialog progressDialog;
    RequestQueue queue;
    EditText first_name;
    EditText  last_name;
    EditText  NRC;
    EditText mobile_number;
    EditText email;
    TextView bus_Schedule_Details;
    String firstname;
    String lastname;
    String  nrc;
    String mobilenumber;
    String route_Name;
    String departure_Date;
    String departure_Time;
    String bus_Operator_Name;
    String bus_license_plate;
    String bus_Fare;
    String route_Code;
    Button btnSubmit;

    String emailPattern = "[a-zA-Z0-9._-]+@[a-z]+\\.+[a-z]+";
    int mobile_number_char;
    String blockCharacterSet = "123456789";
    String  device_serial;
    String buyer_id;
    String  buyer_first_name;
    String  buyer_last_name;
    String  buyer_mobile_number;
    String buyer_email;
    Double amount_due;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_bus_buy_ticket_buyer_details);
        getSupportActionBar().setSubtitle("Buy Bus Ticket");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        progressDialog = new ProgressDialog(BusBuyTicketBuyerDetails.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);


        first_name = (EditText) findViewById(R.id.editTextFirstName);
        last_name = (EditText ) findViewById(R.id.editTextLastName);
        NRC = (EditText ) findViewById(R.id.editTextNRC);
        mobile_number = (EditText ) findViewById(R.id.editTextMobileNumber);
        email= (EditText ) findViewById(R.id.editTextEmail);
        first_name.requestFocus();

        route_Name = getIntent().getStringExtra(KEY_ROUTE_NAME);
        route_Code = getIntent().getStringExtra(KEY_ROUTE_CODE);
        departure_Date = getIntent().getStringExtra(KEY_DEPARTURE_DATE);
        departure_Time = getIntent().getStringExtra(KEY_DEPARTURE_TIME);
        bus_Operator_Name =getIntent().getStringExtra(KEY_BUS_OPERATOR_NAME);
        bus_license_plate =getIntent().getStringExtra(KEY_BUS_LICENSE_PLATE);
        bus_Fare =getIntent().getStringExtra(KEY_BUS_FARE);


        btnSubmit = (Button) findViewById(R.id.buttonSubmit);

        InputFilter string_filter = new InputFilter()
        {
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
        InputFilter alphaNumeric_filter = new InputFilter()
        {
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


    first_name.setFilters(new InputFilter[] { string_filter });
    last_name.setFilters(new InputFilter[] { string_filter });
    NRC.setFilters(new InputFilter[] {alphaNumeric_filter });


    mobile_number.addTextChangedListener(new BusBuyTicketBuyerDetails.PhoneNumberTextWatcher());
    mobile_number.setFilters(new InputFilter[]{new BusBuyTicketBuyerDetails.PhoneNumberFilter(), new InputFilter.LengthFilter(10)});
    first_name.requestFocus();



          btnSubmit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                firstname = first_name.getText().toString().trim();
                lastname = last_name.getText().toString().trim();
                nrc = NRC.getText().toString().trim();
                mobilenumber = mobile_number.getText().toString().trim();
                buyer_email = email.getText().toString().trim();

                //validating inputs
                if (TextUtils.isEmpty(firstname)) {
                    first_name.setError("Please enter the first name");
                    first_name.requestFocus();
                    return;
                }
                if (TextUtils.isEmpty(lastname)) {
                    last_name.setError("Please enter the last name");
                    last_name.requestFocus();
                    return;
                }

                if (mobile_number_char != 10) {
                   mobile_number.setError("Enter a 10 digit mobile number (0xxxxxxxxx)");
                    mobile_number.requestFocus();
                    return;
                }
                if (!TextUtils.isEmpty(buyer_email)) {


                    if (email.getText().toString().trim().matches(emailPattern)) {

                    } else {
                        email.setError("Enter a valid email address.");
                        email.requestFocus();
                        return;
                    }
                }

                AlertDialog.Builder builder = new AlertDialog.Builder(BusBuyTicketBuyerDetails.this);
                builder.setMessage("Confirm booking?");
                builder.setPositiveButton("Yes",
                        new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {

                                device_serial = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
                                amount_due = Double.valueOf(bus_Fare.toString());
                                buyer_first_name= first_name.getText().toString().trim();
                                buyer_last_name= last_name.getText().toString().trim();
                                buyer_id = NRC.getText().toString().trim();
                                buyer_mobile_number = "26"+ mobile_number.getText().toString().trim();
                                buyer_email = email.getText().toString().trim();
                                sendInformation
                                        (
                                            3,
                                            null,
                                            null,
                                            null,
                                            null,
                                            null,
                                            buyer_first_name,
                                            buyer_last_name,
                                            buyer_mobile_number,
                                            buyer_email,
                                            amount_due,
                                            device_serial,
                                            Calendar.getInstance().getTime(),
                                            route_Code,
                                            "API",
                                            "NRC",
                                            buyer_id,
                                            departure_Date
                                         );

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
            }});

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
                    String buyer_mobile_number,
                    String buyer_email,
                    double amount_due,
                    String device_serial,
                    Date transaction_Date,
                    String route_code,
                    String transaction_channel,
                    String id_type,
                    String passenger_id,
                    String travel_date
            )
    {

       // progressDialog.show();

        ///prepare your JSONObject which you want to send in your web service request
// Calendar.getInstance().getTime()
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
            jsonObject.put( "transaction_channel",transaction_channel);
            jsonObject.put( "id_type", id_type);
            jsonObject.put( "passenger_id",passenger_id);
            jsonObject.put( "travel_date",travel_date);
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

                           // DialogBox.mLovelyStandardDialog(BusBuyTicketBuyerDetails.this, response.getString(KEY_MESSAGE)); //response.getString(KEY_MESSAGE)
                            Toast.makeText(getApplicationContext(),response.getString(KEY_MESSAGE), Toast.LENGTH_LONG).show();
                            first_name.setText("");
                            last_name.setText("");
                            NRC.setText("");
                            mobile_number.setText("");
                            email.setText("");

                            startActivity(new Intent(BusBuyTicketBuyerDetails.this, BusSearch.class));

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

                DialogBox.mLovelyStandardDialog(BusBuyTicketBuyerDetails.this, "Server unreachable.");

                // startActivity(new Intent( BuyFromTrader.this,MainActivity.class));
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

            mobile_number_char =mobile_number.getText().toString().length();
            if (mobile_number.getText().toString().length() == 0)
                blockCharacterSet = "123456789";

            else
                blockCharacterSet = "";
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
    public void stringInputFilter(){

    }

}
