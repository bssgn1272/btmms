package com.innovitrix.napsamarketsales;

import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;
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
import com.innovitrix.napsamarketsales.models.Transaction_Summary;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_VOLLEY_SOCKET_TIMEOUT_MS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
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
    TextView textViewUsername;
    TextView textViewDate;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_check_balance);
        getSupportActionBar().setSubtitle("check sales");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        textViewUsername = (TextView)findViewById(R.id.textViewUsername);
        textViewUsername.setText("Logged in as "+SharedPrefManager.getInstance(CheckBalance.this).getUser().getFirstname()+ " "+SharedPrefManager.getInstance(CheckBalance.this).getUser().getLastname());
        textViewDate = (TextView)findViewById(R.id.textViewDate);
        textViewDate.setText(SharedPrefManager.getInstance(CheckBalance.this).getTranactionDate2());

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
        progressBar = (ProgressBar) findViewById(R.id.progressBar);


        progressDialog = new ProgressDialog(CheckBalance.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);

       // loadMonth();
        //check_Balance_Daily();
       // check_Balance_Weekly();
        check_Balance();
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                //do whatever
                Intent intent = new Intent(CheckBalance.this, MainActivity.class);
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
        Intent intent = new Intent(CheckBalance.this, MainActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
    }
    public void check_Balance() {

           // progressDialog.show();


        // prepare the Request
         progressBar.setVisibility(View.VISIBLE);

        //creating a string request

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, URL_SUMMARY_TRANSACTIONS +
                URL_CHAR_QUESTION +
                URL_PARAM_SELLER_MOBILE_NUMBER +seller_mobile_number
                , null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {

                        progressDialog.dismiss();
                        progressBar.setVisibility(View.GONE);

                        try {

                            // JSONObject obj = new JSONObject(String.valueOf(response));
                            //Check if the object if the object is null.
                            //if (!obj.isNull("transaction_summaries")){
                            Log.d("check_Balance", response.toString());
                            progressBar.setVisibility(View.GONE);

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
                            progressBar.setVisibility(View.GONE);
//                            AlertDialog.Builder builder = new AlertDialog.Builder(CheckBalance.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("An error occurred while retrieving sales, kindly check your internet connection and try again.");
//                            builder.setPositiveButton("Ok",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//
//                                            Intent intent = new Intent(CheckBalance.this, MainActivity.class);
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
                    public void onErrorResponse(VolleyError error)
                    {
                        Log.d("Error.Response", error.toString());

                        progressBar.setVisibility(View.GONE);


                        AlertDialog.Builder builder = new AlertDialog.Builder(CheckBalance.this);
                        builder.setCancelable(false);
                        builder.setMessage("Connection failure, kindly check your internet connection and try again.");
                        builder.setPositiveButton("Ok",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {

                                        Intent intent = new Intent(CheckBalance.this, MainActivity.class);
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
                params.put("mobile_number",  seller_mobile_number );
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






}
