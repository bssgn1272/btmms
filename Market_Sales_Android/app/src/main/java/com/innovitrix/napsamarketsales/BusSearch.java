package com.innovitrix.napsamarketsales;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.RecyclerView;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Spinner;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.dialog.DialogBox;
import com.innovitrix.napsamarketsales.models.Route;

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

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_END_ROUTE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_FIRSTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LASTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_NRC;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_CODE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_NAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_START_ROUTE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRAVEL_DATE;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_ROUTES;


public class BusSearch extends AppCompatActivity {

    private RecyclerView recyclerView;
    private Spinner spinner_Route;
    private Date travel_Date;
    private String date_Of_Travel;
    private RadioGroup radioGroup;
    private Button button_Search;

    private List<Route> routes;
    private ArrayAdapter<String> adapter_Route;
    private String route_Name;
    private ArrayList<String> route_ArrayList;
    private Route selectedRoute;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_bus_search);
        getSupportActionBar().setSubtitle("Search for bus schedule");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        spinner_Route = (Spinner) findViewById(R.id.spinner_Route);
        radioGroup = (RadioGroup) findViewById(R.id.radioGroup_Day);
        button_Search = (Button) findViewById(R.id.button_Search);
        routes = new ArrayList<>();
        route_ArrayList = new ArrayList<>();

        routes = new ArrayList<>();
        fetchRoutes();
        spinner_Route.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                route_Name = parent.getItemAtPosition(position).toString();
                selectedRoute =  getRouteCode();

            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });

        button_Search.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

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
                    c.add(Calendar.DAY_OF_MONTH, 1);
                    travel_Date = c.getTime();
                    // DialogBox.mLovelyStandardDialog(BuyBusTicketActivity.this, travel_Date.toString());
                }
                SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");//formating according to my need
                date_Of_Travel = formatter.format(travel_Date);
                //   DialogBox.mLovelyStandardDialog(BuyBusTicketActivity.this,   date_Of_Travel.toString());
                if (TextUtils.isEmpty(route_Name)) {
                    DialogBox.mLovelyStandardDialog(BusSearch.this, "No route selected.");
                    spinner_Route.requestFocus();
                    return;

                }else
                {
                Intent intent = new Intent(getApplicationContext(),BusSchedule.class);
                intent.putExtra(KEY_ROUTE_NAME,route_Name);
                intent.putExtra(KEY_TRAVEL_DATE,date_Of_Travel);
                intent.putExtra(KEY_START_ROUTE,selectedRoute.getStart_route());
                intent.putExtra(KEY_END_ROUTE,selectedRoute.getEnd_route());
                startActivity(intent);}
            }
        });
    }

    public Route getRouteCode() {
        for (Route r : routes) {
            if (r.getRoute_name().equals(route_Name)) {
                return r;
            }
        }
        return null;
    }


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

                                    String s = currentRoute.getString("route_name");
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

                                    route_ArrayList.add(s);

                                }

                                // Creating adapter for spinner
                                adapter_Route = new ArrayAdapter<>(BusSearch.this, android.R.layout.simple_spinner_dropdown_item, route_ArrayList);

                                // Drop down layout style - list view with radio button
                                adapter_Route.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);


                                // attaching data adapter to spinner

                                //attach adapter to spinner
                                spinner_Route.setAdapter(adapter_Route);

                                //    Log.d("Route", adapterRoute.toString());

                            } else {

                                DialogBox.mLovelyStandardDialog(BusSearch.this, "Unable to retrieve routes");
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
//                        DialogBox.mLovelyStandardDialog(BusSearch.this, error.toString());
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
