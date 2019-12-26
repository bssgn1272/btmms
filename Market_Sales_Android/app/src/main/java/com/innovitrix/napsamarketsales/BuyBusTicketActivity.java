package com.innovitrix.napsamarketsales;

import android.app.LauncherActivity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.CardView;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.view.inputmethod.EditorInfo;
import android.widget.ArrayAdapter;
import android.widget.SearchView;
import android.widget.Spinner;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.models.RoutePlanned;
import com.innovitrix.napsamarketsales.models.RoutePlannedAdapter;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_MOBILE_NUMBER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_ROUTE_ID;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_ROUTES;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_USERS;

public class BuyBusTicketActivity extends AppCompatActivity {

    private RecyclerView recyclerView;
    private CardView cardViewRoutePlanned;
    RoutePlannedAdapter routePlannedAdapter;
    private List<RoutePlanned> routesPlanned;
    private SearchView searchView;
    private String route_id;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_buy_bus_ticket);

        setUpRecyclerView();

        routesPlanned = new ArrayList<>();
      //  routePlannedAdapter = new RoutePlannedAdapter(getApplicationContext(),routesPlanned);
        loadRoutesPlanned();

        searchView = (SearchView) findViewById(R.id.searchview_route);
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String s) {
                return false;
            }

            @Override
            public boolean onQueryTextChange(String s) {
                routePlannedAdapter.getFilter().filter(s);
                return false;
            }
        });
    }

    private void setUpRecyclerView() {
        recyclerView = (RecyclerView) findViewById(R.id.recylerview_route);
        recyclerView.setHasFixedSize(true);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));


    }
    private void loadRoutesPlanned(){

        final ProgressDialog  progressDialog = new ProgressDialog(this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        progressDialog.show();

        StringRequest stringRequest = new StringRequest(Request.Method.GET, URL_ROUTES
                //URL_CHAR_AMPERSAND +
                //URL_PARAM_USER_ID + 1
                ,

                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        progressDialog.dismiss();

                    //    progressBar.setVisibility(View.GONE);

                        try {
                            //converting response to json object
                            JSONObject obj = new JSONObject(String.valueOf(response));

                            //if no error in response
                            if (!obj.getBoolean("error")) {
                                Toast.makeText(getApplicationContext(), obj.getString("error"), Toast.LENGTH_SHORT).show();

                                //getting the user from the response

                                //creating a new user object
                                JSONArray array = obj.getJSONArray("routes");

                                for (int i = 0; i< array.length();i++) {

                                    JSONObject currentRoute = array.getJSONObject(i);
                                    RoutePlanned rp = new RoutePlanned(
                                            "Power Tools",
                                            "ABZ "+ (i+1) +"01",
                                            1,
                                            currentRoute.getDouble("fare"),
                                            "1",
                                            currentRoute.getString("origin") + " To " +currentRoute.getString("destination"),
                                            currentRoute.getString("origin"),
                                            currentRoute.getString("destination")
                                    );
                                    routesPlanned.add(rp);
                                }
                                routePlannedAdapter = new RoutePlannedAdapter(getApplicationContext(),routesPlanned);
                                recyclerView.setAdapter(routePlannedAdapter);
                            } else {
                                Toast.makeText(getApplicationContext(), obj.getString("message"), Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }},
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        progressDialog.dismiss();
                        Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override



            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                // params.put("username", trader_id);
                //params.put("email", firstname);
                // params.put("password", lastname);
                params.put("route_id", route_id);
                return params;
            }
        };

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(stringRequest);


    }
}
