package com.innovitrix.napsamarketsales;

import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;
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

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_FIRSTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LASTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_SERVICE_TOKEN;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRADER_ID;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_USERNAME_API;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_VOLLEY_SOCKET_TIMEOUT_MS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_MARKETER_KYC;

public class FindTrader extends AppCompatActivity {

    private ProgressDialog progressDialog;
    ProgressBar progressBar;
    RequestQueue queue;

    Button button_Submit;
    private TextInputLayout  textInputLayout_Mobile_Number, textInputLayout_Amount;

    int mobile_number_char;
    String blockCharacterSet = "123456789";

    String seller_first_name;
    String seller_last_name;
    String seller_mobile_number;
    String seller_id;
    String buyer_mobile_number;
    TextView textViewUsername;
    TextView textViewDate;
    private long backPressedTime;
    private Toast backToast;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_find_trader);
        getSupportActionBar().setSubtitle("make an order");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        progressDialog = new ProgressDialog(FindTrader.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);

        textViewUsername = (TextView)findViewById(R.id.textViewUsername);
        textViewUsername.setText("Logged in as "+SharedPrefManager.getInstance(FindTrader.this).getUser().getFirstname()+ " "+SharedPrefManager.getInstance(FindTrader.this).getUser().getLastname());
        textViewDate = (TextView)findViewById(R.id.textViewDate);
        textViewDate.setText(SharedPrefManager.getInstance(FindTrader.this).getTranactionDate2());

        button_Submit = (Button) findViewById(R.id.button_Submit);

        textInputLayout_Mobile_Number = (TextInputLayout) findViewById(R.id.mobile_number_TextInputLayout);
        textInputLayout_Mobile_Number.getEditText().addTextChangedListener(new FindTrader.PhoneNumberTextWatcher());
        textInputLayout_Mobile_Number.getEditText().setFilters(new InputFilter[]{new FindTrader.PhoneNumberFilter(), new InputFilter.LengthFilter(10)});
        textInputLayout_Mobile_Number.requestFocus();

        progressBar = (ProgressBar) findViewById(R.id.progressBar);
        button_Submit.setOnClickListener(new View.OnClickListener() {
                                             @Override
                                             public void onClick(View view) {


                                                 buyer_mobile_number = SharedPrefManager.getInstance(FindTrader.this).getUser().getMobile_number();
                                                 String string_seller_mobile_number = textInputLayout_Mobile_Number.getEditText().getText().toString().trim();
                                                 mobile_number_char = string_seller_mobile_number.length();

                                                 if (TextUtils.isEmpty(string_seller_mobile_number)  || mobile_number_char != 10) {
                                                     textInputLayout_Mobile_Number.setErrorEnabled(true);
                                                     textInputLayout_Mobile_Number.setError("Enter a 10 digit mobile number (0xxxxxxxxx).");
                                                     textInputLayout_Mobile_Number.requestFocus();
                                                     return;
                                                 }


                                                 // Toast.makeText(getApplicationContext(), buyer_name, Toast.LENGTH_SHORT).show();
                                                 seller_mobile_number = "26" +string_seller_mobile_number;

                                                 seller_mobile_number = seller_mobile_number.replace("-", "");

                                                 if (mobile_number_char == 10) {
                                                     if (buyer_mobile_number.equals(seller_mobile_number)) {
                                                         //seller_mobile_number ="";
                                                         AlertDialog.Builder builder = new AlertDialog.Builder(FindTrader.this);

                                                         builder.setMessage("You can not order from yourself. Change the number?");
                                                         builder.setPositiveButton("Yes",
                                                                 new DialogInterface.OnClickListener() {
                                                                     public void onClick(DialogInterface dialog, int id) {
                                                                         dialog.cancel();
                                                                         textInputLayout_Mobile_Number.requestFocus();
                                                                     }
                                                                 });

                                                         builder.setNegativeButton("No",
                                                                 new DialogInterface.OnClickListener() {
                                                                     public void onClick(DialogInterface dialog, int id) {
                                                                         dialog.cancel();
                                                                         finish();

                                                                     }
                                                                 });

                                                         builder.create().show();
                                                     } else {
                                                         fetchTrader();
                                                     }
                                                 } else
                                                     {
                                                         textInputLayout_Mobile_Number.setErrorEnabled(true);
                                                         textInputLayout_Mobile_Number.setError("Enter a 10 digit mobile number (0xxxxxxxxx).");
                                                         textInputLayout_Mobile_Number.requestFocus();
                                                 }
                                             }
                                         }
        );

    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                //do whatever
                Intent intent = new Intent(FindTrader.this, MainActivity.class);
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
        Intent intent = new Intent(FindTrader.this, MainActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK| Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
    }



    public void fetchTrader() {

        //    progressDialog.show();
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
            jsonPayloadObject.put("mobile", seller_mobile_number);
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
                            if (obj.getJSONObject("response").has("QUERY")) {

                                //getting the user from the response

                                JSONObject currentUser = obj.getJSONObject("response").getJSONObject("QUERY").getJSONObject("data");
                                //creating a new user object
                                com.innovitrix.napsamarketsales.models.User mUser = new com.innovitrix.napsamarketsales.models.User(
                                        currentUser.getString("uuid"),
                                        currentUser.getString("first_name"),
                                        currentUser.getString("last_name"),
                                        currentUser.getString("nrc"),
                                        currentUser.getString("mobile")
                                );


                                Intent intent = new Intent(getApplicationContext(), BuyFromTrader.class);
                                intent.putExtra(KEY_FIRSTNAME, mUser.getFirstname());
                                intent.putExtra(KEY_LASTNAME, mUser.getLastname());
                                intent.putExtra(KEY_MOBILE, mUser.getMobile_number());
                                intent.putExtra(KEY_TRADER_ID, mUser.getTrader_id());
                                startActivity(intent);
                            } else {

                               // DialogBox.mLovelyStandardDialog(FindTrader.this, "Trader not found.");
                                progressBar.setVisibility(View.GONE);
                                AlertDialog.Builder builder = new AlertDialog.Builder(FindTrader.this);
                                builder.setCancelable(false);
                                builder.setMessage("Trader not found, kindly contact the System Administrator.");
                                builder.setPositiveButton("Ok",
                                        new DialogInterface.OnClickListener() {
                                            public void onClick(DialogInterface dialog, int id) {
                                               textInputLayout_Mobile_Number.requestFocus();
                                            }
                                        });
                                builder.create().show();

                            }
                        } catch (JSONException e) {
                            e.printStackTrace();

                            progressBar.setVisibility(View.GONE);
//                            AlertDialog.Builder builder = new AlertDialog.Builder(FindTrader.this);
//                            builder.setCancelable(false);
//                            builder.setMessage("An error occurred while retrieving traders details, kindly check your internet connection and try again.");
//                            builder.setPositiveButton("Ok",
//                                    new DialogInterface.OnClickListener() {
//                                        public void onClick(DialogInterface dialog, int id) {
//                                            dialog.cancel();
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
                        Log.d("Error.Response", error.toString());
                        progressDialog.dismiss();
                        progressBar.setVisibility(View.GONE);
                        AlertDialog.Builder builder = new AlertDialog.Builder(FindTrader.this);
                        builder.setCancelable(false);
                        builder.setMessage("Connection failure, kindly check your internet connection.");
                        builder.setPositiveButton("Ok",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                        //Intent intent = new Intent(ResetPin.this,LoginActivity.class);
                                        // startActivity(intent);
                                        dialog.cancel();
                                    }
                                });
                        builder.create().show();
                        //Log.d("Error.Response", error.getMessage());
                        //DialogBox.mLovelyStandardDialog(FindTrader.this,"Server unreachable.");
                        // startActivity(new Intent( BuyFromTrader.this,MainActivity.class));
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                // params.put("username", trader_id);
                //params.put("email", firstname);
                // params.put("password", lastname);
                params.put("mobile_number", seller_mobile_number);
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
            seller_mobile_number = textInputLayout_Mobile_Number.getEditText().getText().toString().trim();
            if (seller_mobile_number.length() == 0)
                blockCharacterSet = "123456789";

            else
                blockCharacterSet = "";
            if (  seller_mobile_number.length() == 1)
                blockCharacterSet = "0";
    }}

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
