package com.innovitrix.napsamarketsales;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.RecyclerView;
import android.text.TextUtils;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Spinner;
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
import com.innovitrix.napsamarketsales.models.Route;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_END_ROUTE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_NAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_SERVICE_TOKEN;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_START_ROUTE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRAVEL_DATE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_USERNAME_API;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_VOLLEY_SOCKET_TIMEOUT_MS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_ROUTES;


public class BusSearchE extends AppCompatActivity {
    ProgressBar progressBar;

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

    TextView textViewUsername;
    TextView textViewDate;
    private long backPressedTime;
    private Toast backToast;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_bus_search_e);
        getSupportActionBar().setSubtitle("select destination and travel date");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
       // textViewUsername = (TextView)findViewById(R.id.textViewUsername);
      //  textViewUsername.setText("Logged in as "+SharedPrefManager.getInstance(BusSearchE.this).getUser().getFirstname()+ " "+SharedPrefManager.getInstance(BusSearchE.this).getUser().getLastname());
        textViewDate = (TextView)findViewById(R.id.textViewDate);
        textViewDate.setText(SharedPrefManager.getInstance(BusSearchE.this).getTranactionDate2());

        progressBar = (ProgressBar) findViewById(R.id.progressBar);
        spinner_Route = (Spinner) findViewById(R.id.spinner_Route);
        radioGroup = (RadioGroup) findViewById(R.id.radioGroup_Day);
        button_Search = (Button) findViewById(R.id.button_Search);
        routes = new ArrayList<>();
        route_ArrayList = new ArrayList<>();

        routes = new ArrayList<>();
        SharedPrefManager.getInstance(this).logout();

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

                date_Of_Travel = SharedPrefManager.getInstance(BusSearchE.this).ConvertDateToString(travel_Date);

                //   DialogBox.mLovelyStandardDialog(BuyBusTicketActivity.this,   date_Of_Travel.toString());
                if (TextUtils.isEmpty(route_Name)) {

                    AlertDialog.Builder builder = new AlertDialog.Builder(BusSearchE.this);
                    builder.setCancelable(false);
                    builder.setMessage("Select a route for you to buy a bus ticket.");
                    builder.setPositiveButton("Ok",
                            new DialogInterface.OnClickListener() {
                                public void onClick(DialogInterface dialog, int id) {
                                    spinner_Route.requestFocus();
                                    return;
                                }
                            });
                    builder.create().show();

                }else
                {
                    Intent intent = new Intent(getApplicationContext(),BusScheduleE.class);
                    intent.putExtra(KEY_ROUTE_NAME,route_Name);
                    intent.putExtra(KEY_TRAVEL_DATE,date_Of_Travel);
                    intent.putExtra(KEY_START_ROUTE,selectedRoute.getStart_route());
                    intent.putExtra(KEY_END_ROUTE,selectedRoute.getEnd_route());
                    startActivity(intent);}
            }
        });
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                //do whatever
                Intent intent = new Intent(BusSearchE.this, StartActivity.class);
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
        Intent intent = new Intent(BusSearchE.this, StartActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
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
        progressBar.setVisibility(View.VISIBLE);

        JSONObject jsonAuthObject = new JSONObject();
        try {
            jsonAuthObject.put("username", KEY_USERNAME_API);
            jsonAuthObject.put("service_token", KEY_SERVICE_TOKEN);
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
                        progressBar.setVisibility(View.GONE);

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
                                                    currentRoute.getString("end_route"),
                                                    currentRoute.getDouble("route_fare"),
                                                    currentRoute.getString("route_uuid")
                                            );

                                    routes.add(r);

                                    route_ArrayList.add(s);

                                }

                                // Creating adapter for spinner
                                adapter_Route = new ArrayAdapter<>(BusSearchE.this, android.R.layout.simple_spinner_dropdown_item, route_ArrayList);

                                // Drop down layout style - list view with radio button
                                adapter_Route.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);


                                // attaching data adapter to spinner

                                //attach adapter to spinner
                                spinner_Route.setAdapter(adapter_Route);

                                //    Log.d("Route", adapterRoute.toString());

                            } else {

                                progressBar.setVisibility(View.GONE);
//
//
//                                Toast.makeText(getApplicationContext(), "No bus routes available.", Toast.LENGTH_LONG).show();
//                                Intent intent = new Intent(BusSearchE.this,StartActivity.class);
//                                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
//                                startActivity(intent);
//                                finish();

                                AlertDialog.Builder builder = new AlertDialog.Builder(BusSearchE.this);
                                builder.setCancelable(false);
                                builder.setMessage("There are no bus routes defined on the system, kindly contact the System Administrator.");
                                builder.setPositiveButton("Ok",
                                        new DialogInterface.OnClickListener() {
                                            public void onClick(DialogInterface dialog, int id) {
                                                Intent intent = new Intent(BusSearchE.this,StartActivity.class);
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
//                            AlertDialog.Builder builder = new AlertDialog.Builder(BusSearchE.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("There was an error retrieving the bus routes, kindly check your internet connection and try again.");
//                            builder.setPositiveButton("Ok",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//                                            Intent intent = new Intent(BusSearchE.this,StartActivity.class);
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
                        //Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show()
                        //Handle Errors here
                        //   progressDialog.dismiss();
                        Log.d("Error.Response", error.toString());
                        // Log.d("Error.Response", error.getMessage());
//                        DialogBox.mLovelyStandardDialog(BusSearch.this, error.toString());
                        // startActivity(new Intent( BuyFromTrader.this,MainActivity.class));
                        progressBar.setVisibility(View.GONE);
//
//                        Toast.makeText(getApplicationContext(), "Connection failure, kindly check your internet connection.", Toast.LENGTH_LONG).show();
//                        Intent intent = new Intent(BusSearchE.this,StartActivity.class);
//                        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
//                        startActivity(intent);
//                        finish();

                        AlertDialog.Builder builder = new AlertDialog.Builder(BusSearchE.this);
                        builder.setCancelable(false);
                        builder.setMessage("Connection failure, kindly check your internet connection and try again.");
                        builder.setPositiveButton("Ok",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                        Intent intent = new Intent(BusSearchE.this,StartActivity.class);
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
