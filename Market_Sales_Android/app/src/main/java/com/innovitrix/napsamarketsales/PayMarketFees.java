package com.innovitrix.napsamarketsales;

import android.content.DialogInterface;
import android.content.Intent;
import android.provider.Settings;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;

import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
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
import com.innovitrix.napsamarketsales.dialog.DialogBox;
import com.innovitrix.napsamarketsales.models.MarketStand;
import com.innovitrix.napsamarketsales.models.MarketStandAdapter;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRANSACTION_CHANNEL;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_VOLLEY_SOCKET_TIMEOUT_MS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_MARKETEER_KYC_ALL;

import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_MARKETER_KYC_SINGLE;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_MARKET_FEE;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_SELLER_MOBILE_NUMBER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_TRANSACTIONS;

public class PayMarketFees extends AppCompatActivity implements MarketStandAdapter.OnMarketStandListener {
    private static final String TAG = "PayMarketFees";
    private ProgressBar progressBar;

    // ui components
    private RecyclerView recyclerView;

    // vars
    private ArrayList<MarketStand> mMarketStands = new ArrayList<>();
    private MarketStandAdapter mMarketStandAdapter;

    private RequestQueue queue;

    private String seller_id;
    private String seller_first_name;
    private String seller_last_name;
    private String seller_mobile_number;
    private String buyer_id;
    private String buyer_first_name;
    private String buyer_last_name;
    private String buyer_mobile_number;

    private String device_serial;
    private double amount;
    private long backPressedTime;
    private Toast backToast;
    TextView textViewUsername;
    TextView textViewDate;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_pay_market_fees);
        getSupportActionBar().setSubtitle("pay market fees");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        queue = Volley.newRequestQueue(this);
        recyclerView = findViewById(R.id.recylerview_Market_Stand);
        progressBar = (ProgressBar) findViewById(R.id.progressBar);

        textViewUsername = (TextView)findViewById(R.id.textViewUsername);
        textViewUsername.setText("Logged in as "+SharedPrefManager.getInstance(PayMarketFees.this).getUser().getFirstname()+ " "+SharedPrefManager.getInstance(PayMarketFees.this).getUser().getLastname());
        textViewDate = (TextView)findViewById(R.id.textViewDate);
        textViewDate.setText(SharedPrefManager.getInstance(PayMarketFees.this).getTranactionDate2());


        seller_id = SharedPrefManager.getInstance(PayMarketFees.this).getUser().getTrader_id();
        seller_mobile_number = SharedPrefManager.getInstance(PayMarketFees.this).getUser().getMobile_number();
        seller_first_name = SharedPrefManager.getInstance(PayMarketFees.this).getUser().getFirstname();
        seller_last_name = SharedPrefManager.getInstance(PayMarketFees.this).getUser().getLastname();
        progressBar.setVisibility(View.VISIBLE);

        initRecyclerView();
        //fetchStands();
        fetch_Stands();
        //insertFakeNotes();


    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                //do whatever
                Intent intent = new Intent(PayMarketFees.this, MainActivity.class);
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
        Intent intent = new Intent(PayMarketFees.this, MainActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
    }
    private void insertFakeNotes() {
        for (int i = 0; i < 5; i++) {
            MarketStand note = new MarketStand(String.valueOf(i), Double.valueOf(i));
            mMarketStands.add(note);
        }

        mMarketStandAdapter.notifyDataSetChanged();
    }

    private void initRecyclerView() {
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(this);
        recyclerView.setLayoutManager(linearLayoutManager);
        //VerticalSpacingItemDecorator itemDecorator = new VerticalSpacingItemDecorator(10);
        //recyclerView.addItemDecoration(itemDecorator);
        mMarketStandAdapter = new MarketStandAdapter(mMarketStands, this);
        recyclerView.setAdapter(mMarketStandAdapter);

    }


    @Override
    public void onMarketStandClick(final int position) {
        Log.d(TAG, "onMarketStandClick: Clicked # " + position);

        AlertDialog.Builder builder = new AlertDialog.Builder(PayMarketFees.this);
        builder.setCancelable(false);
        builder.setMessage("Confirm payment of ZMW " + String.valueOf(mMarketStands.get(position).getStand_price()) + " market fees for stand number " + mMarketStands.get(position).getStand_number() + "?");

        builder.setPositiveButton("Yes",
                new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        MarketStand ms = mMarketStands.get(position);
                        device_serial = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
                        sendInformation(4, ms.getStand_number(), seller_id, seller_first_name, seller_last_name, seller_mobile_number, ms.getStand_price(), device_serial);
                        progressBar.setVisibility(View.GONE);
//                        Toast.makeText(getApplicationContext(),"Check your phone to approve the payment. An SMS will notify you of the transaction status.",Toast.LENGTH_LONG).show();
//                        Intent intent = new Intent(PayMarketFees.this, MainActivity.class);
//                        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
//                        startActivity(intent);
//                        finish();
                        dialog.cancel();
                        AlertDialog.Builder builder = new AlertDialog.Builder(PayMarketFees.this);
                        builder.setCancelable(false);
                        builder.setMessage("Check your phone that has mobile number "+  buyer_mobile_number+" to approve the payment. An SMS will notify you of the transaction status.");
                        builder.setPositiveButton("Ok",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                        Intent intent = new Intent(PayMarketFees.this, MainActivity.class);
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


    public void fetch_Stands() {
        progressBar.setVisibility(View.VISIBLE);

      /*  JSONObject jsonAuthObject = new JSONObject();
      try {
            jsonAuthObject.put("username", "admin");
            jsonAuthObject.put("service_token", "JJ8DJ7S66DMA5");
        } catch (JSONException e) {
            e.printStackTrace();
        }


        //PAYLOAD
        JSONObject jsonPayloadObject = new JSONObject();
        try {
             jsonPayloadObject.put("start_route", start_Route);
             jsonPayloadObject.put("end_route", end_Route);
              jsonPayloadObject.put("date", travel_Date);//TODO change to actual date
           // jsonPayloadObject.put("date", "27/01/2020");
        } catch (JSONException e) {
            e.printStackTrace();
        }


        ///prepare your JSONObject which you want to send in your web service request
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("auth", jsonAuthObject); //TODO once a probase finalises
            jsonObject.put("payload", jsonPayloadObject);

        } catch (JSONException e) {
            e.printStackTrace();
        }
*/
        // prepare the Request


        // prepare the Request


        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, URL_MARKET_FEE
                + URL_CHAR_QUESTION +
                URL_PARAM_SELLER_MOBILE_NUMBER
                + seller_mobile_number,
                null,

                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {

                        //Do stuff here
                        // display response

                        Log.d("Response", response.toString());
                        progressBar.setVisibility(View.GONE);

                        try {
                            //converting response to json object
                            JSONObject obj = new JSONObject(String.valueOf(response));


                            //Check if the object has the key.
                            //  if (obj.getJSONObject("found").has("QUERY")) {
                            boolean found = obj.getBoolean("found");
                            if (found==true) {
                                //  JSONObject KYC = obj.getJSONObject("response").getJSONObject("success");

                                JSONArray StandArray = obj.getJSONArray("market_fee");

                                if (StandArray.length() > 0) {
                                    //   DialogBox.mLovelyStandardDialog(PayMarketFees.this, String.valueOf(KYCArray.length()));

                                    for (int i = 0; i < StandArray.length(); i++) {

                                        JSONObject currentStand = StandArray.getJSONObject(i);

                                        // DialogBox.mLovelyStandardDialog(PayMarketFees.this, currentStand.getString("shop_number") + " " + String.valueOf(StandArray.length()));

                                        MarketStand ms = new MarketStand
                                                (
                                                        currentStand.getString("stand_number"),
                                                        currentStand.getDouble("stand_price")
                                                );

                                        mMarketStands.add(ms);
                                    }

                                    mMarketStandAdapter.notifyDataSetChanged();


                                } else {
//
                                    progressBar.setVisibility(View.GONE);

                                    AlertDialog.Builder builder = new AlertDialog.Builder(PayMarketFees.this);
                                    builder.setCancelable(false);
                                    builder.setMessage("You currently have no fees to pay today.");
                                    builder.setPositiveButton("Ok",
                                            new DialogInterface.OnClickListener() {
                                                public void onClick(DialogInterface dialog, int id) {

                                                    Intent intent = new Intent(PayMarketFees.this, MainActivity.class);
                                                    intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                                                    startActivity(intent);
                                                    finish();
                                                }
                                            });
                                    builder.create().show();
                                }}
                            else {

                                progressBar.setVisibility(View.GONE);


                                AlertDialog.Builder builder = new AlertDialog.Builder(PayMarketFees.this);
                                builder.setCancelable(false);
                                builder.setMessage("You currently have no fees to pay today.");
                                builder.setPositiveButton("Ok",
                                        new DialogInterface.OnClickListener() {
                                            public void onClick(DialogInterface dialog, int id) {

                                                Intent intent = new Intent(PayMarketFees.this, MainActivity.class);
                                                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                                                startActivity(intent);
                                                finish();
                                            }
                                        });
                                builder.create().show();


                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                            progressBar.setVisibility(View.GONE);
//                            AlertDialog.Builder builder = new AlertDialog.Builder(PayMarketFees.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("An error occured while trying to retrieve market stands, kindly check your internet connection and try again.");
//                            builder.setPositiveButton("Ok",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//
//                                            Intent intent = new Intent(PayMarketFees.this, MainActivity.class);
//                                            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
//                                            startActivity(intent);
//                                            finish();
//                                        }
//                                    });
//                            builder.create().show();

                        }

                    }

                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error)  {
                        Log.d("Error.Response", error.toString());

                        progressBar.setVisibility(View.GONE);

                        AlertDialog.Builder builder = new AlertDialog.Builder(PayMarketFees.this);
                        builder.setCancelable(false);
                        builder.setMessage("Connection failure, kindly check your internet connection and try again and try again.");
                        builder.setPositiveButton("Ok",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {

                                        Intent intent = new Intent(PayMarketFees.this, MainActivity.class);
                                        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                                        startActivity(intent);
                                        finish();
                                    }
                                });
                        builder.create().show();


                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                // params.put("username", trader_id);
                //params.put("email", firstname);
                // params.put("password", lastname);
                params.put("mobile_number", "ll");
                return params;
            }
        };

        jsonObjectRequest.setRetryPolicy(new DefaultRetryPolicy(KEY_VOLLEY_SOCKET_TIMEOUT_MS,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonObjectRequest);


    }

    public void sendInformation
            (
                    int transaction_type_id,
                    String stand_number,
                    String buyer_id,
                    String buyer_first_name,
                    String buyer_last_name,
                    String buyer_mobile_number,
                    double amount_due,
                    String device_serial
            ) {

        //progressDialog.show();

        ///prepare your JSONObject which you want to send in your web service request
        String transaction_date = SharedPrefManager.getInstance(PayMarketFees.this).getTranactionDate();

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
            jsonObject.put("transaction_date",transaction_date);
            jsonObject.put( "route_code",null);
            jsonObject.put( "transaction_channel",KEY_TRANSACTION_CHANNEL);
            jsonObject.put( "bus_schedule_id",null);
            jsonObject.put( "id_type", null);
            jsonObject.put( "passenger_id",null);
            jsonObject.put( "travel_date",null);
            jsonObject.put("travel_time",null);
            jsonObject.put("stand_number", stand_number);

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
                        //  progressDialog.dismiss();
                        Log.d("Response", response.toString());
                        progressBar.setVisibility(View.GONE);

                        try {
                            // etAmount.requestFocus();
                            Log.d("Response", response.getString(KEY_MESSAGE));
//                            AlertDialog.Builder builder = new AlertDialog.Builder(PayMarketFees.this);
//
//                            builder.setMessage("Check your phone to approve the payment. An SMS will notify you of the transaction status.");
//                            builder.setPositiveButton("Ok",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//                                            Intent intent = new Intent(PayMarketFees.this, MainActivity.class);
//                                            startActivity(intent);
//                                        }
//                                    });
//                            builder.create().show();
//                            //DialogBox.mLovelyStandardDialog(PayMarketFees.this, response.getString(KEY_MESSAGE));
//                            //startActivity(new Intent( MakeSell.this,MainActivity.class));

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener() {


            @Override
            public void onErrorResponse(VolleyError error) {
                //Handle Errors here
                //progressDialog.dismiss();
                Log.d("Error.Response", error.toString());
               // Log.d("Error.Response", error.getMessage());

//                AlertDialog.Builder builder = new AlertDialog.Builder(PayMarketFees.this);
//                builder.setMessage("Connection failure, kindly check your internet connection.");
//                builder.setPositiveButton("Ok",
//                        new DialogInterface.OnClickListener() {
//                            public void onClick(DialogInterface dialog, int id) {
//                                Intent intent = new Intent(PayMarketFees.this, MainActivity.class);
//                                startActivity(intent);
//                            }
//                        });
//                builder.create().show();
//                //DialogBox.mLovelyStandardDialog(PayMarketFees.this, response.getString(KEY_MESSAGE));
//                //startActivity(new Intent( MakeSell.this,MainActivity.class));
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
       // queue.add(jsonObjectRequest);
    }


}