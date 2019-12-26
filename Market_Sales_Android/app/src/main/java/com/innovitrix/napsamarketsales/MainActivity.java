package com.innovitrix.napsamarketsales;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Build;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.CardView;
import android.support.v7.widget.LinearLayoutManager;
import android.text.InputFilter;
import android.text.Spanned;
import android.util.Log;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.model.MenuAdapter;
import com.innovitrix.napsamarketsales.model.MenuData;
import com.innovitrix.napsamarketsales.models.RoutePlannedAdapter;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;

//Recyclerview
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.Gravity;
import android.view.View;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.List;

import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHECK_BALANCE;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_ENDPOINT_TEST;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_MOBILE_NUMBER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_USER_ID;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_USERS;

public class MainActivity extends AppCompatActivity implements View.OnClickListener {


    private RecyclerView recyclerView;
    private MenuAdapter menuAdapter;
    private List<MenuData> menuDataList;

    @TargetApi(Build.VERSION_CODES.O)
    private ProgressDialog progressDialog;
    RequestQueue queue;


    private CardView cardViewMenuSaleToBuyer, cardViewMenuBuyFromTrader,cardViewCheckBalance,cardViewChangePin,cardViewBuBusTicket;
    TextView textViewBalance;
    String mobile_number;
    String stringAmount;
    int userObjectLength ;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        progressDialog = new ProgressDialog(MainActivity.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);
        //textViewBalance = findViewById(R.id.tvBalance);
        setDate();
        fetchTrader();
        menuDataList = new ArrayList<>();

//        menuDataList.add(new MenuData(1, "Make A Sale", R.drawable.ic_local_shipping_black_24dp));
//        menuDataList.add(new MenuData(2, "Make An order",R.drawable.ic_local_shipping_black_24dp));
//        menuDataList.add(new MenuData(3, "Seller Bus Ticket", R.drawable.ic_directions_bus_black_24dp ));
//        menuDataList.add(new MenuData(4, "Check Balance", R.drawable.ic_account_balance_black_24dp));
//        menuDataList.add(new MenuData(5, "View Transactions",R.drawable.ic_account_balance_black_24dp));
//        menuDataList.add(new MenuData(6, "Change pin",R.drawable.ic_lock_open_black_24dp));

        menuDataList.add(new MenuData(1, "Make A Sale", R.drawable.ic_local_shipping_black_24dp,R.drawable.circle_bakcground_sales));
        menuDataList.add(new MenuData(2, "Make An Order",R.drawable.ic_local_shipping_black_24dp,R.drawable.circle_background_order));
        //menuDataList.add(new MenuData(3, "Seller Bus Ticket", R.drawable.ic_directions_bus_black_24dp,R.drawable.circle_bakcground_sales ));
//        menuDataList.add(new MenuData(5, "View Transactions",R.drawable.ic_account_balance_black_24dp,R.drawable.circle_bakcground_sales));
//        menuDataList.add(new MenuData(6, "Change pin",R.drawable.ic_lock_open_black_24dp,R.drawable.circle_background_order));
        menuDataList.add(new MenuData(3, "Check Balance",R.drawable.ic_attach_money_black_24dp,R.drawable.circle_background_balance));
        menuDataList.add(new MenuData(4, "Logout", R.drawable.ic_power_settings_new_black_24dp,R.drawable.circle_bakcground_sales ));


        recyclerView = (RecyclerView) findViewById(R.id.recyclerViewMenu);
        menuAdapter = new MenuAdapter(this,menuDataList);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
        recyclerView.setAdapter(menuAdapter);
//
//        //Define cards
//        cardViewMenuSaleToBuyer = findViewById(R.id.cardViewMenuSaleToBuyer);
//        cardViewMenuBuyFromTrader = findViewById(R.id.cardViewMenuBuyFromTrader);
//        cardViewCheckBalance = findViewById(R.id.cardViewMenuCheckBalance);
//        //cardViewChangePin = findViewById(R.id.cardViewMenuChenagePin);
//        cardViewBuBusTicket =findViewById(R.id.cardViewBuBusTicket);
//
//
//        cardViewMenuSaleToBuyer.setOnClickListener(this);
//        cardViewMenuBuyFromTrader.setOnClickListener(this);
//        cardViewCheckBalance.setOnClickListener(this);
////        cardViewChangePin.setOnClickListener(this);
//        cardViewBuBusTicket.setOnClickListener(this);




        //  fetchInformation();
        //  sendInformation();
        //  updateInformation();




    }

    @Override
    protected void onResume() {
        super.onResume();

        fetchTrader();
    }

    public void fetchTrader() {
        //userObjectLength =0;
        progressDialog.show();
        mobile_number = SharedPrefManager.getInstance(MainActivity.this).getCustomer().getMobile_number();
       // mobile_number =  mobile_number.substring(2);

         JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, URL_CHECK_BALANCE +
                URL_CHAR_QUESTION +
                URL_PARAM_MOBILE_NUMBER + mobile_number
                //URL_CHAR_AMPERSAND +
                //URL_PARAM_USER_ID + 1
                , null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {

                        // display response
                        progressDialog.dismiss();

                        Log.d("fetchTrader()", response.toString());
                        try {


                            JSONObject currentUser = response.getJSONObject("user");
                        //    userObjectLength = currentUser.length();
                            com.innovitrix.napsamarketsales.models.User mUser = new com.innovitrix.napsamarketsales.models.User(
                                    currentUser.getInt("trader_id"),
                                    currentUser.getDouble("token_balance")

                            );

                            //either display using listView or recyclerView
                            //store in dp temporal DB
                            //mDatabase.createRoutes(mRoute);

                            // mUser.getFirstname();//retrieving firstname
                            //mUser.getLastname();//retrieving lastname
                           // stringAmount = "K"+ String.valueOf(mUser.getBalance());
                         //   textViewBalance.setText(stringAmount);

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        //Handle Errors here
                        progressDialog.dismiss();
                        Log.d("Error.Response", error.toString());
                   //     Log.d("Error.Response", error.getMessage());
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

    public void fetchInformation() {

        progressDialog.show();

        // prepare the Request
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, URL_ENDPOINT_TEST +
                URL_CHAR_QUESTION +
                URL_PARAM_USER_ID + 1
                //URL_CHAR_AMPERSAND +
                //URL_PARAM_USER_ID + 1
                , null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        // display response
                        progressDialog.dismiss();
                        Log.d("Response", response.toString());
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        //Handle Errors here
                        progressDialog.dismiss();
                        Log.d("Error.Response", error.toString());
                        Log.d("Error.Response", error.getMessage());
                    }
                })
        {


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

    public void sendInformation() {

        progressDialog.show();

        ///prepare your JSONObject which you want to send in your web service request
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("firstname","chimuka");
            jsonObject.put("lastname","moonde");
        } catch (JSONException e) {
            e.printStackTrace();
        }

        // prepare the Request
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, URL_ENDPOINT_TEST, jsonObject,
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

    public void updateInformation() {

        progressDialog.show();

        ///prepare your JSONObject which you want to send in your web service request
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("firstname","chims");
            jsonObject.put("lastname","C7");
        } catch (JSONException e) {
            e.printStackTrace();
        }

        // prepare the Request
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.PUT, URL_ENDPOINT_TEST, jsonObject,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {

                        //Do stuff here
                        // display response
                        progressDialog.dismiss();
                        Log.d("Response", response.toString());

//                        try {
//
//
//                            //Do stuff here
//                            // display response
//                            progressDialog.dismiss();
//                            Log.d("Response", response.toString());
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

    @Override
    public void onClick(View view) {
      //  fetchTrader();


            }


    public void setDate()
    {
        Date today = Calendar.getInstance().getTime();//getting date
        SimpleDateFormat formatter = new SimpleDateFormat("EEE, d MMM yyyy");//formating according to my need
        String date = formatter.format(today);
        TextView txtViewDate = (TextView)findViewById(R.id.textViewDate);
        txtViewDate.setText(date);
    }

    }

