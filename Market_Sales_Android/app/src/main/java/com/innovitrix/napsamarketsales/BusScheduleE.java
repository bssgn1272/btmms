package com.innovitrix.napsamarketsales;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.ProgressBar;
import android.widget.SearchView;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.models.Route;
import com.innovitrix.napsamarketsales.models.RoutePlanned;
import com.innovitrix.napsamarketsales.models.RoutePlannedAdapter;
import com.innovitrix.napsamarketsales.network.MyJsonArrayRequest;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_END_ROUTE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_CODE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_NAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_SERVICE_TOKEN;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_START_ROUTE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRAVEL_DATE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_USERNAME_API;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_VOLLEY_SOCKET_TIMEOUT_MS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_DESTINATION;

//import android.support.v7.widget.SearchView;

public class BusScheduleE extends AppCompatActivity {
    ProgressBar progressBar;
    private RecyclerView recyclerView;
    private CardView cardViewRoutePlanned;
    RoutePlannedAdapter routePlannedAdapter;
    private List<RoutePlanned> routesPlanned;
    private SearchView searchView_Route;
    private String route_Code;
    private String route_Name;
    private String travel_Date;
    private List<Route> routes;
    private String start_Route;
    private String end_Route;
    private String start_Route_Format;
    private String end_Route_Format;
    TextView textViewUsername;
    TextView textViewDate;
    private TextView textView_Route_Title;
    private long backPressedTime;
    private Toast backToast;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_bus_schedule_e);
        getSupportActionBar().setSubtitle("bus schedules for ");
                // getSupportActionBar().setSubtitle("Buy bus ticket");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        route_Name = getIntent().getStringExtra(KEY_ROUTE_NAME);
        start_Route= getIntent().getStringExtra(KEY_START_ROUTE);
        end_Route = getIntent().getStringExtra(KEY_END_ROUTE);
        travel_Date = getIntent().getStringExtra(KEY_TRAVEL_DATE);
        route_Code = getIntent().getStringExtra(KEY_ROUTE_CODE);
       // textViewUsername = (TextView)findViewById(R.id.textViewUsername);
        //textViewUsername.setText("Logged in as "+SharedPrefManager.getInstance(BusScheduleE.this).getUser().getFirstname()+ " "+SharedPrefManager.getInstance(BusScheduleE.this).getUser().getLastname());
        textViewDate = (TextView)findViewById(R.id.textViewDate);

        progressBar = (ProgressBar) findViewById(R.id.progressBar);

        textViewDate.setText(SharedPrefManager.getInstance(BusScheduleE.this).getTranactionDate2());
       // textView_Route_Title = (TextView)findViewById(R.id.textViewRouteTitle);
       // textView_Route_Title.setText("Bus Schedules \nScheduled Bus for "+ route_Name);
        start_Route_Format = start_Route.substring(0, 1).toUpperCase() + start_Route.substring(1).toLowerCase();
        end_Route_Format = end_Route.substring(0, 1).toUpperCase() + end_Route.substring(1).toLowerCase();
        getSupportActionBar().setSubtitle("bus schedules for "+ start_Route_Format +" to " + end_Route_Format);
        routes = new ArrayList<>();
        routesPlanned = new ArrayList<>();


//        searchView_Route = (SearchView) findViewById(R.id.searchview_Route);
//        searchView_Route.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
//            @Override
//            public boolean onQueryTextSubmit(String s) {
//                return false;
//            }
//
//            @Override
//            public boolean onQueryTextChange(String s) {
//                routePlannedAdapter.getFilter().filter(s);
//                return false;
//            }
//        });

         setUpRecyclerView();
        fetchDestinations();

    }
//    @Override
////    public boolean onCreateOptionsMenu(Menu menu){
////        getMenuInflater().inflate(R.menu.menu_main,menu);
//        return true;
//    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                //do whatever
                Intent intent = new Intent(BusScheduleE.this, BusSearchE.class);
               // intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
                finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }}

    @Override
    public void onBackPressed() {
        // Toast.makeText(getApplication(),"Use the in app controls to navigate.",Toast.LENGTH_SHORT).show();
        Intent intent = new Intent(BusScheduleE.this, BusSearchE.class);
        //intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
    }
    private void setUpRecyclerView() {
        recyclerView = (RecyclerView) findViewById(R.id.recylerview_Route);
        recyclerView.setHasFixedSize(true);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
    }
    public void fetchDestinations() {
        progressBar.setVisibility(View.VISIBLE);
        //DialogBox.mLovelyStandardDialog(BusScheduleE.this, travel_Date);
        JSONObject jsonAuthObject = new JSONObject();
        try {
            jsonAuthObject.put("username", KEY_USERNAME_API);
            jsonAuthObject.put("service_token", KEY_SERVICE_TOKEN);
        } catch (JSONException e) {
            e.printStackTrace();
        }


        //PAYLOAD
        JSONObject jsonPayloadObject = new JSONObject();
        try {
            jsonPayloadObject.put("start_route", start_Route);
            jsonPayloadObject.put("end_route", end_Route);
            //jsonPayloadObject.put("date", travel_Date);
            jsonPayloadObject.put("date", "2020-07-04"); //TODO change to actual date
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
        MyJsonArrayRequest jsonObjectRequest = new MyJsonArrayRequest(Request.Method.POST, URL_DESTINATION, jsonObject,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray  response) {

                        //Do stuff here
                        // display response

                        Log.d("Response", response.toString());
                        progressBar.setVisibility(View.GONE);

                        try {
                            String ni =null;
                            if (response.length() > 0) {

                                for (int i = 0; i < response.length(); i++) {

                                    JSONObject currentRoute= response.getJSONObject(i);


                                    //  JSONObject currentRoute = array.getJSONObject(i);
                                    RoutePlanned rp = new RoutePlanned
                                            (
                                                    currentRoute.getInt("available_seats"),
                                                    currentRoute.getJSONObject("bus").getString("company"),
                                                    currentRoute.getJSONObject("bus").getString("license_plate"),
                                                    currentRoute.getJSONObject("bus").getInt("operator_id"),
                                                    currentRoute.getJSONObject("bus").getInt("vehicle_capacity"),
                                                    currentRoute.getString("bus_schedule_id"),
                                                    currentRoute.getString("departure_date"),
                                                    currentRoute.getString("departure_time"),
                                                    currentRoute.getDouble("fare"),
                                                    currentRoute.getJSONObject("route").getString("route_code"),
                                                    currentRoute.getJSONObject("route").getString("route_name"),
                                                    currentRoute.getJSONObject("route").getString("start_route"),
                                                    currentRoute.getJSONObject("route").getString("end_route"),
                                                    travel_Date
                                            );

                                    routesPlanned.add(rp);

                                }

                                routePlannedAdapter = new RoutePlannedAdapter(getApplicationContext(), routesPlanned);
                                recyclerView.setAdapter(routePlannedAdapter);

                            }else{

                                //DialogBox.mLovelyStandardDialog(BusSchedule.this, travel_Date");
                                progressBar.setVisibility(View.GONE);
                                AlertDialog.Builder builder = new AlertDialog.Builder(BusScheduleE.this);
                                builder.setCancelable(false);
                                builder.setMessage("There are no buses scheduled for "+ start_Route_Format +" to " + end_Route_Format+" route, kindly contact the System Administrator.");
                                builder.setPositiveButton("Ok",
                                        new DialogInterface.OnClickListener() {
                                            public void onClick(DialogInterface dialog, int id) {
                                                Intent intent = new Intent(BusScheduleE.this,BusSearchE.class);
                                                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                                startActivity(intent);
                                                finish();
                                            }
                                        });
                                builder.create().show();


                            }


                        } catch (JSONException e) {
                            e.printStackTrace();

                            progressBar.setVisibility(View.GONE);
//                            AlertDialog.Builder builder = new AlertDialog.Builder(BusScheduleE.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("A problem occurred while retrieving bus schedules for "+ start_Route_Format +" to " + end_Route_Format+" route.");
//                            builder.setPositiveButton("Ok",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//                                            Intent intent = new Intent(BusScheduleE.this,BusSearchE.class);
//                                            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
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
                    public void onErrorResponse(VolleyError error) {
                        //Handle Errors here
                        //   progressDialog.dismiss();
                        Log.d("Error.Response", error.toString());
                        //Log.d("Error.Response", error.getMessage());
                        progressBar.setVisibility(View.GONE);
                        AlertDialog.Builder builder = new AlertDialog.Builder(BusScheduleE.this);
                        builder.setCancelable(false);
                        builder.setMessage("Connection failure, kindly check your internet connection and try again.");
                        builder.setPositiveButton("Ok",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                        Intent intent = new Intent(BusScheduleE.this,BusSearchE.class);
                                        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
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
                params.put("mobile_number", route_Name);
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
