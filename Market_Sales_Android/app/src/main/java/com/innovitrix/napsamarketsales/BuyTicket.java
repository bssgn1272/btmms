package com.innovitrix.napsamarketsales;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_FIRSTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_LASTNAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_MOBILE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_NRC;

public class BuyTicket extends AppCompatActivity {
    String firstname;
    String lastname;
    String  nrc;
    String mobilenumber;
    String routecode;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_buy_ticket);
        firstname = getIntent().getStringExtra(KEY_FIRSTNAME);
        lastname = getIntent().getStringExtra(KEY_LASTNAME);
        nrc =  getIntent().getStringExtra(KEY_NRC);
        mobilenumber = getIntent().getStringExtra(KEY_MOBILE);
       // routecode = getIntent().getStringExtra(KEY_ROUTE_CODE);
    }
}
