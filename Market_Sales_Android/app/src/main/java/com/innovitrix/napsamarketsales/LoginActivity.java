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

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;



import static com.innovitrix.napsamarketsales.network.NetworkMonitor.checkNetworkConnection;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_STATUS;
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

    int mobile_number_char;
    String blockCharacterSet = "123456789";
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
       editTextEmail.addTextChangedListener(new LoginActivity.PhoneNumberTextWatcher());
       editTextEmail.setFilters(new InputFilter[]{new LoginActivity.PhoneNumberFilter(), new InputFilter.LengthFilter(10)});


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
            editTextEmail.setError("Please enter your mobile number");
            editTextEmail.requestFocus();
            return;
        }
        if (mobile_number_char != 10) {
            editTextEmail.setError("Enter a 10 digit mobile number (0xxxxxxxxx)");
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
                URL_PARAM_MOBILE_NUMBER +  "26"+email_or_mobile
                //URL_CHAR_AMPERSAND +
                //URL_PARAM_USER_ID + 1
                ,null,
                new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                progressBar.setVisibility(View.GONE);

                        try {
                            //converting response to json object
                            JSONObject obj = new JSONObject(String.valueOf(response));
                            //Check if the object if the object is null.
                            if (!obj.isNull("users")){
                            //    Toast.makeText(getApplicationContext(), obj.getString("message"), Toast.LENGTH_SHORT).show();

                                //getting the user from the response

                                JSONObject currentUser = obj.getJSONObject("users");
                                //creating a new user object
                                com.innovitrix.napsamarketsales.models.User mUser = new com.innovitrix.napsamarketsales.models.User(
                                        currentUser.getInt("trader_id"),
                                        currentUser.getString("firstname"),
                                        currentUser.getString("lastname"),
                                        currentUser.getString("nrc"),
                                        currentUser.getString("gender"),
                                        currentUser.getString("mobile_number")
                                );

                                //storing the user in shared preferences
                                //SharedPrefManager.getInstance(getApplicationContext()).userLogin(user);
                                SharedPrefManager.getInstance(getApplicationContext()).storeCurrentUser(mUser);
                                //starting profile activity
                                finish();
                                //   startActivity(new Intent(getApplicationContext(), ProfileActivity.class));

                                startActivity(new Intent(LoginActivity.this, MainActivity.class));

                            } else {

                                    DialogBox.mLovelyStandardDialog(LoginActivity.this, response.getString(KEY_MESSAGE));
                                    // startActivity(new Intent( BuyFromTrader.this,MainActivity.class));

                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {

                        DialogBox.mLovelyStandardDialog(LoginActivity.this,error.getMessage());

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