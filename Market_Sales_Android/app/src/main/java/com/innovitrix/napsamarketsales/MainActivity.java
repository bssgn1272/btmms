package com.innovitrix.napsamarketsales;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Build;
import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;
import androidx.cardview.widget.CardView;
import androidx.recyclerview.widget.LinearLayoutManager;
import android.util.Log;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.models.MenuAdapter;
import com.innovitrix.napsamarketsales.models.MenuData;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

//Recyclerview
import androidx.recyclerview.widget.RecyclerView;

import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.List;

import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHECK_BALANCE;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_ENDPOINT_TEST;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_MOBILE_NUMBER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_USER_ID;

public class MainActivity extends AppCompatActivity implements View.OnClickListener {


    private RecyclerView recyclerView;
    private MenuAdapter menuAdapter;
    private List<MenuData> menuDataList;

    @TargetApi(Build.VERSION_CODES.O)
    private ProgressDialog progressDialog;
    RequestQueue queue;


    private CardView cardViewMenuSaleToBuyer, cardViewMenuBuyFromTrader,cardViewCheckBalance,cardViewChangePin,cardViewBuBusTicket;
    TextView textViewBalance;
    TextView textViewUsername;
    String mobile_number;
    String stringAmount;
    int userObjectLength ;
    TextView txtViewDate;

    private long backPressedTime;
    private Toast backToast;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        progressDialog = new ProgressDialog(MainActivity.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);
        textViewUsername = (TextView)findViewById(R.id.textViewUsername);
        textViewUsername.setText("Welcome " + SharedPrefManager.getInstance(MainActivity.this).getUser().getFirstname()+ " "+SharedPrefManager.getInstance(MainActivity.this).getUser().getLastname());
        txtViewDate = (TextView)findViewById(R.id.textViewDate);
        txtViewDate.setText(SharedPrefManager.getInstance(MainActivity.this).getTranactionDate2());

        menuDataList = new ArrayList<>();

        menuDataList.add(new MenuData(1, "Make a sale", R.drawable.ic_local_shipping_black_24dp,R.drawable.circle_bakcground_sales));
        menuDataList.add(new MenuData(2, "Make an order",R.drawable.ic_local_shipping_black_24dp,R.drawable.circle_background_order));

//         menuDataList.add(new MenuData(5, "View Transactions",R.drawable.ic_account_balance_black_24dp,R.drawable.circle_bakcground_sales));
        menuDataList.add(new MenuData(3, "Sale bus ticket", R.drawable.ic_directions_bus_black_24dp,R.drawable.circle_bakcground_sales ));
        menuDataList.add(new MenuData(4, "Pay market fees", R.drawable.ic_directions_bus_black_24dp,R.drawable.circle_background_bus));
        menuDataList.add(new MenuData(5, "Check sales",R.drawable.ic_attach_money_black_24dp,R.drawable.circle_background_balance));
        menuDataList.add(new MenuData(6, "Change pin",R.drawable.ic_lock_open_black_24dp,R.drawable.circle_background_pin));
        menuDataList.add(new MenuData(7, "Logout", R.drawable.ic_power_settings_new_black_24dp,R.drawable.circle_background_logout ));


        recyclerView = (RecyclerView) findViewById(R.id.recyclerViewMenu);
        menuAdapter = new MenuAdapter(this,menuDataList);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
        recyclerView.setAdapter(menuAdapter);


    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                //do whatever
                Intent intent = new Intent(getApplicationContext(), StartActivity.class);
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
        Intent intent = new Intent(MainActivity.this, StartActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
    }

   /* @Override
    protected void onResume() {
        super.onResume();

    }
*/

    @Override
    public void onClick(View view) {



            }




    }

