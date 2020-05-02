package com.innovitrix.napsamarketsales.models;


import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.support.annotation.NonNull;
import android.support.v7.widget.CardView;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.LinearLayout;
import android.widget.TextView;



import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;


import com.innovitrix.napsamarketsales.BusBuyTicketBuyerDetails;
import com.innovitrix.napsamarketsales.BusSchedule;
import com.innovitrix.napsamarketsales.BuyBusTicketActivity;
import com.innovitrix.napsamarketsales.BuyTicket;
import com.innovitrix.napsamarketsales.R;



import java.util.ArrayList;
import java.util.List;


import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_BUS_FARE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_BUS_LICENSE_PLATE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_BUS_OPERATOR_NAME;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_DEPARTURE_DATE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_DEPARTURE_TIME;

import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_CODE;
import static com.innovitrix.napsamarketsales.utils.AppConstants.KEY_ROUTE_NAME;



public class RoutePlannedAdapter extends RecyclerView.Adapter<RoutePlannedAdapter.MyViewHolder> {


    Context context;
    List<RoutePlanned> routesPlanned;
    List<RoutePlanned> routesPlannedFull;
    List<Route> routes;


    public RoutePlannedAdapter(Context context, List<RoutePlanned> routesPlanned) {
        this.context = context;
        this.routesPlanned = routesPlanned;
        this.routesPlannedFull = new ArrayList<>((routesPlanned));
    }

    @NonNull
    @Override
    public RoutePlannedAdapter.MyViewHolder onCreateViewHolder(@NonNull ViewGroup viewGroup, int i) {
        View v = LayoutInflater.from(viewGroup.getContext())
                .inflate(R.layout.card_view_route_planned,viewGroup,false);
        return new MyViewHolder(v);
    }


    @Override
    public void onBindViewHolder(@NonNull RoutePlannedAdapter.MyViewHolder myViewHolder, int i) {
        final RoutePlanned routePlanned = routesPlanned.get(i);
        myViewHolder.textViewRouteName.append(routePlanned.getRoute_name());
        myViewHolder.textViewDepartureDate.append(routePlanned.getDeparture_date());
        myViewHolder.textViewDepartureTime.append(routePlanned.getDeparture_time()+"hrs");
        myViewHolder.textViewCompany.append(routePlanned.getCompany());
        myViewHolder.textViewSeats.append(String.valueOf(routePlanned.getAvailable_seats()));
        myViewHolder.textViewBusFare.append(String.valueOf(routePlanned.getFare()));
        myViewHolder.textViewBusLicensePlate.append(routePlanned.getLicense_plate());
             myViewHolder.linearLayout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent (view.getContext(), BusBuyTicketBuyerDetails.class);
                intent.putExtra(KEY_ROUTE_NAME,routePlanned.getRoute_name());
                intent.putExtra(KEY_DEPARTURE_DATE,routePlanned.getDeparture_date());
                intent.putExtra(KEY_ROUTE_CODE,routePlanned.getRoute_code());
                intent.putExtra(KEY_DEPARTURE_TIME,routePlanned.getDeparture_time());
                intent.putExtra(KEY_BUS_OPERATOR_NAME,routePlanned.getCompany());
                intent.putExtra(KEY_BUS_LICENSE_PLATE,routePlanned.getLicense_plate());
                intent.putExtra(KEY_BUS_FARE,String.valueOf(routePlanned.getFare()));
                view.getContext().startActivity(intent);
            }
        });
    }

    @Override
    public int getItemCount() {
        return routesPlanned.size();
    }


//    @Override
//    public Filter getFilter(){
//        return routeFilter;
//    }
//
//    private Filter routeFilter = new Filter() {
//        @Override
//        protected FilterResults performFiltering(CharSequence charSequence) {
//
//            List<RoutePlanned> filteredRoutes = new ArrayList<>();
//            if (charSequence == null || charSequence.length() == 0) {
//                filteredRoutes.addAll(routesPlannedFull);
//            } else {
//                String filterPattern = charSequence.toString().toLowerCase().trim();
//                for (RoutePlanned r : routesPlannedFull) {
//                    //Query field.
//                    if (r.getRoute_name().toLowerCase().contains(filterPattern)) {
//                        filteredRoutes.add(r);
//                    }
//                }
//            }
//            FilterResults results = new FilterResults();
//            results.values = filteredRoutes;
//            return results;
//        }
//
//        @Override
//        protected void publishResults(CharSequence charSequence, FilterResults filterResults) {
//            routesPlanned.clear();
//            routesPlanned.addAll((List) filterResults.values);
//            notifyDataSetChanged();
//        }
//    };

    public  static class MyViewHolder extends RecyclerView.ViewHolder {
        private CardView card;
        public TextView textViewRouteName;
        public TextView textViewDepartureDate;
        public TextView textViewCompany;
        public TextView textViewSeats;
        public TextView textViewBusFare;
        public TextView textViewBusLicensePlate;
        private TextView  textViewDepartureTime;
        private LinearLayout linearLayout;



        public MyViewHolder(@NonNull View itemView) {
            super(itemView);
            card = (CardView) itemView.findViewById(R.id.cardViewRouteId);
            textViewRouteName = (TextView) itemView.findViewById(R.id.textView_RouteName);
            textViewDepartureDate = (TextView) itemView.findViewById(R.id.textView_Departure_Date);
            textViewDepartureTime = (TextView) itemView.findViewById(R.id.textView_Departure_Time);
            textViewCompany= (TextView) itemView.findViewById(R.id.textView_Company);
            textViewSeats = (TextView) itemView.findViewById(R.id.textView_Seats);
            textViewBusFare = (TextView) itemView.findViewById(R.id.textView_BusFare);
            textViewBusLicensePlate = (TextView) itemView.findViewById(R.id.textView_BusLicensePlate);
          //  textRoute_ID = (TextView) itemView.findViewById(R.id.textView_Route_Id);
            linearLayout = (LinearLayout) itemView.findViewById(R.id.linearLayoutScheduledRoute);



        }
    }}
