package com.innovitrix.napsamarketsales;

import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.text.Editable;
import android.text.InputFilter;
import android.text.InputType;
import android.text.Selection;
import android.text.Spanned;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.text.method.NumberKeyListener;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.google.android.material.textfield.TextInputLayout;
//import com.innovitrix.napsamarketsales.;
//import com.innovitrix.reards.models.Customer;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;


import static com.innovitrix.napsamarketsales.network.NetworkMonitor.checkNetworkConnection;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LOGIN_STATUS;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_PASSWORD;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_SERVICE_TOKEN;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_USERNAME_API;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_VOLLEY_SOCKET_TIMEOUT_MS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_AUTHENTICATE_MARKETER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_MARKETER_KYC;

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
    private Button buttonLogin;
    private TextView textRegisterLink, textPassRestLink, textCardSingInLink;

    private TextInputLayout textInputLayout_Mobile_Number, textInputLayout_Pin;

    ProgressBar progressBar;
    ProgressDialog progressDialog;
    public String TAG_FIREBASE = "Firebase";
    private String TAG = LoginActivity.class.getSimpleName();

    String androidDeviceId, deviceSerial, deviceName, serialNumber;

    String pin_input;
    String mobile_number_input;
    int mobile_number_char;
    int email_or_mobile_char;
    int password_char;
    String blockCharacterSet = "123456789";
    // Database Helper
    //DBHelper db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        getSupportActionBar().setSubtitle("login");

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        //initializing views

        textInputLayout_Mobile_Number = (TextInputLayout) findViewById(R.id.mobile_number_TextInputLayout);
        textInputLayout_Pin = (TextInputLayout) findViewById(R.id.pin_TextInputLayout);


        //  editTextEmail = (EditText) findViewById(R.id.editTextEmail);
        //editTextPassword = (EditText) findViewById(R.id.editTextPassword);

        buttonLogin = findViewById(R.id.buttonLogin);
        buttonLogin.setOnClickListener(this);


        //  textCardSingInLink = (TextView) findViewById(R.id.linkCardSignIn);
        //  textCardSingInLink.setOnClickListener(this);


        //textRegisterLink = (TextView) findViewById(R.id.linkSignup);
        //textRegisterLink.setOnClickListener(this);

        textPassRestLink = (TextView) findViewById(R.id.linkPasswordReset);
        textPassRestLink.setOnClickListener(this);
        textInputLayout_Mobile_Number.getEditText().addTextChangedListener(new LoginActivity.PhoneNumberTextWatcher());
        textInputLayout_Mobile_Number.getEditText().setFilters(new InputFilter[]{new LoginActivity.PhoneNumberFilter(), new InputFilter.LengthFilter(10)});


        progressBar = (ProgressBar) findViewById(R.id.progressBar);

        serialNumber = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);

        deviceName = Build.BRAND + " " + Build.MODEL;

        //db = new DBHelper(LoginActivity.this);

        progressDialog = new ProgressDialog(LoginActivity.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);

        textInputLayout_Mobile_Number.requestFocus();
        if (!checkNetworkConnection(LoginActivity.this)) {

            //DialogBox.mLovelyStandardDialog(LoginActivity.this, R.string..error_timeout);

        }
        textPassRestLink.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(LoginActivity.this, ResetPin.class);
                startActivity(intent);
            }
        });


        textInputLayout_Pin.getEditText().addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
             /*   if (s.length() < 1) {
                    textInputLayout.setErrorEnabled(true);
                    textInputLayout.setError("Please enter a value");
                }

                if (s.length() > 0) {
                    textInputLayout.setError(null);
                    textInputLayout.setErrorEnabled(false);
                }
*/
                textInputLayout_Pin.setError(null);

            }

            @Override
            public void afterTextChanged(Editable s) {

            }
        });


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
        }
    }

    @Override
    public void onBackPressed() {
        // Toast.makeText(getApplication(),"Use the in app controls to navigate.",Toast.LENGTH_SHORT).show();
        Intent intent = new Intent(getApplicationContext(), StartActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
    }

    //Probase Login - Production
    private void userLogin() {

        progressBar.setVisibility(View.VISIBLE);

        ///prepare your JSONObject which you want to send in your web service request

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
            jsonPayloadObject.put("mobile", "26" + mobile_number_input);
            jsonPayloadObject.put("pin", pin_input);

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


                                //    Toast.makeText(getApplicationContext(), obj.getString("message"), Toast.LENGTH_SHORT).show();

                                //getting the user from the response
                                // DialogBox.mLovelyStandardDialog(LoginActivity.this,obj.getJSONObject("response").getJSONObject("AUTHENTICATION").getJSONObject("data").getString(KEY_MESSAGE));

                                JSONObject currentUser = obj.getJSONObject("response").getJSONObject("AUTHENTICATION").getJSONObject("data");
                                //creating a new user object
                                com.innovitrix.napsamarketsales.models.User mUser = new com.innovitrix.napsamarketsales.models.User(
                                        currentUser.getString("uuid"),
                                        currentUser.getString("first_name"),
                                        currentUser.getString("last_name"),
                                        currentUser.getString("nrc"),
                                        currentUser.getString("mobile")

                                );


                                //storing the user in shared preferences
                                //SharedPrefManager.getInstance(getApplicationContext()).userLogin(user);
                                SharedPrefManager.getInstance(getApplicationContext()).storeCurrentUser(mUser);
                                //starting main activity

                                String ot = obj.getJSONObject("response").getJSONObject("AUTHENTICATION").getJSONObject("data").getString("account_status");
                                if (ot.equals("OTP")) {
                                    //getting the user from the response
                                    SharedPrefManager.getInstance(getApplicationContext()).logout();
                                    Intent intent = new Intent(getApplicationContext(), ChangePin.class);
                                    intent.putExtra(KEY_PASSWORD, pin_input);
                                    intent.putExtra(KEY_MOBILE, "26" + mobile_number_input);//TODO change
                                    intent.putExtra(KEY_LOGIN_STATUS, "OTP");//TODO change
                                    intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                                    startActivity(intent);
                                    finish();
                                } else {
                                    textInputLayout_Mobile_Number.getEditText().setText(null);
                                    textInputLayout_Pin.getEditText().setText(null);
                                    Intent intent = new Intent(LoginActivity.this, MainActivity.class);
                                    intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                    startActivity(intent);
                                    finish();

                                }
                            } else {

                                progressBar.setVisibility(View.GONE);
                                AlertDialog.Builder builder = new AlertDialog.Builder(LoginActivity.this);
                                builder.setCancelable(false);
                                builder.setMessage("Authentication failed: Invalid mobile number or pin.");
                                builder.setPositiveButton("Ok",
                                        new DialogInterface.OnClickListener() {
                                            public void onClick(DialogInterface dialog, int id) {
                                                textInputLayout_Pin.getEditText().setText(null);
                                                textInputLayout_Mobile_Number.requestFocus();
                                                //Intent intent = new Intent(ResetPin.this,LoginActivity.class);
                                                // startActivity(intent);
                                                dialog.cancel();
                                            }
                                        });
                                builder.create().show();
                                //fetchTrader();
                            }
                        } catch (JSONException e) {

                            e.printStackTrace();
                            progressBar.setVisibility(View.GONE);
                            AlertDialog.Builder builder = new AlertDialog.Builder(LoginActivity.this);
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
                        }

                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.d("Error.Response", error.toString());
                //  Log.d("Error.Response", error.getMessage());
                //   startActivity(new Intent(LoginActivity.this, MainActivity.class)); //TODO Change when the API server is reachable
                progressBar.setVisibility(View.GONE);
                AlertDialog.Builder builder = new AlertDialog.Builder(LoginActivity.this);
                builder.setCancelable(false);
                builder.setMessage("Connection failure, kindly check your internet connection and try again.");
                builder.setPositiveButton("Ok",
                        new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                //startActivity(new Intent(LoginActivity.this, MainActivity.class));
                                //Intent intent = new Intent(ResetPin.this,LoginActivity.class);
                                // startActivity(intent);
                                dialog.cancel();
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
                params.put("mobile_number", mobile_number_input);
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


    @Override
    public void onClick(View view) {

        if (view == buttonLogin) {
            //calling the storeCurrentUser function
            if (!validateMobileNumber() || !validatePin()) {
                return;
            } else {
                userLogin();
            }

        } else if (view == textCardSingInLink) {
            finish();
            //  startActivity(new Intent(getApplicationContext(), LoginCardActivity.class));
        } else if (view == textRegisterLink) {
            finish();
            // startActivity(new Intent(getApplicationContext(), RegisterActivity.class));
        } else if (view == textPassRestLink) {
            finish();
            startActivity(new Intent(getApplicationContext(), ResetPin.class));
        }
    }


    public class PhoneNumberTextWatcher implements TextWatcher {

        private boolean isFormatting;
        private boolean deletingHyphen;
        private int hyphenStart;
        private boolean deletingBackward;

        @Override
        public void afterTextChanged(Editable text) {
            if (isFormatting)
                return;

            isFormatting = true;

            // If deleting hyphen, also delete character before or after it
            if (deletingHyphen && hyphenStart > 0) {
                if (deletingBackward) {
                    if (hyphenStart - 1 < text.length()) {
                        text.delete(hyphenStart - 1, hyphenStart);
                    }
                } else if (hyphenStart < text.length()) {
                    text.delete(hyphenStart, hyphenStart + 1);
                }
            }
            if (text.length() == 4 || text.length() == 8) {
                text.append('-');
            }

            isFormatting = false;
        }

        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            if (isFormatting)
                return;

            // Make sure user is deleting one char, without a selection
            final int selStart = Selection.getSelectionStart(s);
            final int selEnd = Selection.getSelectionEnd(s);
            if (s.length() > 1 // Can delete another character
                    && count == 1 // Deleting only one character
                    && after == 0 // Deleting
                    && s.charAt(start) == '-' // a hyphen
                    && selStart == selEnd) { // no selection
                deletingHyphen = true;
                hyphenStart = start;
                // Check if the user is deleting forward or backward
                if (selStart == start + 1) {
                    deletingBackward = true;
                } else {
                    deletingBackward = false;
                }
            } else {
                deletingHyphen = false;
            }
        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {

            textInputLayout_Mobile_Number.setError(null);
            mobile_number_input = textInputLayout_Mobile_Number.getEditText().getText().toString().trim();


            if (mobile_number_input.length() == 0)
                blockCharacterSet = "123456789";

            else
                blockCharacterSet = "";
            if (mobile_number_input.length() == 1)
                blockCharacterSet = "0";
        }
    }

    public class PhoneNumberFilter extends NumberKeyListener {

        @Override
        public int getInputType() {
            return InputType.TYPE_CLASS_PHONE;
        }

        @Override
        protected char[] getAcceptedChars() {
            return new char[]{'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-'};
        }

        @Override
        public CharSequence filter(CharSequence source, int start, int end,
                                   Spanned dest, int dstart, int dend) {

            try {
                // Don't let phone numbers start with 1

                if (source != null && blockCharacterSet.contains("" + source.charAt(0)))
                    return "";


                //if (dstart == 0 && source.equals("1"))
                //   return "";

                if (end > start) {
                    String destTxt = dest.toString();
                    String resultingTxt = destTxt.substring(0, dstart) + source.subSequence(start, end) + destTxt.substring(dend);

                    // Phone number must match xxx-xxx-xxxx
                    if (!resultingTxt.matches("^\\d{0,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}?)?)?)?)?)?)?)?)?)?")) {
                        //   if (!resultingTxt.matches("^\\d{1,1}(\\d{1,1}(\\d{1,1}(\\-(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\-(\\d{1,1}(\\d{1,1}(\\d{1,1}(\\d{1,1}?)?)?)?)?)?)?)?)?)?)?)?")) {

                        return "";
                    }
                }
                return null;
            } catch (StringIndexOutOfBoundsException e) {

            }
            return null;
        }
    }

    private boolean validateMobileNumber() {
        mobile_number_input = textInputLayout_Mobile_Number.getEditText().getText().toString().trim();
        if (mobile_number_input.isEmpty() | mobile_number_input.length() < 10) {
            textInputLayout_Mobile_Number.setErrorEnabled(true);
            textInputLayout_Mobile_Number.setError("Enter a 10 digit mobile number (0xxxxxxxxx).");
            textInputLayout_Mobile_Number.requestFocus();
            return false;

        } else {
            textInputLayout_Mobile_Number.setError(null);
            return true;
        }
    }

    private boolean validatePin() {
        pin_input = textInputLayout_Pin.getEditText().getText().toString().trim();
        if (pin_input.isEmpty() | pin_input.length() < 5) {
            textInputLayout_Pin.setErrorEnabled(true);
            textInputLayout_Pin.setError("Enter a valid 5 digit pin.");
            textInputLayout_Pin.requestFocus();
            return false;
        } else {
            textInputLayout_Pin.setError(null);
            return true;
        }
    }

}