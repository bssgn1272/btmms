package com.innovitrix.napsamarketsales;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.AppCompatButton;
import android.text.Editable;
import android.text.InputFilter;
import android.text.InputType;
import android.text.Selection;
import android.text.Spanned;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.text.method.NumberKeyListener;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

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
import com.innovitrix.napsamarketsales.dialog.DialogBox;
import com.innovitrix.napsamarketsales.models.User;
import com.innovitrix.napsamarketsales.network.VolleySingleton;
//import com.innovitrix.napsamarketsales.;
//import com.innovitrix.reards.models.Customer;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;



import static com.innovitrix.napsamarketsales.network.NetworkMonitor.checkNetworkConnection;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_FIRSTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LASTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_PASSWORD;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_STATUS;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRADER_ID;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_AUTHENTICATE_MARKETER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_AMPERSAND;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_MARKETER_KYC;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_MOBILE_NUMBER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_PIN;
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
    ProgressDialog progressDialog;
    public String TAG_FIREBASE = "Firebase";
    private String TAG = LoginActivity.class.getSimpleName();

    String androidDeviceId, deviceSerial, deviceName, serialNumber;

    String email_or_mobile;
   String password;

    int mobile_number_char;
    String blockCharacterSet = "123456789";
    // Database Helper
    //DBHelper db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        //initializing views
        editTextEmail = (EditText) findViewById(R.id.editTextEmail);
        editTextPassword = (EditText) findViewById(R.id.editTextPassword);

        buttonLogin = findViewById(R.id.buttonLogin);
        buttonLogin.setOnClickListener(this);


      //  textCardSingInLink = (TextView) findViewById(R.id.linkCardSignIn);
      //  textCardSingInLink.setOnClickListener(this);


        //textRegisterLink = (TextView) findViewById(R.id.linkSignup);
        //textRegisterLink.setOnClickListener(this);

        textPassRestLink = (TextView) findViewById(R.id.linkPasswordReset);
        textPassRestLink.setOnClickListener(this);
       editTextEmail.addTextChangedListener(new LoginActivity.PhoneNumberTextWatcher());
       editTextEmail.setFilters(new InputFilter[]{new LoginActivity.PhoneNumberFilter(), new InputFilter.LengthFilter(10)});


        progressBar = (ProgressBar) findViewById(R.id.progressBar);

        serialNumber = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);

        deviceName = Build.BRAND +" "+ Build.MODEL;

        //db = new DBHelper(LoginActivity.this);

        progressDialog = new ProgressDialog(LoginActivity.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);

        editTextEmail.requestFocus();
        if (!checkNetworkConnection(LoginActivity.this)) {

           //DialogBox.mLovelyStandardDialog(LoginActivity.this, R.string..error_timeout);

        }
        textPassRestLink.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(LoginActivity.this,ResetPin.class);
                startActivity(intent);
            }
        });

    }

    private void userLogin() {

        email_or_mobile = editTextEmail.getText().toString().trim();
        password = editTextPassword.getText().toString().trim();
        startActivity(new Intent(LoginActivity.this, MainActivity.class));
    }


    //Live
    private void userLogin_Live() {

        //getting values from edit texts
       email_or_mobile = editTextEmail.getText().toString().trim();
         password = editTextPassword.getText().toString().trim();


        //validating inputs
        if (TextUtils.isEmpty(email_or_mobile)) {
            editTextEmail.setError("Please enter your mobile number");
            editTextEmail.requestFocus();
            return;
        }
        if (mobile_number_char != 10) {
            editTextEmail.setError("Enter a 10 digit mobile number (0xxxxxxxxx)");
            editTextEmail.requestFocus();
            return;
        }

       if (TextUtils.isEmpty(password)) {
            editTextPassword.setError("Please enter your password");
            editTextPassword.requestFocus();
            return;
        }


        progressBar.setVisibility(View.VISIBLE);

        ///prepare your JSONObject which you want to send in your web service request

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
            jsonPayloadObject.put("mobile","26" +email_or_mobile);
            jsonPayloadObject.put("pin", password);

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
                        progressBar.setVisibility(View.GONE);

                        try {
                            //converting response to json object
                            JSONObject obj = new JSONObject(String.valueOf(response));


                            //Check if the object has the key.
                           if(obj.getJSONObject("response").has("AUTHENTICATION")){


                                //    Toast.makeText(getApplicationContext(), obj.getString("message"), Toast.LENGTH_SHORT).show();

                                //getting the user from the response
                               // DialogBox.mLovelyStandardDialog(LoginActivity.this,obj.getJSONObject("response").getJSONObject("AUTHENTICATION").getJSONObject("data").getString(KEY_MESSAGE));

                                JSONObject currentUser = obj.getJSONObject("response").getJSONObject("AUTHENTICATION").getJSONObject("data");
                                //creating a new user object
                                com.innovitrix.napsamarketsales.models.User mUser = new com.innovitrix.napsamarketsales.models.User(
                                        currentUser.getString("uuid"),
//                                        currentUser.getString("first_name"),
//                                        currentUser.getString("last_name"),
//                                        currentUser.getString("nrc"),
                                        currentUser.getString("mobile")

                                );

                                  //storing the user in shared preferences
                                //SharedPrefManager.getInstance(getApplicationContext()).userLogin(user);
                                SharedPrefManager.getInstance(getApplicationContext()).storeCurrentUser(mUser);
                                //starting main activity

                               String ot =  obj.getJSONObject("response").getJSONObject("AUTHENTICATION").getJSONObject("data").getString("account_status");
                               if (ot.equals("OTP")) {
                                   //getting the user from the response
                                   Intent intent = new Intent(getApplicationContext(), ChangePin.class);
                                   intent.putExtra(KEY_PASSWORD, password);
                                   intent.putExtra(KEY_MOBILE, "26" + email_or_mobile);//TODO change
                                   startActivity(intent);
                               }
                               else
                                   {
                                   startActivity(new Intent(LoginActivity.this, MainActivity.class));
                               }
                           }
                           else
                                {

                                 // DialogBox.mLovelyStandardDialog(LoginActivity.this,obj.getJSONObject("response").getJSONObject("error").getJSONObject("message").getString(KEY_MESSAGE));
                                  progressBar.setVisibility(View.GONE);
                                    fetchTrader();
                             }
                        } catch (JSONException e) {
                          DialogBox.mLovelyStandardDialog(LoginActivity.this,e.getMessage());
                            //progressBar.setVisibility(View.GONE);

                            e.printStackTrace();
                        }

                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.d("Error.Response", error.toString());
         //       Log.d("Error.Response", error.getMessage());
             //   startActivity(new Intent(LoginActivity.this, MainActivity.class)); //TODO Change when the API server is reachable

                DialogBox.mLovelyStandardDialog(LoginActivity.this,   "Server unreachable");
                progressBar.setVisibility(View.GONE);
                // Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
            }
        }) {
            @Override

            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                // params.put("username", trader_id);
                //params.put("email", firstname);
                // params.put("password", lastname);
                params.put("mobile_number", email_or_mobile );
                return params;
            }
        };

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonObjectRequest);
    }

    public void fetchTrader() {

        //    progressDialog.show();


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
            jsonPayloadObject.put("mobile", "26" +email_or_mobile);
            //  jsonPayloadObject.put("pin", password);

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
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, URL_MARKETER_KYC, jsonObject,
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
                            if (obj.getJSONObject("response").has("QUERY"))
                                                        {
                                String ot =  obj.getJSONObject("response").getJSONObject("QUERY").getJSONObject("data").getString("account_status");
                                if (ot.equals("OTP")) {
                                    //getting the user from the response
                                    Intent intent = new Intent(getApplicationContext(), ChangePin.class);
                                    intent.putExtra(KEY_PASSWORD, password);
                                    intent.putExtra(KEY_MOBILE, "26" + email_or_mobile);//TODO change
                                    startActivity(intent);
                                }
                                else
                                {
                                    DialogBox.mLovelyStandardDialog(LoginActivity.this, "Wrong mobile number or pin!");

                                }
                            } else {

                                    DialogBox.mLovelyStandardDialog(LoginActivity.this, "Wrong mobile number or pin!");
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
                        progressDialog.dismiss();
                        Log.d("Error.Response", error.toString());
                        //Log.d("Error.Response", error.getMessage());
                        DialogBox.mLovelyStandardDialog(LoginActivity.this,"Server unreachable.");
                        // startActivity(new Intent( BuyFromTrader.this,MainActivity.class));
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                // params.put("username", trader_id);
                //params.put("email", firstname);
                // params.put("password", lastname);
                params.put("mobile_number",email_or_mobile);
                return params;
            }
        };

        //  VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
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

            mobile_number_char =editTextEmail.getText().toString().length();
            if (editTextEmail.getText().toString().length() == 0)
                blockCharacterSet = "123456789";

            else
                blockCharacterSet = "";
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


}