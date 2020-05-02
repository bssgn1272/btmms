package com.innovitrix.napsamarketsales;

import android.content.DialogInterface;
import android.provider.Settings;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.CardView;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.SearchView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.dialog.DialogBox;
import com.innovitrix.napsamarketsales.models.MarketStand;
import com.innovitrix.napsamarketsales.models.MarketStandAdapter;
import com.innovitrix.napsamarketsales.models.Route;
import com.innovitrix.napsamarketsales.models.RoutePlannedAdapter;
import com.innovitrix.napsamarketsales.network.MyJsonArrayRequest;
import com.innovitrix.napsamarketsales.utils.VerticalSpacingItemDecorator;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_MARKETER_KYC;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_MARKETER_KYC_SINGLE;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_TRANSACTIONS;

public class PayMarketFees extends AppCompatActivity implements MarketStandAdapter.OnMarketStandListener {
    private static final String TAG = "PayMarketFees";

    // ui components
    private RecyclerView recyclerView;

    // vars
    private ArrayList<MarketStand> mMarketStands = new ArrayList<>();
    private MarketStandAdapter mMarketStandAdapter;

    private RequestQueue queue;

    private String seller_id;
    private String seller_first_name;
    private  String seller_last_name;
    private String seller_mobile_number;
    private String buyer_id;
    private String  buyer_first_name;
    private String  buyer_last_name;
    private String buyer_mobile_number;

    private String device_serial;
    private double amount;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_pay_market_fees);
        getSupportActionBar().setSubtitle("Pay Market Fees");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        queue = Volley.newRequestQueue(this);
        recyclerView = findViewById(R.id.recylerview_Market_Stand);

        buyer_id=  "2020-3676257032-2-8166-20"; //SharedPrefManager.getInstance(context).getUser().getTrader_id();
        buyer_mobile_number = "260967485331";// SharedPrefManager.getInstance(context).getUser().getMobile_number();
        buyer_first_name = "Simon";//SharedPrefManager.getInstance(context).getUser().getFirstname();
        buyer_last_name = "Chiwamba"; //SharedPrefManager.getInstance(context).getUser().getLastname();


        initRecyclerView();
        fetchStands();

        //insertFakeNotes();


    }

    private void insertFakeNotes()
    {
        for(int i = 0; i < 5; i++){
            MarketStand note = new MarketStand(String.valueOf(i), Double.valueOf(i));
            mMarketStands.add(note);
        }

        mMarketStandAdapter.notifyDataSetChanged();
    }

    private void initRecyclerView(){
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

        builder.setMessage("Confirm payment of ZMW "+String.valueOf(mMarketStands.get(position).getStand_price()) +" Market fees for stand number "+mMarketStands.get(position).getStand_number()+"?");
        builder.setPositiveButton("Yes",
                new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        MarketStand ms = mMarketStands.get(position);
                        device_serial = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
                        sendInformation(4,ms.getStand_number(), buyer_id, buyer_first_name, buyer_last_name, buyer_mobile_number,ms.getStand_price(), device_serial);
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

    public void fetchStands() {

        JSONObject jsonAuthObject = new JSONObject();
        try {
            jsonAuthObject.put("username", "admin");
            jsonAuthObject.put("service_token", "JJ8DJ7S66DMA5");
        } catch (JSONException e) {
            e.printStackTrace();
        }


        //PAYLOAD
        JSONObject jsonPayloadObject = new JSONObject();
        try {
            // jsonPayloadObject.put("start_route", start_Route);
            //jsonPayloadObject.put("end_route", end_Route);
            //  jsonPayloadObject.put("date", travel_Date);//TODO change to actual date
            jsonPayloadObject.put("date", "27/01/2020");
        } catch (JSONException e) {
            e.printStackTrace();
        }


        ///prepare your JSONObject which you want to send in your web service request
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("auth", jsonAuthObject);
            jsonObject.put("payload", jsonPayloadObject);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        // prepare the Request


        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, URL_MARKETER_KYC_SINGLE,null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {

                        //Do stuff here
                        // display response

                        Log.d("Response", response.toString());
                        //progressBar.setVisibility(View.GONE);

                        try {
                            //converting response to json object
                            JSONObject obj = new JSONObject(String.valueOf(response));


                            //Check if the object has the key.
                            if (obj.getJSONObject("response").has("QUERY")) {

                                JSONObject KYC = obj.getJSONObject("response").getJSONObject("QUERY").getJSONObject("data");

                                JSONArray KYCArray = KYC.getJSONArray("stands");

                                if (KYCArray.length() > 0) {
                                    //   DialogBox.mLovelyStandardDialog(PayMarketFees.this, String.valueOf(KYCArray.length()));

                                    for (int i = 0; i < KYCArray.length(); i++) {

                                        JSONObject currentStand = KYCArray.getJSONObject(i);
                                        // DialogBox.mLovelyStandardDialog(PayMarketFees.this, currentStand.getString("stand_number"));

                                        MarketStand ms = new MarketStand
                                                (
                                                        currentStand.getString("stand_number"),
                                                        currentStand.getDouble("stand_price")
                                                );

                                        mMarketStands.add(ms);
                                    }
                                    mMarketStandAdapter.notifyDataSetChanged();
                                }
                                else{
                                    DialogBox.mLovelyStandardDialog(PayMarketFees.this, " Zero KYC.");
                                }
                            }else{

                                DialogBox.mLovelyStandardDialog(PayMarketFees.this, "No market stands for you.");

                            }


                        } catch (JSONException e) {
                            e.printStackTrace();
                        }

                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        //Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show()
                        //Handle Errors here
                        //   progressDialog.dismiss();
                        Log.d("Error.Response", error.toString());
                        //Log.d("Error.Response", error.getMessage());
                        DialogBox.mLovelyStandardDialog(PayMarketFees.this, "Server unreachable");
                        // startActivity(new Intent( BuyFromTrader.this,MainActivity.class));
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
            )
    {

        //progressDialog.show();

        ///prepare your JSONObject which you want to send in your web service request

        JSONObject jsonObject = new JSONObject();
        try {

            jsonObject.put( "transaction_type_id",transaction_type_id);
            jsonObject.put("stand_number",stand_number);
            jsonObject.put("buyer_id",buyer_id);
            jsonObject.put("buyer_firstname",buyer_first_name);
            jsonObject.put("buyer_lastname",buyer_last_name);
            jsonObject.put("buyer_mobile_number",buyer_mobile_number);
            jsonObject.put("amount_due", amount_due);
            jsonObject.put("device_serial",device_serial);
            jsonObject.put("transaction_date", Calendar.getInstance().getTime());
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
                        try {
                            // etAmount.requestFocus();
                            DialogBox.mLovelyStandardDialog(PayMarketFees.this, response.getString(KEY_MESSAGE));
                            //startActivity(new Intent( MakeSell.this,MainActivity.class));

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
                Log.d("Error.Response", error.getMessage());

                //  DialogBox.mLovelyStandardDialog( MakeSell.this, error.toString());
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