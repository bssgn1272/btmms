package com.innovitrix.napsamarketsales;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.CardView;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
//import android.support.v7.widget.SearchView;
import android.view.Menu;
import android.view.View;
import android.widget.SearchView;
import android.util.Log;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.RadioGroup;
import android.widget.Spinner;
import android.widget.TextView;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.dialog.DialogBox;
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
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_FIRSTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LASTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_NRC;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_CODE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_NAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_START_ROUTE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRAVEL_DATE;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_DESTINATION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_ROUTES;

public class BusSchedule extends AppCompatActivity {
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

    private TextView textView_Route_Title;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_bus_schedule);
        getSupportActionBar().setSubtitle("Buy bus ticket");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        route_Name = getIntent().getStringExtra(KEY_ROUTE_NAME);
        start_Route= getIntent().getStringExtra(KEY_START_ROUTE);
        end_Route = getIntent().getStringExtra(KEY_END_ROUTE);
        travel_Date = getIntent().getStringExtra(KEY_TRAVEL_DATE);
        route_Code = getIntent().getStringExtra(KEY_ROUTE_CODE);

       // textView_Route_Title = (TextView)findViewById(R.id.textViewRouteTitle);
       // textView_Route_Title.setText("Bus Schedules \nScheduled Bus for "+ route_Name);
        getSupportActionBar().setSubtitle("Bus Schedules Bus for "+ route_Name);
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
    public void fetchRoutes() {


        // prepare the Request
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, URL_ROUTES, null,
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

                            //Check if the object has the key
                            if (obj.has("travel_routes")) {

                                //creating a new user object
                                JSONArray array = obj.getJSONArray("travel_routes");

                                for (int i = 0; i < array.length(); i++) {

                                    JSONObject currentRoute = array.getJSONObject(i);

                                    Route r = new Route
                                            (
                                                    currentRoute.getInt("id"),
                                                    currentRoute.getString("route_code"),
                                                    currentRoute.getString("route_name"),
                                                    currentRoute.getString("source_state"),
                                                    currentRoute.getString("start_route"),
                                                    currentRoute.getString("end_route")
                                            );

                                    routes.add(r);
                                    if (r.getRoute_name().equals(route_Name)){
                                        start_Route = r.getStart_route();
                                        end_Route = r.getEnd_route();
                                        fetchDestinations();

                                    }
                                }

                            } else {

                                DialogBox.mLovelyStandardDialog(BusSchedule.this, "Unable to retrieve routes");
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
                        DialogBox.mLovelyStandardDialog(BusSchedule.this, error.toString());
                        // startActivity(new Intent( BuyFromTrader.this,MainActivity.class));
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

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonObjectRequest);

    }
    private void setUpRecyclerView() {
        recyclerView = (RecyclerView) findViewById(R.id.recylerview_Route);
        recyclerView.setHasFixedSize(true);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
    }
    public void fetchDestinations() {

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
            jsonPayloadObject.put("start_route", start_Route);
            jsonPayloadObject.put("end_route", end_Route);
            jsonPayloadObject.put("date", travel_Date);//TODO change to actual date
           //jsonPayloadObject.put("date", "27/01/2020");
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
                        //progressBar.setVisibility(View.GONE);

                        try {

                            if (response.length() > 0) {

                                for (int i = 0; i < response.length(); i++) {

                                    JSONObject currentRoute= response.getJSONObject(i);


                                    //  JSONObject currentRoute = array.getJSONObject(i);
                                    RoutePlanned rp = new RoutePlanned
                                            (
                                                    currentRoute.getInt("available_seats"),
                                                    currentRoute.getJSONObject("bus").getString("company"),
                                                    currentRoute.getJSONObject("bus").getString("liscense_plate"),
                                                    currentRoute.getJSONObject("bus").getInt("operator_id"),
                                                    currentRoute.getJSONObject("bus").getInt("vehicle_capacity"),
                                                    currentRoute.getInt("bus_schedule_id"),
                                                    currentRoute.getString("departure_date"),
                                                    currentRoute.getString("departure_time"),
                                                    currentRoute.getDouble("fare"),
                                                    currentRoute.getJSONObject("route").getString("route_code"),
                                                    currentRoute.getJSONObject("route").getString("route_name")
                                            );

                                    routesPlanned.add(rp);
                                }

                                routePlannedAdapter = new RoutePlannedAdapter(getApplicationContext(), routesPlanned);
                                recyclerView.setAdapter(routePlannedAdapter);

                            }else{

                                DialogBox.mLovelyStandardDialog(BusSchedule.this, "No buses are scheduled for " + route_Name+".");

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
                        DialogBox.mLovelyStandardDialog(BusSchedule.this, "Server unreachable");
                        // startActivity(new Intent( BuyFromTrader.this,MainActivity.class));
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

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonObjectRequest);

    }


}
