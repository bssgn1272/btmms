package com.innovitrix.napsamarketsales;

import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
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
import com.google.android.material.textfield.TextInputLayout;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LOGIN_STATUS;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_PASSWORD;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_SERVICE_TOKEN;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_USERNAME_API;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_VOLLEY_SOCKET_TIMEOUT_MS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_AUTHENTICATE_MARKETER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_UPDATE_PIN;

public class ChangePin extends AppCompatActivity {

    private Button button_Save;
    private ProgressBar progressBar;
    private ProgressDialog progressDialog;
    private TextInputLayout textInputLayout_Pin_Current, textInputLayout_Pin_New, textInputLayout_Pin_Confirm;

    RequestQueue queue;
    String trader_mobile_number;
    String pin;
    String pin_current_input;
    String pin_new_input;
    String pin_confirm_input;
    int current_password_char;
    int new_password_char;
    int confirm_password_char;
    TextView textViewUsername;
    TextView textViewDate;
    String login_status;
    String confirm_message;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_change_pin);
        getSupportActionBar().setSubtitle("change pin");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        progressDialog = new ProgressDialog(ChangePin.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        progressBar = (ProgressBar) findViewById(R.id.progressBar);
        queue = Volley.newRequestQueue(this);

        textViewUsername = (TextView) findViewById(R.id.textViewUsername);
        textViewUsername.setText("Logged in as " + SharedPrefManager.getInstance(ChangePin.this).getUser().getFirstname() + " " + SharedPrefManager.getInstance(ChangePin.this).getUser().getLastname());
        textViewDate = (TextView) findViewById(R.id.textViewDate);
        textViewDate.setText(SharedPrefManager.getInstance(ChangePin.this).getTranactionDate2());

        textInputLayout_Pin_Current = (TextInputLayout) findViewById(R.id.pin_current_TextInputLayout);
        textInputLayout_Pin_New = (TextInputLayout) findViewById(R.id.pin_new_TextInputLayout);
        textInputLayout_Pin_Confirm = (TextInputLayout) findViewById(R.id.pin_confirm_TextInputLayout);

        textInputLayout_Pin_Current.requestFocus();

       // trader_mobile_number = SharedPrefManager.getInstance(ChangePin.this).getUser().getMobile_number();
        pin = getIntent().getStringExtra(KEY_PASSWORD);
        login_status = getIntent().getStringExtra(KEY_LOGIN_STATUS);

        if (!TextUtils.isEmpty(pin)) {
            // trader_mobile_number = getIntent().getStringExtra(KEY_MOBILE);
            getSupportActionBar().setSubtitle("change OTP");
            textViewUsername.setText(null);
            textInputLayout_Pin_Current.setFocusable(false);
            textInputLayout_Pin_Current.setFocusableInTouchMode(false);
            textInputLayout_Pin_Current.requestFocus();
            trader_mobile_number =  getIntent().getStringExtra(KEY_MOBILE);
            confirm_message = "Confirm pin change for "+ trader_mobile_number + "?";
        }
        else {
            trader_mobile_number = SharedPrefManager.getInstance(ChangePin.this).getUser().getMobile_number();
            confirm_message = "Confirm pin change for " + SharedPrefManager.getInstance(ChangePin.this).getUser().getFirstname() + " " + SharedPrefManager.getInstance(ChangePin.this).getUser().getLastname() + "?";

        }

        textInputLayout_Pin_Current.getEditText().setText(pin);
        button_Save = (Button) findViewById(R.id.buttonSubmitCP);

        button_Save.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (!validatePinCurrent() || !validatePinNew() || !validatePinConfirm()) {
                    return;
                } else {

                    //validating inputs

                    int all_fine = 1;


                    if (!pin_new_input.equals(pin_confirm_input)) {
                        all_fine = 0;
                        AlertDialog.Builder builder = new AlertDialog.Builder(ChangePin.this);
                        builder.setMessage("New pin do not match.");
                        builder.setPositiveButton("Ok",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                        return;
                                        //Intent intent = new Intent(ResetPin.this,LoginActivity.class);
                                        // startActivity(intent);
                                    }
                                });
                        builder.create().show();

                        //     DialogBox.mLovelyStandardDialog(ChangePin.this,"Password not equal");

                    }
                    if (pin_current_input.equals(pin_new_input)) {
                        all_fine = 0;
                        AlertDialog.Builder builder = new AlertDialog.Builder(ChangePin.this);
                        builder.setMessage("Your current and new pin should not be the same.");
                        builder.setPositiveButton("Ok",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                      textInputLayout_Pin_Current.requestFocus();
                                        return;
                                        //Intent intent = new Intent(ResetPin.this,LoginActivity.class);
                                        // startActivity(intent);
                                    }
                                });
                        builder.create().show();

                        //     DialogBox.mLovelyStandardDialog(ChangePin.this,"Password not equal");

                    }
                    if (all_fine == 1) {
                        AlertDialog.Builder builder = new AlertDialog.Builder(ChangePin.this);
                        builder.setCancelable(false);
                        builder.setMessage(confirm_message);
                        builder.setPositiveButton("Yes",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {

                                        userLogin();

                                    }
                                });

                        builder.setNegativeButton("No",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                              /*  Intent intent = new Intent(ChangePin.this, MainActivity.class);
                                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                                startActivity(intent);
                                finish();

                               */
                                        dialog.cancel();
                                    }
                                });

                        builder.create().show();
                    }
                }
            }
        });
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                //do whatever
                Intent intent = new Intent(ChangePin.this, MainActivity.class);
                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
                finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    @Override
    public void onBackPressed() {
        // Toast.makeText(getApplication(),"Use the in app controls to navigate.",Toast.LENGTH_SHORT).show();
        Intent intent = new Intent(ChangePin.this, MainActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
    }

    private void userLogin() {

        //getting values from edit texts

        progressBar.setVisibility(View.VISIBLE);
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
            jsonPayloadObject.put("mobile", trader_mobile_number);
            jsonPayloadObject.put("pin", pin_current_input);

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
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, URL_AUTHENTICATE_MARKETER, jsonObject,
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


                            //Check if the object has the key.
                            if (obj.getJSONObject("response").has("AUTHENTICATION")) {
                                sendInformation(trader_mobile_number, pin_new_input);
                            } else {
                                progressBar.setVisibility(View.GONE);
                                AlertDialog.Builder builder = new AlertDialog.Builder(ChangePin.this);
                                builder.setCancelable(false);
                                builder.setMessage("Authentication failed: Invalid pin..");
                                builder.setPositiveButton("Ok",
                                        new DialogInterface.OnClickListener() {
                                            public void onClick(DialogInterface dialog, int id) {
                                                //Intent intent = new Intent(ResetPin.this,LoginActivity.class);
                                                // startActivity(intent);
                                                return;
                                            }
                                        });
                                builder.create().show();
                                //fetchTrader();
                            }
                        } catch (JSONException e) {
                                                        e.printStackTrace();
                            //     DialogBox.mLovelyStandardDialog(LoginActivity.this,e.getMessage());
                            progressBar.setVisibility(View.GONE);
//                            AlertDialog.Builder builder = new AlertDialog.Builder(ChangePin.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("An error occurred while changing the pin, kindly check your internet connection and try again.");
//                            builder.setPositiveButton("Ok",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//                                            //Intent intent = new Intent(BusBuyTicketBuyerDetailsE.this,BusSearch.class);
//                                            // intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
//                                            //startActivity(intent);
//                                            // finish();
//                                            dialog.cancel();
//                                        }
//                                    });
//                            builder.create().show();
                        }

                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.d("Error.Response", error.toString());
                //       Log.d("Error.Response", error.getMessage());
                progressBar.setVisibility(View.GONE);
                AlertDialog.Builder builder = new AlertDialog.Builder(ChangePin.this);
                builder.setCancelable(false);
                builder.setMessage("Connection failure, kindly check your internet connection and try again.");
                builder.setPositiveButton("Ok",
                        new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                //Intent intent = new Intent(ResetPin.this,LoginActivity.class);
                                // startActivity(intent);
                            }
                        });
                builder.create().show();
                //  DialogBox.mLovelyStandardDialog(ChangePin.this,   error.toString());
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
                params.put("mobile_number", trader_mobile_number);
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


    public void sendInformation(
            final String mobile_number,
            String pin
    ) {

        progressBar.setVisibility(View.VISIBLE);
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
            jsonPayloadObject.put("mobile", mobile_number);
            jsonPayloadObject.put("pin", pin);

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

        // progressDialog.show();

        ///prepare your JSONObject which you want to send in your web service request
// Calendar.getInstance().getTime()


        // prepare the Request
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, URL_UPDATE_PIN, jsonObject,
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


                            //Check if the object has the key.
                            if (obj.getJSONObject("response").has("AUTHENTICATION")) {

//                                AlertDialog.Builder builder = new AlertDialog.Builder(ChangePin.this);
//                                builder.setMessage("Pin has been changed.");
//                                builder.setPositiveButton("Ok",
//                                        new DialogInterface.OnClickListener() {
//                                            public void onClick(DialogInterface dialog, int id) {
//                                                 Intent intent = new Intent(ChangePin.this, StartActivity.class);
//
//                                                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
//                                                editTextCurrentPassword.setText("");
//                                                editTextConfirmPassword.setText("");
//                                                editTextNewPassword.setText("");
//                                                startActivity(intent);
//                                                finish();
//                                            }
//                                        });
//                                builder.create().show();

                                progressBar.setVisibility(View.GONE);

                                AlertDialog.Builder builder = new AlertDialog.Builder(ChangePin.this);
                                builder.setCancelable(false);
                                builder.setMessage("Your pin was changed successfully.");
                                builder.setPositiveButton("Ok",
                                        new DialogInterface.OnClickListener() {
                                            public void onClick(DialogInterface dialog, int id) {
                                                Intent intent = new Intent(ChangePin.this, StartActivity.class);
                                                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                                startActivity(intent);
                                                finish();
                                            }
                                        });
                                builder.create().show();


                            } else {
                                progressBar.setVisibility(View.GONE);
                                AlertDialog.Builder builder = new AlertDialog.Builder(ChangePin.this);
                                builder.setCancelable(false);
                                builder.setMessage("Pin was not changed.");
                                builder.setPositiveButton("Ok",
                                        new DialogInterface.OnClickListener() {
                                            public void onClick(DialogInterface dialog, int id) {
                                                //Intent intent = new Intent(ResetPin.this,LoginActivity.class);
                                                // startActivity(intent);
                                                dialog.cancel();
                                            }
                                        });
                                builder.create().show();
                                // DialogBox.mLovelyStandardDialog(ChangePin.this,"Pin change not successful.");
                                //           progressBar.setVisibility(View.GONE);
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                            progressBar.setVisibility(View.GONE);
//                            AlertDialog.Builder builder = new AlertDialog.Builder(ChangePin.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("Connection failure, kindly check your internet connection and try again.");
//                            builder.setPositiveButton("Ok",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//                                            dialog.cancel();
//                                        }
//                                    });
//                            builder.create().show();

                        }

                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.d("Error.Response", error.toString());
                progressBar.setVisibility(View.GONE);
                AlertDialog.Builder builder = new AlertDialog.Builder(ChangePin.this);
                builder.setCancelable(false);
                builder.setMessage("Connection failure, kindly check your internet connection and try again.");
                builder.setPositiveButton("Ok",
                        new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                //Intent intent = new Intent(ResetPin.this,LoginActivity.class);
                                // startActivity(intent);
                                dialog.cancel();
                            }
                        });
                builder.create().show();
                //       Log.d("Error.Response", error.getMessage());
                //   startActivity(new Intent(LoginActivity.this, MainActivity.class)); //TODO Change when the API server is reachable

                // DialogBox.mLovelyStandardDialog(ChangePin.this,   "Server not reachable.");
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
                params.put("mobile_number", mobile_number);
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


    private boolean validatePinCurrent() {

        pin_current_input = textInputLayout_Pin_Current.getEditText().getText().toString().trim();
        if (pin_current_input.isEmpty() | pin_current_input.length() < 5) {
            textInputLayout_Pin_Current.setErrorEnabled(true);
            textInputLayout_Pin_Current.setError("Enter a valid 5 digit pin.");
            textInputLayout_Pin_Current.requestFocus();
            return false;
        } else {
            textInputLayout_Pin_Current.setError(null);
            return true;
        }
    }

    private boolean validatePinNew() {
        pin_new_input = textInputLayout_Pin_New.getEditText().getText().toString().trim();
        if (pin_new_input.isEmpty() | pin_new_input.length() < 5) {
            textInputLayout_Pin_New.setErrorEnabled(true);
            textInputLayout_Pin_New.setError("Enter a valid 5 digit pin.");
            textInputLayout_Pin_New.requestFocus();
            return false;
        } else {
            textInputLayout_Pin_New.setError(null);
            return true;
        }
    }

    private boolean validatePinConfirm() {

        pin_confirm_input = textInputLayout_Pin_Confirm.getEditText().getText().toString().trim();
        if (pin_confirm_input.isEmpty() | pin_new_input.length() < 5) {
            textInputLayout_Pin_Confirm.setErrorEnabled(true);
            textInputLayout_Pin_Confirm.setError("Enter a valid 5 digit pin.");
            textInputLayout_Pin_Confirm.requestFocus();
            return false;
        } else {
            textInputLayout_Pin_New.setError(null);
            return true;
        }
    }
}
