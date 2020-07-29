package com.innovitrix.napsamarketsales;

import android.content.Intent;
import android.graphics.drawable.AnimationDrawable;
import androidx.constraintlayout.widget.ConstraintLayout;
import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

public class StartActivity extends AppCompatActivity {
    private long backPressedTime;
    private Toast backToast;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_start);
        ConstraintLayout constraintLayout = findViewById(R.id.layout);
        AnimationDrawable animationDrawable = (AnimationDrawable) constraintLayout.getBackground();
        animationDrawable.setEnterFadeDuration(2000);
        animationDrawable.setExitFadeDuration(4000);
        animationDrawable.start();
        final TextView textViewBuyBusTicket = (TextView) findViewById(R.id.textView_buy_bus_ticket);
        final TextView textViewLogin = (TextView) findViewById(R.id.textView_login);
        SharedPrefManager.getInstance(StartActivity.this).logout();

       // textViewBuyBusTicket.setTextColor(textViewBuyBusTicket.getTextColors().withAlpha(128));
        textViewBuyBusTicket.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(StartActivity.this,BusSearchE.class);
                startActivity(intent);
            }
        });
        textViewLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Intent intent = new Intent(StartActivity.this,LoginActivity.class);

                startActivity(intent);
                //finish();
                            }
        });
    }
    @Override
    public void onBackPressed() {

        finish();
//        if (backPressedTime + 2000 > System.currentTimeMillis()) {
//            backToast.cancel();
//            finish();
//            //super.onBackPressed();
//            //return;
//        } else {
//            backToast = Toast.makeText(getBaseContext(), "Press back again to exit", Toast.LENGTH_SHORT);
//            backToast.show();
//        }
//        backPressedTime = System.currentTimeMillis();
    }
}