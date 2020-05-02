package com.innovitrix.napsamarketsales;

import android.app.ProgressDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.android.volley.AuthFailureError;
import com.android.volley.NetworkError;
import com.android.volley.NoConnectionError;
import com.android.volley.ParseError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.ServerError;
import com.android.volley.TimeoutError;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.dialog.DialogBox;
import com.innovitrix.napsamarketsales.models.Transaction_Summary;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.API_KEY;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_AMPERSAND;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_PERIOD;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_SELLER_MOBILE_NUMBER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_SUMMARY_TRANSACTIONS;

public class CheckBalance extends AppCompatActivity {
    String seller_id;
    String seller_first_name;
    String seller_last_name;
    String seller_mobile_number;
    TextView textView_Balance_Daily;
    TextView textView_Balance_Weekly;
    TextView textView_Balance_Monthly;
    TextView textView_Balance_Daily_Count;
    TextView textView_Balance_Weekly_Count;
    TextView textView_Balance_Monthly_Count;
    private List<Transaction_Summary> transaction_Summaries;
    ProgressBar progressBar;
    ProgressDialog progressDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_check_balance);
        getSupportActionBar().setSubtitle("Check Sales");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        seller_id=  SharedPrefManager.getInstance(CheckBalance.this).getUser().getTrader_id();
        seller_mobile_number = SharedPrefManager.getInstance(CheckBalance.this).getUser().getMobile_number();
        seller_first_name= SharedPrefManager.getInstance(CheckBalance.this).getUser().getFirstname();
        seller_last_name = SharedPrefManager.getInstance(CheckBalance.this).getUser().getLastname();
        textView_Balance_Daily = (TextView) findViewById(R.id.tvBalanceDaily);
        textView_Balance_Weekly = (TextView) findViewById(R.id.tvBalanceWeekly);
        textView_Balance_Monthly = (TextView) findViewById(R.id.tvBalanceMonthly);
        textView_Balance_Daily_Count = (TextView) findViewById(R.id.tvBalanceDailyCount);
        textView_Balance_Weekly_Count = (TextView) findViewById(R.id.tvBalanceWeeklyCount);
        textView_Balance_Monthly_Count = (TextView) findViewById(R.id.tvBalanceMonthlyCount);
        setDate();

        progressDialog = new ProgressDialog(CheckBalance.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);

       // loadMonth();
        //check_Balance_Daily();
       // check_Balance_Weekly();
        check_Balance();
    }


    public void check_Balance() {

            progressDialog.show();


        // prepare the Request
        // progressBar.setVisibility(View.VISIBLE);

        //creating a string request



        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, URL_SUMMARY_TRANSACTIONS +
                URL_CHAR_QUESTION +
                URL_PARAM_SELLER_MOBILE_NUMBER +"260967485331"
                , null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {

                        progressDialog.dismiss();
                        try {

                            // JSONObject obj = new JSONObject(String.valueOf(response));
                            //Check if the object if the object is null.
                            //if (!obj.isNull("transaction_summaries")){
                            Log.d("check_Balance", response.toString());

                            // if(obj.getJSONObject("response").has("transaction_summaries")){

                            //getting the user from the response

                            //SONObject currentTransaction_Summary = response.getJSONObject("marketeer");
                            //creating a new user object
                            com.innovitrix.napsamarketsales.models.Transaction_Summary mTransaction_Summary = new com.innovitrix.napsamarketsales.models.Transaction_Summary(

                                    response.getJSONObject("marketeer").getString("seller_id"),
                                    response.getJSONObject("marketeer").getString("seller_firstname"),
                                    response.getJSONObject("marketeer").getString( "seller_lastname"),
                                    response.getJSONObject("marketeer").getString("seller_mobile_number"),
                                    response.getJSONObject("today").getInt("num_of_sales"),
                                    response.getJSONObject("today").getString("revenue"),
                                    response.getJSONObject("week").getInt("num_of_sales"),
                                    response.getJSONObject("week").getString("revenue"),
                                    response.getJSONObject("month").getInt("num_of_sales"),
                                    response.getJSONObject("month").getString("revenue")

                            );

                            //storing the user in shared preferences
                            //SharedPrefManager.getInstance(getApplicationContext()).userLogin(user);
                            //    SharedPrefManager.getInstance(getApplicationContext()).storeCurrentUser(mUser);
                            //starting profile activity
                            //  finish();

                            textView_Balance_Daily.setText(mTransaction_Summary.getToday_revenue());
                            textView_Balance_Daily_Count.setText("Number of sales :"+ mTransaction_Summary.getToday_num_of_sales());

                            textView_Balance_Weekly.setText(mTransaction_Summary.getWeek_revenue());
                            textView_Balance_Weekly_Count.setText("Number of sales :"+ mTransaction_Summary.getWeek_num_of_sales());

                            textView_Balance_Monthly.setText(mTransaction_Summary.getMonth_revenue());
                            textView_Balance_Monthly_Count.setText("Number of sales :"+  mTransaction_Summary.getMonth_num_of_sales());
                           // DialogBox.mLovelyStandardDialog(CheckBalance.this,mTransaction_Summary.getMonth_num_of_sales());
                        } catch (JSONException e) {
                            e.printStackTrace();
                       //     DialogBox.mLovelyStandardDialog(CheckBalance.this,e.getMessage());

                        }

                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error)
                    {
                        //Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show()
                        //Handle Errors here
                        // progressDialog.dismiss();
                        Log.d("Error.Response", error.toString());
                        Log.d("Error.Response", error.getMessage());
                        // DialogBox.mLovelyStandardDialog(CheckBalance.this, error.toString());
                        // startActivity(new Intent( BuyFromTrader.this,MainActivity.class));

                        progressDialog.dismiss();
                        if (error instanceof TimeoutError || error instanceof NoConnectionError) {

                            DialogBox.mLovelyStandardDialog(CheckBalance.this, R.string.error_timeout);

                        } else if (error instanceof AuthFailureError) {

                            DialogBox.mLovelyStandardDialog(CheckBalance.this, R.string.error_auth_failure);

                        } else if (error instanceof ServerError) {

                            DialogBox.mLovelyStandardDialog(CheckBalance.this, R.string.error_server);

                        } else if (error instanceof NetworkError) {

                            DialogBox.mLovelyStandardDialog(CheckBalance.this, R.string.error_network);

                        } else if (error instanceof ParseError) {

                            DialogBox.mLovelyStandardDialog(CheckBalance.this, R.string.error_parser);

                        }




        }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                // params.put("username", trader_id);
                //params.put("email", firstname);
                // params.put("password", lastname);
                params.put("mobile_number",  seller_mobile_number );
                return params;
            }
        };

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonObjectRequest);

    }

    public void setDate()
    {
        Date today = Calendar.getInstance().getTime();//getting date
        SimpleDateFormat formatter = new SimpleDateFormat("EEE, d MMM yyyy");//formating according to my need
        String date = formatter.format(today);
        TextView txtViewDate = (TextView)findViewById(R.id.textViewDate2);
        txtViewDate.setText(date);
    }





}
