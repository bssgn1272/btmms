package com.innovitrix.napsamarketsales;

import android.app.ActionBar;
import android.app.LauncherActivity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.CardView;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;
import android.view.inputmethod.EditorInfo;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.SearchView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.dialog.DialogBox;
import com.innovitrix.napsamarketsales.models.Route;
import com.innovitrix.napsamarketsales.models.RoutePlanned;
import com.innovitrix.napsamarketsales.models.RoutePlannedAdapter;
import com.innovitrix.napsamarketsales.network.MyJsonArrayRequest;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import android.widget.AdapterView;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_FIRSTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LASTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_NRC;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_DESTINATION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_MARKETER_KYC;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_MOBILE_NUMBER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_ROUTE_ID;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_ROUTES;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_TRANSACTIONS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_USERS;

public class BuyBusTicketActivity extends AppCompatActivity {

    private RecyclerView recyclerView;
    private CardView cardViewRoutePlanned;
    RoutePlannedAdapter routePlannedAdapter;
    private List<RoutePlanned> routesPlanned;
    private List<Route> routes;
    private ArrayAdapter<String> adapter_Start_Route;
    private ArrayAdapter<String> adapter_End_Route;
    private Spinner spinner_Start_Route;
    private Spinner spinner_End_Route;
    private String start_Route;
    private String end_Route;
    private SearchView searchView;
    private String route_id;
    ArrayList<String> start_Route_ArrayList;
    ArrayList<String> end_Route_ArrayList;
    private RadioGroup radioGroup;
    Button btnSubmit;
    Date travel_Date;
    String date_Of_Travel;
    String firstname;
    String lastname;
    String  nrc;
    String mobilenumber;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_buy_bus_ticket);
        setUpRecyclerView();
        getSupportActionBar().setSubtitle("Buy bus ticket");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        firstname = getIntent().getStringExtra(KEY_FIRSTNAME);
        lastname = getIntent().getStringExtra(KEY_LASTNAME);
        nrc =  getIntent().getStringExtra(KEY_NRC);
        mobilenumber = getIntent().getStringExtra(KEY_MOBILE);
        start_Route_ArrayList = new ArrayList<String>();
        end_Route_ArrayList = new ArrayList<String>();

        routes = new ArrayList<>();
        routesPlanned = new ArrayList<>();
        //  routePlannedAdapter = new RoutePlannedAdapter(getApplicationContext(),routesPlanned);
        // loadRoutes();

        fetchRoutes();

        //searchView = (SearchView) findViewById(R.id.searchview_route);
        spinner_Start_Route = (Spinner) findViewById(R.id.start_route);
        spinner_End_Route = (Spinner) findViewById(R.id.end_route);
        radioGroup = (RadioGroup) findViewById(R.id.groupradio);
        btnSubmit = (Button) findViewById(R.id.buttonPay);

        spinner_Start_Route.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                start_Route = parent.getItemAtPosition(position).toString();
                // Toast.makeText(parent.getContext(), "Selected: " + tutorialsName, Toast.LENGTH_LONG).show();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });

        spinner_End_Route.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                end_Route = parent.getItemAtPosition(position).toString();

                //    Toast.makeText(parent.getContext(), "Selected: " + tutorialsName2, Toast.LENGTH_LONG).show();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });
//        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
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
        btnSubmit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                // boolean checked = ((RadioButton) view).isChecked();
                int selectedId = radioGroup.getCheckedRadioButtonId();
                RadioButton radioButton = (RadioButton) findViewById(selectedId);
                String da = radioButton.getText().toString();
                Date currentDate = new Date();
                // convert date to calendar
                Calendar c = Calendar.getInstance();
                c.setTime(currentDate);

                if (da.equals("Today")) {
                    travel_Date = c.getTime();
                    //     DialogBox.mLovelyStandardDialog(BuyBusTicketActivity.this, travel_Date.toString());
                }
                if (da.equals("Tomorrow")) {
                    c.add(Calendar.DAY_OF_MONTH, -2);
                    travel_Date = c.getTime();
                    // DialogBox.mLovelyStandardDialog(BuyBusTicketActivity.this, travel_Date.toString());
                }
                SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");//formating according to my need
                date_Of_Travel = formatter.format(travel_Date);
                //   DialogBox.mLovelyStandardDialog(BuyBusTicketActivity.this,   date_Of_Travel.toString());

                fetchDestinations();
            }
        });
    }

    private void setUpRecyclerView() {
        recyclerView = (RecyclerView) findViewById(R.id.recylerview_route);
        recyclerView.setHasFixedSize(true);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
    }

    //start and end route
    public void fetchRoutes() {

        JSONObject jsonAuthObject = new JSONObject();
        try {
            jsonAuthObject.put("username", "admin");
            jsonAuthObject.put("service_token", "JJ8DJ7S66DMA5");
        } catch (JSONException e) {
            e.printStackTrace();
        }


        ///prepare your JSONObject which you want to send in your web service request
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("auth", jsonAuthObject);
        } catch (JSONException e) {
            e.printStackTrace();
        }
        // prepare the Request

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

                                    String s = currentRoute.getString("start_route");
                                    String e = currentRoute.getString("end_route");

                                    start_Route_ArrayList.add(s);
                                    end_Route_ArrayList.add(e);

                                }

                                // Creating adapter for spinner
                                adapter_Start_Route = new ArrayAdapter<>(BuyBusTicketActivity.this, android.R.layout.simple_spinner_dropdown_item, start_Route_ArrayList);
                                adapter_End_Route = new ArrayAdapter<>(BuyBusTicketActivity.this, android.R.layout.simple_spinner_dropdown_item, end_Route_ArrayList);

                                // Drop down layout style - list view with radio button
                                adapter_Start_Route.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                                adapter_End_Route.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);


                                // attaching data adapter to spinner

                                //attach adapter to spinner
                                spinner_Start_Route.setAdapter(adapter_Start_Route);
                                spinner_End_Route.setAdapter(adapter_End_Route);
                                //    Log.d("Route", adapterRoute.toString());

                            } else {

                                DialogBox.mLovelyStandardDialog(BuyBusTicketActivity.this, "Unable to retrieve routes");


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
                        DialogBox.mLovelyStandardDialog(BuyBusTicketActivity.this, error.toString());
                        // startActivity(new Intent( BuyFromTrader.this,MainActivity.class));
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                // params.put("username", trader_id);
                //params.put("email", firstname);
                // params.put("password", lastname);
                params.put("mobile_number", route_id);
                return params;
            }
        };

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonObjectRequest);

    }


    //end
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
            //jsonPayloadObject.put("date", date_Of_Travel);//TODO change to actual date
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
        MyJsonArrayRequest   jsonObjectRequest = new MyJsonArrayRequest(Request.Method.POST, URL_DESTINATION, jsonObject,
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

                                DialogBox.mLovelyStandardDialog(BuyBusTicketActivity.this, "Unable to retrieve routes");
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
                        DialogBox.mLovelyStandardDialog(BuyBusTicketActivity.this, error.toString());
                        // startActivity(new Intent( BuyFromTrader.this,MainActivity.class));
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                // params.put("username", trader_id);
                //params.put("email", firstname);
                // params.put("password", lastname);
                params.put("mobile_number", route_id);
                return params;
            }
        };

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonObjectRequest);

    }





}
