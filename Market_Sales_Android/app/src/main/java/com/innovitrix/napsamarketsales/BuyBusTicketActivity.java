package com.innovitrix.napsamarketsales;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.ArrayAdapter;
import android.widget.Spinner;

public class BuyBusTicketActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_buy_bus_ticket);
        //Spinner spinnerFromTown = (Spinner) findViewById(R.id.spFromTown);

        ArrayAdapter<String> adapterFromTown = new ArrayAdapter<>(BuyBusTicketActivity.this,
                android.R.layout.simple_list_item_1, getResources().getStringArray(R.array.FromBusStation));
        adapterFromTown.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
      //  spinnerFromTown.setAdapter(adapterFromTown);


        //To
        Spinner spinnerToTown = (Spinner) findViewById(R.id.spToTown);
        ArrayAdapter<String> adapterToTown = new ArrayAdapter<>(BuyBusTicketActivity.this,
                android.R.layout.simple_list_item_1, getResources().getStringArray(R.array.FromBusStation));
        adapterToTown.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerToTown.setAdapter(adapterToTown);


    }
}
