package com.innovitrix.napsamarketsales;

import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.provider.Settings;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
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
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_AUTHENTICATE_MARKETER;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_RESET_PIN;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_TRANSACTIONS;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_UPDATE_PIN;

public class ResetPin extends AppCompatActivity {
    private EditText editTextTraderMobileNumber, editTextTraderNRC;
    private Button button_Save;
    private ProgressBar progressBar;
    private ProgressDialog progressDialog;
    RequestQueue queue;
    String   trader_mobile_number, trader_nrc;
    String pin;
    int mobile_number_char;
    String blockCharacterSet = "123456789";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_reset_pin);
        getSupportActionBar().setSubtitle("Reset pin");
        progressDialog = new ProgressDialog(ResetPin.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);
        editTextTraderMobileNumber = (EditText) findViewById(R.id.editText_Trader_Mobile_Number);
        editTextTraderNRC = (EditText) findViewById(R.id.editText_Trader_NRC);

        editTextTraderMobileNumber.requestFocus();

        editTextTraderMobileNumber.addTextChangedListener(new ResetPin.PhoneNumberTextWatcher());
        editTextTraderMobileNumber.setFilters(new InputFilter[]{new ResetPin.PhoneNumberFilter(), new InputFilter.LengthFilter(10)});

        button_Save = (Button) findViewById(R.id.buttonSubmitRP);
           button_Save.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                trader_mobile_number =  "26"+ editTextTraderMobileNumber.getText().toString().trim();
                trader_nrc =     editTextTraderNRC.getText().toString().trim();

                if (TextUtils.isEmpty(trader_mobile_number)) {
                    editTextTraderMobileNumber.setError("Please enter your mobile no.");
                    editTextTraderMobileNumber.requestFocus();
                    return;
                }

                if (TextUtils.isEmpty(       trader_nrc)) {
                    editTextTraderNRC.setError("Please enter your nrc");
                    editTextTraderNRC.requestFocus();
                    return;
                }


                AlertDialog.Builder builder = new AlertDialog.Builder(ResetPin.this);
                builder.setMessage("Confirm password reset?");
                builder.setPositiveButton("Yes",
                        new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {

                                sendInformation( trader_mobile_number,trader_nrc);
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




    public void sendInformation(
            final String mobile_number,
            String nrc
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
            jsonPayloadObject.put("nrc", nrc);

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
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST,URL_RESET_PIN, jsonObject,
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
                            if(obj.getJSONObject("response").getJSONObject("AUTHENTICATION").has("data")){
                                //getting the user from the response
                                Toast.makeText(getApplicationContext(), "Pin reset successful", Toast.LENGTH_LONG).show();
                                startActivity(new Intent(ResetPin.this, LoginActivity.class));
                                //DialogBox.mLovelyStandardDialog(ResetPin.this,"Pin reset successful.");
                                editTextTraderMobileNumber.requestFocus();
                                editTextTraderMobileNumber.setText("");
                                editTextTraderNRC.setText("");
                                //startActivity(new Intent(ResetPin.this, LoginActivity.class));
                            }
                            else
                            {
                                DialogBox.mLovelyStandardDialog(ResetPin.this,"Pin reset not successful.");
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
//                       Log.d("Error.Response", error.getMessage());
                //   startActivity(new Intent(LoginActivity.this, MainActivity.class)); //TODO Change when the API server is reachable

                DialogBox.mLovelyStandardDialog(ResetPin.this,   "Connection failure.");
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
//
//
//
//    public void sendInformation(
//            String mobile_number,
//            String nrc
//    )
//    {
//        JSONObject jsonAuthObject = new JSONObject();
//        try {
//            jsonAuthObject.put("username","admin");
//            jsonAuthObject.put("service_token","JJ8DJ7S66DMA5");
//        } catch (JSONException e) {
//            e.printStackTrace();
//        }
//
//
//        //PAYLOAD
//        JSONObject jsonPayloadObject = new JSONObject();
//        try {
//            jsonPayloadObject.put("mobile",mobile_number);
//            jsonPayloadObject.put("nrc", nrc);
//
//        } catch (JSONException e) {
//            e.printStackTrace();
//        }
//
//
//        ///prepare your JSONObject which you want to send in your web service request
//        JSONObject jsonObject = new JSONObject();
//        try {
//            jsonObject.put("auth",jsonAuthObject);
//            jsonObject.put("payload",jsonPayloadObject);
//        } catch (JSONException e) {
//            e.printStackTrace();
//        }
//
//        // progressDialog.show();
//
//        ///prepare your JSONObject which you want to send in your web service request
//// Calendar.getInstance().getTime()
//
//
//        // prepare the Request
//        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, URL_RESET_PIN, jsonObject,
//                new Response.Listener<JSONObject>() {
//                    @Override
//                    public void onResponse(JSONObject response) {
//
//                        //Do stuff here
//                        // display response
//                        progressDialog.dismiss();
//                        Log.d("Response", response.toString());
//
//                        try {
//
//                            DialogBox.mLovelyStandardDialog(ResetPin.this, response.getString(KEY_MESSAGE)); //response.getString(KEY_MESSAGE)
//
//                            //  startActivity(new Intent( BuyFromTrader.this,MainActivity.class));
//
//                        } catch (JSONException e) {
//                            e.printStackTrace();
//                        }
//
//                    }
//                }, new Response.ErrorListener() {
//
//
//            @Override
//            public void onErrorResponse(VolleyError error) {
//                //Handle Errors here
//                progressDialog.dismiss();
//                Log.d("Error.Response", error.toString());
//                Log.d("Error.Response", error.getMessage());
//
//                DialogBox.mLovelyStandardDialog(ResetPin.this, error.toString());
//
//                // startActivity(new Intent( BuyFromTrader.this,MainActivity.class));
//            }
//        }) {
//
//            /** Passing some request headers* */
//            @Override
//            public Map<String, String> getHeaders() throws AuthFailureError {
//                Map<String, String> headers = new HashMap<>();
//                headers.put("Content-Type", "application/json");
//                headers.put("apiKey", "xxxxxxxxxxxxxxx");
//                return headers;
//            }
//
//        };
//
//        // add it to the RequestQueue
//        queue.add(jsonObjectRequest);
//    }
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

            mobile_number_char =editTextTraderMobileNumber.getText().toString().length();
            if (  editTextTraderMobileNumber.getText().toString().length() == 0)
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
