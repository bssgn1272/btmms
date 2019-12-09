package com.innovitrix.napsamarketsales;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.AppCompatButton;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
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
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.models.User;
//import com.innovitrix.napsamarketsales.;
//import com.innovitrix.reards.models.Customer;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

import static com.innovitrix.napsamarketsales.network.NetworkMonitor.checkNetworkConnection;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_LOGIN;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_MOBILE_NUMBER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_USERS;

/*import static com.innovitrix.reards.network.NetworkMonitor.checkNetworkConnection;
import static com.innovitrix.reards.utils.AppConstants.KEY_COMPANY_ID;
import static com.innovitrix.reards.utils.AppConstants.KEY_DEFAULT_COMPANY_ID;
import static com.innovitrix.reards.utils.AppConstants.KEY_EMAIL_OR_PHONE;
import static com.innovitrix.reards.utils.AppConstants.KEY_PASSWORD;
import static com.innovitrix.reards.utils.UrlEndpoints.API_KEY;
import static com.innovitrix.reards.utils.UrlEndpoints.URL_LOGIN;
*/

public class LoginActivity extends AppCompatActivity implements View.OnClickListener {

    //Defining views
    private EditText editTextEmail, editTextPassword;
    private Button buttonLogin;
    private TextView textRegisterLink, textPassRestLink,textCardSingInLink;

    ProgressBar progressBar;

    public String TAG_FIREBASE = "Firebase";
    private String TAG = LoginActivity.class.getSimpleName();

    String androidDeviceId, deviceSerial, deviceName, serialNumber;

    ProgressDialog progressDialog;


    // Database Helper
    //DBHelper db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        //initializing views
        editTextEmail = (EditText) findViewById(R.id.editTextEmail);
        //editTextPassword = (EditText) findViewById(R.id.editTextPassword);

        buttonLogin = findViewById(R.id.buttonLogin);
        buttonLogin.setOnClickListener(this);


      //  textCardSingInLink = (TextView) findViewById(R.id.linkCardSignIn);
      //  textCardSingInLink.setOnClickListener(this);


        //textRegisterLink = (TextView) findViewById(R.id.linkSignup);
        //textRegisterLink.setOnClickListener(this);

        //textPassRestLink = (TextView) findViewById(R.id.linkPasswordReset);
        //textPassRestLink.setOnClickListener(this);

        progressBar = (ProgressBar) findViewById(R.id.progressBar);

        serialNumber = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);

        deviceName = Build.BRAND +" "+ Build.MODEL;

        //db = new DBHelper(LoginActivity.this);

        progressDialog = new ProgressDialog(LoginActivity.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);

        if (!checkNetworkConnection(LoginActivity.this)) {

        //    DialogBox.mLovelyStandardDialog(LoginActivity.this, R.string.error_timeout);

        }

    }

    private void userLogin() {

        //getting values from edit texts
        final String email_or_mobile = editTextEmail.getText().toString().trim();
       // final String password = editTextPassword.getText().toString().trim();


        //validating inputs
        if (TextUtils.isEmpty(email_or_mobile)) {
            editTextEmail.setError("Please enter your email or mobile number");
            editTextEmail.requestFocus();
            return;
        }

//        if (TextUtils.isEmpty(password)) {
//            editTextPassword.setError("Please enter your password");
//            editTextPassword.requestFocus();
//            return;
//        }


        progressBar.setVisibility(View.VISIBLE);

        //creating a string request

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, URL_USERS +
                URL_CHAR_QUESTION +
                URL_PARAM_MOBILE_NUMBER +   editTextEmail.getText()
                //URL_CHAR_AMPERSAND +
                //URL_PARAM_USER_ID + 1
                , null, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                // display response
               // progressDialog.dismiss();
//                        Toasting.Toast_Long(getApplicationContext(), response);

                        try {

                            //converting response to json object



                                //Toast.makeText(getApplicationContext(), obj.getString("message"), Toast.LENGTH_SHORT).show();



                                JSONObject currentUser = response.getJSONObject("users");

                                com.innovitrix.napsamarketsales.models.User mUser = new com.innovitrix.napsamarketsales.models.User(
                                        currentUser.getInt("trader_id"),
                                        currentUser.getString("firstname"),
                                        currentUser.getString("lastname"),
                                        currentUser.getString("nrc"),
                                        currentUser.getString("gender"),
                                        currentUser.getString("mobile_number")

                                );


                                //storing the user in shared preferences
                               SharedPrefManager.getInstance(getApplicationContext()).storeCurrentUser(mUser);

//                                if (SharedPrefManager.getInstance(getApplicationContext()).getDeviceToken() != null) {
//                                    Log.d("FCMTokenShared", SharedPrefManager.getInstance(getApplicationContext()).getDeviceToken());
//                                    sendTokenToServer();
//                                }

                                finish();
                                //starting profile activity
                                startActivity(new Intent(LoginActivity.this,MainActivity.class));



                        } catch (JSONException e) {
                            e.printStackTrace();
                        }

                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

                progressBar.setVisibility(View.GONE);

                //Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_LONG).show();
                Log.e("VolleyError", "error: " + error.toString());



            }
        }){

            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                Map<String, String> headers  = new HashMap<>();
                //adding parameters to request
                headers.put("Authorization", "xxxx");

                //returning parameter
                return headers ;
            }
        };

        //adding the String request to the queue
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonObjectRequest);
    }




    @Override
    public void onClick(View view) {

        if (view == buttonLogin) {
            //calling the storeCurrentUser function
            userLogin();
        } else if (view == textCardSingInLink) {
            finish();
          //  startActivity(new Intent(getApplicationContext(), LoginCardActivity.class));
        } else if (view == textRegisterLink) {
            finish();
           // startActivity(new Intent(getApplicationContext(), RegisterActivity.class));
        } else if (view == textPassRestLink) {
            finish();
          //  startActivity(new Intent(getApplicationContext(), PasswordResetRequestActivity.class));
        }
    }

}