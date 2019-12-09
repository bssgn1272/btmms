package com.innovitrix.napsamarketsales.model;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.support.annotation.NonNull;
import android.support.v7.widget.CardView;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.innovitrix.napsamarketsales.BuyBusTicketActivity;
import com.innovitrix.napsamarketsales.BuyFromTrader;
import com.innovitrix.napsamarketsales.R;
import com.squareup.picasso.Picasso;

import java.util.List;
import java.util.Random;


public class MenuAdapter extends RecyclerView.Adapter<MenuAdapter.MyViewHolder> {

    Context context;
    List<MenuData> menuDataList;


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
        return new MyViewHolder(itemView);

    }


    @Override
    public void onBindViewHolder(final MyViewHolder viewHolder, final int i) {

        viewHolder.menuName.setText(menuDataList.get(i).getMenuName());
        viewHolder.menuImage.setImageResource(menuDataList.get(i).getMenuImage());
        viewHolder.cardView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                Toast.makeText(context,"You have clicked " + menuDataList.get(i).getMenuName(),Toast.LENGTH_LONG).show();

                int menuId = menuDataList.get(i).getMenuId();


                switch (menuId) {
                    case 1:
                    //
                        break;
                    case 2:
                        Intent intent2 = new Intent(context, BuyFromTrader.class);
                        context.startActivity(intent2);
                        break;
                    case 3:
                        //Intent intent3 = new Intent(context, BuyFromTrader.class);
                      //  context.startActivity(intent3);
                        Intent intent3 = new Intent(context, BuyBusTicketActivity.class);
                        context.startActivity(intent3);

                        break;
                    case 4:
                        //handle menu3 click p
                        break;
                    case 5:
                        //handle menu3 click p
                        break;
                    case  6:
                        //handle menu3 click
                        break;
                    default:
                        break;
                }




             //   Intent intent = new Intent(context, BuyFromTrader.class);

                // passing data to the book activity
          //      intent.putExtra("Title",mData.get(position).getTitle());
            //    intent.putExtra("Description",mData.get(position).getDescription());
              //  intent.putExtra("Thumbnail",mData.get(position).getThumbnail());
                // start the activity
                //   context.startActivity(intent);
                }
        });
    }



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

            menuName = (TextView) itemView.findViewById(R.id.menuName);
            menuImage = (ImageView) itemView.findViewById(R.id.menuImageId);
            cardView = (CardView) itemView.findViewById(R.id.cardViewId);
            linearLayout = (LinearLayout) itemView.findViewById(R.id.linearLayout);
    }
}}
