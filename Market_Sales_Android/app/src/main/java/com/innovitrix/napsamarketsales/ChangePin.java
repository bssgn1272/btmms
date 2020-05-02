package com.innovitrix.napsamarketsales;

import android.app.ActionBar;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.provider.Settings;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.dialog.DialogBox;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import static android.text.InputType.TYPE_NULL;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_PASSWORD;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_NAME;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_AUTHENTICATE_MARKETER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_TRANSACTIONS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_UPDATE_PIN;

public class ChangePin extends AppCompatActivity {

    private EditText editTextCurrentPassword, editTextNewPassword, editTextConfirmPassword;
    private Button button_Save;
    private ProgressBar progressBar;
    private ProgressDialog progressDialog;
    RequestQueue queue;
    String   trader_mobile_number;
    String pin;
    String current_password ;
   String new_password  ;
   String confirm_password ;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_change_pin);
        getSupportActionBar().setSubtitle("Change pin");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        progressDialog = new ProgressDialog(ChangePin.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);
        editTextCurrentPassword = (EditText) findViewById(R.id.editTextCurrentPassword);
        editTextNewPassword = (EditText) findViewById(R.id.editTextNewPassword);
        editTextConfirmPassword = (EditText) findViewById(R.id.editTextConfirmPassword);
        editTextCurrentPassword.requestFocus();
        trader_mobile_number= SharedPrefManager.getInstance(ChangePin.this).getUser().getMobile_number();
        pin = getIntent().getStringExtra(KEY_PASSWORD);

        if (!TextUtils.isEmpty(pin))
        {
            trader_mobile_number = getIntent().getStringExtra(KEY_MOBILE);
            getSupportActionBar().setSubtitle("Change OTP");
            editTextCurrentPassword.setFocusable(false);
            editTextCurrentPassword.setFocusableInTouchMode(false);
           // editTextCurrentPassword.setInputType(TYPE_NULL);
            editTextNewPassword.requestFocus();
        }


        editTextCurrentPassword.setText(pin);
        button_Save = (Button) findViewById(R.id.buttonSubmitCP);

        button_Save.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                AlertDialog.Builder builder = new AlertDialog.Builder(ChangePin.this);
                builder.setMessage("Confirm password change?");
                builder.setPositiveButton("Yes",
                        new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {

                                current_password = editTextCurrentPassword.getText().toString().trim();
                                new_password  = editTextNewPassword.getText().toString().trim();
                                confirm_password = editTextConfirmPassword.getText().toString().trim();
                                //validating inputs

                                if (TextUtils.isEmpty(current_password)) {
                                    editTextCurrentPassword.setError("Please enter your current password");
                                    editTextCurrentPassword.requestFocus();
                                    return;
                                }

                                if (TextUtils.isEmpty(new_password)) {
                                    editTextNewPassword.setError("Please enter your new password");
                                    editTextNewPassword.requestFocus();
                                    return;
                                }
                                if (TextUtils.isEmpty(confirm_password)) {
                                    editTextConfirmPassword.setError("Please re-enter your new password");
                                    editTextConfirmPassword.requestFocus();
                                    return;
                                }
                                //progressBar.setVisibility(View.VISIBLE);

                                ///prepare your JSONObject which you want to send in your web service request
                                if(!new_password.equals(confirm_password))
                                {
                                    DialogBox.mLovelyStandardDialog(ChangePin.this,"Password not equal");
                                    return;
                                }

                                if (!TextUtils.isEmpty(pin))
                                {
                                    sendInformation( trader_mobile_number,new_password);
                                }
                                else
                                    {
                                userLogin();
                                }
                            }
                        });

                builder.setNegativeButton("No",
                        new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                dialog.cancel();
                            }
                        });

                builder.create().show();
            }
        });
    }

    private void userLogin() {

        //getting values from edit texts


        JSONObject jsonAuthObject= new JSONObject();
        try {
            jsonAuthObject.put("username","admin");
            jsonAuthObject.put("service_token","JJ8DJ7S66DMA5");
        } catch (JSONException e) {
            e.printStackTrace();
        }


        //PAYLOAD
        JSONObject jsonPayloadObject = new JSONObject();
        try {
            jsonPayloadObject.put("mobile",trader_mobile_number);
            jsonPayloadObject.put("pin", current_password);

        } catch (JSONException e) {
            e.printStackTrace();
        }


        ///prepare your JSONObject which you want to send in your web service request
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("auth",jsonAuthObject);
            jsonObject.put("payload",jsonPayloadObject);
        } catch (JSONException e) {
            e.printStackTrace();
        }
        // prepare the Request
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, URL_AUTHENTICATE_MARKETER, jsonObject,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {

                        //Do stuff here
                        // display response

                        Log.d("Response", response.toString());
//                        progressBar.setVisibility(View.GONE);

                        try {
                            //converting response to json object
                            JSONObject obj = new JSONObject(String.valueOf(response));


                            //Check if the object has the key.
                            if(obj.getJSONObject("response").has("AUTHENTICATION")){
                                sendInformation(trader_mobile_number,new_password);
                            }
                            else
                            {
                                DialogBox.mLovelyStandardDialog(ChangePin.this,"Current password is incorrect.");
                            //    progressBar.setVisibility(View.GONE);
                            }
                        } catch (JSONException e) {
                            //     DialogBox.mLovelyStandardDialog(LoginActivity.this,e.getMessage());
                          //  progressBar.setVisibility(View.GONE);

                            e.printStackTrace();
                        }

                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.d("Error.Response", error.toString());
                //       Log.d("Error.Response", error.getMessage());

                DialogBox.mLovelyStandardDialog(ChangePin.this,   error.toString());
                //progressBar.setVisibility(View.GONE);
                // Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
            }
        }) {
            @Override

            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                // params.put("username", trader_id);
                //params.put("email", firstname);
                // params.put("password", lastname);
                params.put("mobile_number", trader_mobile_number );
                return params;
            }
        };

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonObjectRequest);
    }


    public void sendInformation(
            final String mobile_number,
            String pin
    )
    {


        JSONObject jsonAuthObject = new JSONObject();
        try {
            jsonAuthObject.put("username","admin");
            jsonAuthObject.put("service_token","JJ8DJ7S66DMA5");
        } catch (JSONException e) {
            e.printStackTrace();
        }


        //PAYLOAD
        JSONObject jsonPayloadObject = new JSONObject();
        try {
            jsonPayloadObject.put("mobile",mobile_number);
            jsonPayloadObject.put("pin", pin);

        } catch (JSONException e) {
            e.printStackTrace();
        }


        ///prepare your JSONObject which you want to send in your web service request
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("auth",jsonAuthObject);
            jsonObject.put("payload",jsonPayloadObject);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        // progressDialog.show();

        ///prepare your JSONObject which you want to send in your web service request
// Calendar.getInstance().getTime()


        // prepare the Request
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST,URL_UPDATE_PIN, jsonObject,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {

                        //Do stuff here
                        // display response

                        Log.d("Response", response.toString());
                   //     progressBar.setVisibility(View.GONE);

                        try {
                            //converting response to json object
                            JSONObject obj = new JSONObject(String.valueOf(response));


                            //Check if the object has the key.
                            if(obj.getJSONObject("response").has("AUTHENTICATION")){


                                //    Toast.makeText(getApplicationContext(), obj.getString("message"), Toast.LENGTH_SHORT).show();

                                //getting the user from the response
                                Toast.makeText(getApplicationContext(), "Change pin successful", Toast.LENGTH_LONG).show();
                                startActivity(new Intent(ChangePin.this, MainActivity.class));
                                //DialogBox.mLovelyStandardDialog(ChangePin.this,"Pin change successful.");
                                editTextCurrentPassword.setText("");
                                editTextConfirmPassword.setText("");
                                editTextNewPassword.setText("");
                            }
                            else
                            {
                                DialogBox.mLovelyStandardDialog(ChangePin.this,"Pin change not successful.");
                     //           progressBar.setVisibility(View.GONE);
                            }
                        } catch (JSONException e) {
                            //     DialogBox.mLovelyStandardDialog(LoginActivity.this,e.getMessage());
                     //       progressBar.setVisibility(View.GONE);

                            e.printStackTrace();
                        }

                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.d("Error.Response", error.toString());
                //       Log.d("Error.Response", error.getMessage());
                //   startActivity(new Intent(LoginActivity.this, MainActivity.class)); //TODO Change when the API server is reachable

                DialogBox.mLovelyStandardDialog(ChangePin.this,   "Server not reachable.");
               // progressBar.setVisibility(View.GONE);
                // Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
            }
        }) {
            @Override

            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                // params.put("username", trader_id);
                //params.put("email", firstname);
                // params.put("password", lastname);
                params.put("mobile_number", mobile_number );
                return params;
            }
        };

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonObjectRequest);
    }





}
