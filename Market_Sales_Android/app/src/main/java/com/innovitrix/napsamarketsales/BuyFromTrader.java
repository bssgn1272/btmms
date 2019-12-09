    package com.innovitrix.napsamarketsales;

    import android.annotation.TargetApi;
    import android.app.ProgressDialog;
    import android.content.Context;
    import android.content.DialogInterface;
    import android.os.Build;
    import android.os.Bundle;
    import android.os.Handler;
    import android.provider.Settings;
    import android.support.v7.app.AlertDialog;
    import android.support.v7.app.AppCompatActivity;

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
    import android.support.constraint.ConstraintLayout;
    import android.widget.Button;
    import android.widget.EditText;
    import android.widget.TextView;

    import com.android.volley.AuthFailureError;
    import com.android.volley.Request;
    import com.android.volley.RequestQueue;
    import com.android.volley.Response;
    import com.android.volley.VolleyError;
    import com.android.volley.toolbox.JsonObjectRequest;


    import org.json.JSONException;
    import org.json.JSONObject;

    import java.util.HashMap;
    import java.util.Map;

    import com.android.volley.toolbox.Volley;

    import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
    import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_MOBILE_NUMBER;
    import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_TRANSACTIONS;
    import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_USERS;
    import android.widget.Toast;
    public class BuyFromTrader extends AppCompatActivity {
        @TargetApi(Build.VERSION_CODES.O)
        private ProgressDialog progressDialog;
        RequestQueue queue;
        ConstraintLayout layoutSupplierFind;
        ConstraintLayout layoutSupplier;
        Button btnFindSupplier;
        Button btnBack;
        Button btnPay;
        Button btnCancel;
        EditText etSupplierMobileNo;
        String blockCharacterSet = "123456789";
        TextView textViewFirstName;
        TextView textViewLastName;
        TextView textViewMobileNumber;
        EditText etAmount;
        int trader_id;
        int mobile_number_char ;
        int userObjectLength ;
        Double dblAmount;
        String mBuyer_Mobile;
        String stringTrader_id;
        String mobile_number;
        private static final String TAG = MainActivity.class.getName();
        Handler handler = new Handler();


        protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_buy_from_trader);

            progressDialog  = new ProgressDialog(BuyFromTrader.this);
            progressDialog.setMessage("Loading...");
            progressDialog.setCancelable(false);
            queue = Volley.newRequestQueue(this);

            layoutSupplier = findViewById(R.id.layoutSupplier);
            layoutSupplierFind = findViewById(R.id.layoutSupplierFind);
            btnFindSupplier = findViewById(R.id.btnFindSupplier);
            btnFindSupplier.setEnabled(true);
            btnBack = findViewById(R.id.btnBack);
            btnBack.setEnabled(true);
            btnPay = findViewById(R.id.btnPay);
            btnCancel = findViewById(R.id.btnCancel);
            btnPay.setEnabled(true);
            btnCancel.setEnabled(true);
            etSupplierMobileNo = findViewById(R.id.etSupplierMobileNo);
            etSupplierMobileNo.addTextChangedListener(new PhoneNumberTextWatcher());
            etSupplierMobileNo.setFilters(new InputFilter[] { new PhoneNumberFilter(), new InputFilter.LengthFilter(12) });
            textViewFirstName = findViewById(R.id.textViewFirstName);
            textViewLastName = findViewById(R.id.textViewLastName);
            textViewMobileNumber = findViewById(R.id.textViewMobileNumber);
            etAmount = findViewById(R.id.editTextAmt);
            mBuyer_Mobile = SharedPrefManager.getInstance(BuyFromTrader.this).getCustomer().getMobile_number();

            mobile_number = mBuyer_Mobile;
            // etAmount.setFilters(new InputFilter[] { new AmountFilter(), new InputFilter.LengthFilter(12) });
            layoutSupplier.setVisibility(View.GONE);
            mobile_number_char =0 ;
            userObjectLength = 0;
            trader_id = 0;
           // fetchTrader();
            btnFindSupplier.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {

                    textViewFirstName.setText("");
                    textViewLastName.setText("");
                    textViewMobileNumber.setText("");

                    mobile_number = etSupplierMobileNo.getText().toString();

                    mobile_number =mobile_number.replace("-", "");

                    if (mobile_number_char ==10) {


                        if(mBuyer_Mobile.equals(mobile_number))
                        {
                             AlertDialog.Builder   builder = new AlertDialog.Builder(BuyFromTrader.this);

                            builder.setMessage("You can not order from yourself. Change the number?");
                            builder.setPositiveButton("Yes",
                                    new DialogInterface.OnClickListener()
                                    {
                                        public void onClick(DialogInterface dialog, int id)
                                        {
                                            dialog.cancel();
                                        }
                                    });

                            builder.setNegativeButton("No",
                                    new DialogInterface.OnClickListener()
                                    {
                                        public void onClick(DialogInterface dialog, int id)
                                        {
                                            dialog.cancel();
                                            finish();

                                        }
                                    });

                            builder.create().show();
                        }
                        else
                        {
                            fetchTrader();
                        }
                    }
                    else
                    {
                        AlertDialog.Builder   builder = new AlertDialog.Builder(BuyFromTrader.this);

                        builder.setMessage("Enter a 10 digit mobile number (e.g 0900000000).");


                        builder.setNeutralButton("Ok",
                                new DialogInterface.OnClickListener()
                                {
                                    public void onClick(DialogInterface dialog, int id)
                                    {
                                       dialog.cancel();
                                    }
                                });

                        builder.create().show();


                    }
                }}
            );

            btnCancel.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    layoutSupplier.setVisibility(View.GONE);
                    layoutSupplierFind.setVisibility(View.VISIBLE);
                }
            });
            btnBack.setOnClickListener(new View.OnClickListener(){
                @Override
                public void onClick(View view) {
                    finish();
                }
            });
    btnPay.setOnClickListener(new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            AlertDialog.Builder   builder = new AlertDialog.Builder(BuyFromTrader.this);
            builder.setMessage("Are you want to make this payment?");
            builder.setPositiveButton("Yes",
                    new DialogInterface.OnClickListener()
                    {
                        public void onClick(DialogInterface dialog, int id)
                        {
                            dblAmount = Double.valueOf(etAmount.getText().toString());
                            sendInformation(trader_id, mBuyer_Mobile, dblAmount);
                            layoutSupplier.setVisibility(View.GONE);
                            layoutSupplierFind.setVisibility(View.VISIBLE);
                        }
                    });

            builder.setNegativeButton("No",
                    new DialogInterface.OnClickListener()
                    {
                        public void onClick(DialogInterface dialog, int id)
                        {
                            dialog.cancel();
                        }
                    });

            builder.create().show();
        }
    });
        }

public void foundTrader()
{

    if (trader_id==1)
    {
    //Toast.makeText(this,String.valueOf(trader_id),Toast.LENGTH_LONG);

        AlertDialog.Builder   builder = new AlertDialog.Builder(BuyFromTrader.this);
        builder.setMessage("The number is not registered."+  stringTrader_id +" Change the number?");
        builder.setPositiveButton("Yes",
                new DialogInterface.OnClickListener()
                {
                    public void onClick(DialogInterface dialog, int id)
                    {
                        layoutSupplier.setVisibility(View.GONE);
                        layoutSupplierFind.setVisibility(View.VISIBLE);
                        dialog.cancel();
                    }
                });

        builder.setNegativeButton("No",
                new DialogInterface.OnClickListener()
                {
                    public void onClick(DialogInterface dialog, int id)
                    {
                        dialog.cancel();
                        finish();
                    }
                });

        builder.create().show(); }
    }



        public void fetchTrader() {

            progressDialog.show();


            // prepare the Request
            JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, URL_USERS +
                    URL_CHAR_QUESTION +
                    URL_PARAM_MOBILE_NUMBER + mobile_number
                    //URL_CHAR_AMPERSAND +
                    //URL_PARAM_USER_ID + 1
                    , null,
                    new Response.Listener<JSONObject>() {
                        @Override
                        public void onResponse(JSONObject response) {


                            // display response
                            progressDialog.dismiss();

                            Log.d("fetchTrader()", response.toString());

                                try {
                                    if (response.getJSONObject("users").isNull("users")) {

                                        JSONObject currentUser = response.getJSONObject("users");

                                        com.innovitrix.napsamarketsales.models.User mUser = new com.innovitrix.napsamarketsales.models.User(
                                                currentUser.getInt("trader_id"),
                                                currentUser.getString("firstname"),
                                                currentUser.getString("lastname"),
                                                currentUser.getString("nrc"),
                                                currentUser.getString("gender"),
                                                currentUser.getString("mobile_number")

                                        );
                                        mUser.getFirstname();//retrieving firstname
                                        mUser.getLastname();//retrieving lastname
                                        trader_id = mUser.getTrader_id();

                                        textViewFirstName.setText(mUser.getFirstname());
                                        textViewLastName.setText(mUser.getLastname());
                                        textViewMobileNumber.setText(mUser.getMobile_number());
                                        stringTrader_id = String.valueOf(mUser.getTrader_id());
                                        layoutSupplier.setVisibility(View.VISIBLE);
                                        layoutSupplierFind.setVisibility(View.GONE);
                                    }
                                    else
                                    {
                                        trader_id =1;
                                        layoutSupplierFind.setVisibility(View.GONE);
                                        layoutSupplier.setVisibility(View.VISIBLE);
                                        Toast.makeText( BuyFromTrader.this,"Not found",Toast.LENGTH_LONG);

                                    }

                                } catch (JSONException e) {
                                    e.printStackTrace();
                                }

                    }},
                    new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            //Handle Errors here
                            progressDialog.dismiss();
                            Log.d("Error.Response", error.toString());
                            Log.d("Error.Response", error.getMessage());
                        }
                    }) {

                /** Passing some request headers* */
                @Override
                public Map<String, String> getHeaders() throws AuthFailureError {
                    Map<String, String> headers = new HashMap<>();
                    headers.put("Content-Type", "application/json");
                    headers.put("apiKey", "xxxxxxxxxxxxxxx");
                    return headers;
                }
            };

            // add it to the RequestQueue
            queue.add(jsonObjectRequest);
        }

        public void sendInformation(int trader_id,  String mobile_number, double amount) {

            progressDialog.show();

            ///prepare your JSONObject which you want to send in your web service request
            String serialNumber = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);


            JSONObject jsonObject = new JSONObject();
            try {
                jsonObject.put( "marketeer_id", trader_id);
                jsonObject.put("buyer_mobile_number",mobile_number);
                jsonObject.put("amount_due", amount);
                jsonObject.put("token_tendered", amount);
                jsonObject.put("device_serial", serialNumber );
                jsonObject.put("transaction_date", "2019-12-4");
               /// Log.d("Error.Response", jsonObject.toString());
            } catch (JSONException e) {
                e.printStackTrace();
            }

            // prepare the Request
            JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, URL_TRANSACTIONS, jsonObject,
                    new Response.Listener<JSONObject>() {
                        @Override
                        public void onResponse(JSONObject response) {

                            //Do stuff here
                            // display response
                            progressDialog.dismiss();
                            Log.d("Response", response.toString());

    //                        try {
    //
    //                            //Do stuff here
    //                            // display response
    //                            progressDialog.dismiss();
    //                            Log.d("Response", response.toString());
    //
    //
    //
    //                        } catch (JSONException e) {
    //                            e.printStackTrace();
    //                        }

                        }
                    }, new Response.ErrorListener() {


                @Override
                public void onErrorResponse(VolleyError error) {
                    //Handle Errors here
                    progressDialog.dismiss();
                    Log.d("Error.Response", error.toString());
                    Log.d("Error.Response", error.getMessage());

                }
            }) {

                /** Passing some request headers* */
                @Override
                public Map<String, String> getHeaders() throws AuthFailureError {
                    Map<String, String> headers = new HashMap<>();
                    headers.put("Content-Type", "application/json");
                    headers.put("apiKey", "xxxxxxxxxxxxxxx");
                    return headers;
                }

            };

            // add it to the RequestQueue
            queue.add(jsonObjectRequest);
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

                mobile_number_char = etSupplierMobileNo.getText().toString().length();
                if( etSupplierMobileNo.getText().toString().length()==0)
                    blockCharacterSet="123456789";

                else
                    blockCharacterSet="";
                if( etSupplierMobileNo.getText().toString().length()==1)
                    blockCharacterSet="123456780";
            }
        }

        public class PhoneNumberFilter extends NumberKeyListener {

            @Override
            public int getInputType() {
                return InputType.TYPE_CLASS_PHONE;
            }

            @Override
            protected char[] getAcceptedChars() {
                return new char[] { '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-' };
            }

            @Override
            public CharSequence filter(CharSequence source, int start, int end,
                                       Spanned dest, int dstart, int dend) {

                try {
                    // Don't let phone numbers start with 1


                    if ( source != null && blockCharacterSet.contains("" + source.charAt(0)))
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
                }catch (StringIndexOutOfBoundsException e){

                }
                return null;
            }
        }



    }
