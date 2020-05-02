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
import android.widget.Toast;

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

import static android.text.InputType.TYPE_NULL;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_FIRSTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LASTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_NRC;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRADER_ID;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_MARKETER_KYC;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_MOBILE_NUMBER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_TRANSACTIONS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_USERS;


public class BuyFromTrader extends AppCompatActivity {
    @TargetApi(Build.VERSION_CODES.O)
    private ProgressDialog progressDialog;
    RequestQueue queue;
    ConstraintLayout layoutSupplierFind;
    ConstraintLayout layoutSupplier;
    Button btnFindSupplier;
    Button btnBack;
    Button btnPay;
    Button btnCancel;
    EditText editText_Seller_First_Name,editText_Seller_Last_Name,editText_Seller_Mobile_Number, editText_Amount;


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
    String  buyer_first_name;
    String  buyer_last_name;
    String buyer_mobile_number;
    Double amount_due;
    String device_serial;

    com.innovitrix.napsamarketsales.models.User mUser;
    private static final String TAG = BuyFromTrader.class.getName();
    Handler handler = new Handler();

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_buy_from_trader);
       getSupportActionBar().setSubtitle("Make an order");
       // setDate();
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        progressDialog = new ProgressDialog(BuyFromTrader.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);

        editText_Seller_First_Name = (EditText) findViewById(R.id.editTextSellerFirstName);
        editText_Seller_Last_Name = (EditText) findViewById(R.id.editTextSellerLastName);
        editText_Seller_Mobile_Number = (EditText) findViewById(R.id.editTextSellerMobileNumber);
        editText_Amount = (EditText) findViewById(R.id.editTextAmount);

        buyer_id=  SharedPrefManager.getInstance(BuyFromTrader.this).getUser().getTrader_id();
        buyer_mobile_number = SharedPrefManager.getInstance(BuyFromTrader.this).getUser().getMobile_number();
        buyer_first_name = SharedPrefManager.getInstance(BuyFromTrader.this).getUser().getFirstname();
        buyer_last_name = SharedPrefManager.getInstance(BuyFromTrader.this).getUser().getLastname();

        seller_first_name = getIntent().getStringExtra(KEY_FIRSTNAME);
        seller_last_name = getIntent().getStringExtra(KEY_LASTNAME);
        seller_id=  getIntent().getStringExtra(KEY_TRADER_ID);
        seller_mobile_number = getIntent().getStringExtra(KEY_MOBILE);

        editText_Amount.requestFocus();

        disableEditText();

        btnPay = findViewById(R.id.btnPay);

        btnPay.setEnabled(true);

        mobile_number_char = 0;
        userObjectLength = 0;


        btnPay.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                device_serial = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);


                amount_due = Double.valueOf(editText_Amount.getText().toString());

                if (amount_due==0) {
                    editText_Amount.setError("Please  enter valid amount");
                    editText_Amount.requestFocus();
                    return;
                }
                AlertDialog.Builder builder = new AlertDialog.Builder(BuyFromTrader.this);
                builder.setMessage("Confirm order?");
                builder.setPositiveButton("Yes",
                        new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                  sendInformation(2, seller_id, seller_first_name,seller_last_name,seller_mobile_number, buyer_id, buyer_first_name, buyer_last_name,    buyer_mobile_number, device_serial,amount_due);

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

public void disableEditText()
{

    editText_Seller_First_Name.setFocusable(false);
    editText_Seller_Last_Name.setFocusable(false);
    editText_Seller_Mobile_Number.setFocusable(false);

    editText_Seller_First_Name.setFocusableInTouchMode(false);
    editText_Seller_Last_Name.setFocusableInTouchMode(false);
    editText_Seller_Mobile_Number.setFocusableInTouchMode(false);

//    editText_Seller_First_Name.setInputType(TYPE_NULL);
//    editText_Seller_Last_Name.setInputType(TYPE_NULL);
//    editText_Seller_Mobile_Number.setInputType(TYPE_NULL);


    editText_Seller_First_Name.setText(seller_first_name);
    editText_Seller_Last_Name.setText(seller_last_name);
    editText_Seller_Mobile_Number.setText(seller_mobile_number);

    editText_Amount.requestFocus();
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

                            Toast.makeText(getApplicationContext(),response.getString(KEY_MESSAGE), Toast.LENGTH_SHORT).show();

                           // DialogBox.mLovelyStandardDialog(BuyFromTrader.this, response.getString(KEY_MESSAGE)); //response.getString(KEY_MESSAGE)
                            editText_Seller_First_Name.setText("");
                            editText_Seller_Last_Name.setText("");
                            editText_Seller_Mobile_Number.setText("");
                            editText_Amount.setText("");
                            startActivity(new Intent(BuyFromTrader.this, FindTrader.class));
                            //  startActivity(new Intent( BuyFromTrader.this,MainActivity.class));

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
                layoutSupplier.setVisibility(View.VISIBLE);
                layoutSupplierFind.setVisibility(View.GONE);
                    DialogBox.mLovelyStandardDialog(BuyFromTrader.this, error.toString());

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

}
