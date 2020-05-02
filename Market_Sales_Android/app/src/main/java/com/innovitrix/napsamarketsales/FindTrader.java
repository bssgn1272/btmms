package com.innovitrix.napsamarketsales;

import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
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
import android.widget.TextView;

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

import java.util.HashMap;
import java.util.Map;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_END_ROUTE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_FIRSTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LASTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MESSAGE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_NAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_START_ROUTE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRADER_ID;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_TRAVEL_DATE;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_MARKETER_KYC;

public class FindTrader extends AppCompatActivity {

    private ProgressDialog progressDialog;
    RequestQueue queue;

    Button button_Submit;

    EditText editText_Seller_Mobile_Number;

    int mobile_number_char;
    String blockCharacterSet = "123456789";

    String seller_first_name;
    String seller_last_name;
    String seller_mobile_number;
    String seller_id;
    String buyer_mobile_number;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_find_trader);
        getSupportActionBar().setSubtitle("Make an order");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        progressDialog = new ProgressDialog(FindTrader.this);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(this);


        button_Submit = (Button) findViewById(R.id.button_SubmitFT);
        editText_Seller_Mobile_Number = (EditText) findViewById(R.id.editTextSellerMobileNo);
        editText_Seller_Mobile_Number.requestFocus();
        editText_Seller_Mobile_Number.addTextChangedListener(new FindTrader.PhoneNumberTextWatcher());
        editText_Seller_Mobile_Number.setFilters(new InputFilter[]{new FindTrader.PhoneNumberFilter(), new InputFilter.LengthFilter(12)});


        button_Submit.setOnClickListener(new View.OnClickListener() {
                                             @Override
                                             public void onClick(View view) {


                                                 buyer_mobile_number = SharedPrefManager.getInstance(FindTrader.this).getUser().getMobile_number();

                                                 String string_seller_mobile_number =  editText_Seller_Mobile_Number.getText().toString();


                                                 if (TextUtils.isEmpty(string_seller_mobile_number)) {
                                                     editText_Seller_Mobile_Number.setError("Please enter a mobile number.");
                                                     editText_Seller_Mobile_Number.requestFocus();
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

                                                     editText_Seller_Mobile_Number.setError("Enter a 10 digit mobile number.");
                                                     editText_Seller_Mobile_Number.requestFocus();
                                                 }
                                             }
                                         }
        );

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
                        //progressBar.setVisibility(View.GONE);

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

                                DialogBox.mLovelyStandardDialog(FindTrader.this, "Trader not found.");
                                ;
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
                        DialogBox.mLovelyStandardDialog(FindTrader.this,"Server unreachable.");
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

            mobile_number_char = editText_Seller_Mobile_Number.getText().toString().length();
            if (editText_Seller_Mobile_Number.getText().toString().length() == 0)

                blockCharacterSet = "123456789";

           else
                blockCharacterSet = "";
            if (editText_Seller_Mobile_Number.getText().toString().length() == 1)
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


}
