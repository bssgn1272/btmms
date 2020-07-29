package com.innovitrix.napsamarketsales.models;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.cardview.widget.CardView;
import androidx.recyclerview.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;


import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.innovitrix.napsamarketsales.BusSearch;
import com.innovitrix.napsamarketsales.ChangePin;
import com.innovitrix.napsamarketsales.CheckBalance;
import com.innovitrix.napsamarketsales.FindTrader;

import com.innovitrix.napsamarketsales.MakeSell;
import com.innovitrix.napsamarketsales.PayMarketFees;
import com.innovitrix.napsamarketsales.R;
import com.innovitrix.napsamarketsales.SharedPrefManager;
import com.innovitrix.napsamarketsales.StartActivity;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHAR_QUESTION;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_CHECK_BALANCE;
import static com.innovitrix.napsamarketsales.utils.UrlEndpoints.URL_PARAM_MOBILE_NUMBER;


public class MenuAdapter extends RecyclerView.Adapter<MenuAdapter.MyViewHolder> {

    Context context;
    List<MenuData> menuDataList;
    String mobile_number;

    String stringAmount;
    private ProgressDialog progressDialog;
    RequestQueue queue;


    public MenuAdapter(Context context,  List<MenuData> menuDataList) {
        this.context = context;
        this.menuDataList = menuDataList;
    }

    @NonNull
    @Override
    public MyViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int i) {
       // View itemView = LayoutInflater.from(viewGroup.getContext())
           //     .inflate(R.layout.menu_item, viewGroup, false);

       // return new MyViewHolder(itemView);

        View itemView ;
        LayoutInflater mInflater = LayoutInflater.from(context);
        itemView = mInflater.inflate(R.layout.card_view_menu_item,parent,false);
        return new MenuAdapter.MyViewHolder(itemView);




    }


    @Override
    public void onBindViewHolder(final MenuAdapter.MyViewHolder viewHolder, final int i) {
        progressDialog = new ProgressDialog(context);
        progressDialog.setMessage("Loading...");
        progressDialog.setCancelable(false);
        queue = Volley.newRequestQueue(context);

//        fetchTrader();
        progressDialog.dismiss();

        viewHolder.menuName.setText(menuDataList.get(i).getMenuName());
        if (menuDataList.get(i).getMenuId()==1) {
            viewHolder.menuImage.setImageResource(R.drawable.ic_local_grocery_store_black_24dp);
            viewHolder.menuImage.setBackgroundResource(R.drawable.circle_bakcground_sales);
        }
        if (menuDataList.get(i).getMenuId()==2)
        {
           viewHolder.menuImage.setImageResource(R.drawable.ic_local_shipping_black_24dp);
            viewHolder.menuImage.setBackgroundResource(R.drawable.circle_background_order);
        }
        if (menuDataList.get(i).getMenuId()==3)
        {
            viewHolder.menuImage.setImageResource( R.drawable.ic_directions_bus_black_24dp);
            viewHolder.menuImage.setBackgroundResource(R.drawable.circle_background_bus);
        }

        if (menuDataList.get(i).getMenuId()==4)
        {
          viewHolder.menuImage.setImageResource( R.drawable.ic_payment_black_24dp);
            viewHolder.menuImage.setBackgroundResource(R.drawable.circle_background_payment);
        }


        if (menuDataList.get(i).getMenuId()==5)
        {
           viewHolder.menuImage.setImageResource( R.drawable.ic_attach_money_black_24dp);
            viewHolder.menuImage.setBackgroundResource(R.drawable.circle_background_balance);
        }
        if (menuDataList.get(i).getMenuId()==6)
        {
           viewHolder.menuImage.setImageResource(R.drawable.ic_lock_open_black_24dp);
            viewHolder.menuImage.setBackgroundResource(R.drawable.circle_background_pin);
        }
        if (menuDataList.get(i).getMenuId()==7) {
            viewHolder.menuImage.setImageResource(R.drawable.ic_power_settings_new_black_24dp);
            viewHolder.menuImage.setBackgroundResource(R.drawable.circle_background_logout);
        }

     if (menuDataList.get(i).getMenuId()==1)
     { }

        viewHolder.cardView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                int menuId = menuDataList.get(i).getMenuId();

                Intent i;

                switch (menuId) {
                    case 1:
                        i = new Intent(context, MakeSell.class);

                    context.startActivity(i);
                        break;
                    case 2:
                        i = new Intent(context, FindTrader.class);
                        context.startActivity(i);
                        break;
                    case 3:
                        i = new Intent(context, BusSearch.class);
                        context.startActivity(i);
                        break;
                    case 4:
                        i = new Intent(context, PayMarketFees.class);
                        context.startActivity(i);
                        break;

                    case 5:
                        i = new Intent(context, CheckBalance.class);
                        context.startActivity(i);
                        break;

////                        i = new Intent(context, BuyFromTrader.class);
////                        context.startActivity(i);
////                        break;
//                        progressDialog = new ProgressDialog(context);
//                        progressDialog.setMessage("Loading...");
//                        progressDialog.setCancelable(false);
//                        queue = Volley.newRequestQueue(context);
//
//                        fetchTrader();
//                        progressDialog.dismiss();
//
//
//                        AlertDialog.Builder builder1 = new AlertDialog.Builder(context);
//
//                        builder1.setMessage("Your account balance is " +  stringAmount+"");
//
//                        builder1.setNeutralButton("Ok",
//                                new DialogInterface.OnClickListener() {
//                                    public void onClick(DialogInterface dialog, int id) {
//                                        dialog.cancel();
//                                    }
//                                });
//
//                        builder1.create().show();show
                      //  break;
                    case 6:

                        i = new Intent(context, ChangePin.class);
                        context.startActivity(i);
                        break;

                    case 7:

                       // i = new Intent(context, LoginActivity.class);
                       // context.startActivity(i);

                        AlertDialog.Builder builder = new AlertDialog.Builder(context);


                        builder = new AlertDialog.Builder( context);

                        builder.setMessage("Confirm log out?");
                        builder.setPositiveButton("Yes",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                        dialog.cancel();

                                        SharedPrefManager.getInstance(context).logout();

                                        Intent intent = new Intent(context,StartActivity.class);
                                        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                                        context.startActivity(intent);
                                        //context..finish();
                                      //  Intent broadcastIntent = new Intent();
                                       // broadcastIntent.setAction("com.package.ACTION_LOGOUT");
                                        //context.sendBroadcast(broadcastIntent);

                                    }
                                });

                        builder.setNegativeButton("No",
                                new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                        dialog.cancel();
                                    }
                                });

                        builder.create().show();

                        break;
                    default:
                        break;

                }}});}

    @Override
    public int getItemCount()
    {
        return menuDataList.size();

    }

   public  static class MyViewHolder extends RecyclerView.ViewHolder {
       TextView menuName;
       ImageView menuImage;
       CardView cardView ;
       LinearLayout linearLayout;


        public MyViewHolder(View itemView) {
            super(itemView);
           // parent=itemView.findViewById(R.id.parent);
            //imageViewId= itemView.findViewById(R.id.i.imageViewMenu);
           // name=itemView.findViewById(R.id.name);

            menuName = (TextView) itemView.findViewById(R.id.textViewMenuName);
            menuImage = (ImageView) itemView.findViewById(R.id.imageViewMenuImage);
            cardView = (CardView) itemView.findViewById(R.id.cardViewId);
            linearLayout = (LinearLayout) itemView.findViewById(R.id.linearLayoutMenu);



    }

   }
    public void fetchTrader() {
        //userObjectLength =0;

        progressDialog.show();
        mobile_number = SharedPrefManager.getInstance(context).getUser().getMobile_number();

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, URL_CHECK_BALANCE +
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


                            JSONObject currentUser = response.getJSONObject("user");
                            //    userObjectLength = currentUser.length();
                            com.innovitrix.napsamarketsales.models.User mUser = new com.innovitrix.napsamarketsales.models.User(
                                    currentUser.getString("trader_id"),
                                    currentUser.getDouble("token_balance")

                            );

                            //either display using listView or recyclerView
                            //store in dp temporal DB
                            //mDatabase.createRoutes(mRoute);

                            // mUser.getFirstname();//retrieving firstname
                            //mUser.getLastname();//retrieving lastname
                             stringAmount = "K"+ String.valueOf(mUser.getBalance());
                            //   textViewBalance.setText(stringAmount);

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        //Handle Errors here
                        progressDialog.dismiss();
                        Log.d("Error.Response", error.toString());
                        //     Log.d("Error.Response", error.getMessage());
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

}
