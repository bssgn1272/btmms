package com.innovitrix.napsamarketsales.models;


import android.content.Context;
import android.support.annotation.NonNull;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.TextView;


import com.innovitrix.napsamarketsales.R;



import java.util.ArrayList;
import java.util.List;

public class RoutePlannedAdapter extends RecyclerView.Adapter<RoutePlannedAdapter.MyViewHolder> implements Filterable{


    Context context;
    List<RoutePlanned> routesPlanned;
    List<RoutePlanned> routesPlannedFull;



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
        RoutePlanned routePlanned = routesPlanned.get(i);
        myViewHolder.textViewRouteName.setText(routePlanned.getRoute_name());
        myViewHolder.textViewBusFare.setText(String.valueOf(routePlanned.getBus_fare()));
        myViewHolder.textViewCompany.setText(routePlanned.getCompany());
        myViewHolder.textViewBusLicensePlate.setText(routePlanned.getBus_license_plate());

    }

    @Override
    public int getItemCount() {
        return routesPlanned.size();
    }

    @Override
    public Filter getFilter(){
        return routeFilter;
    }

    private Filter routeFilter = new Filter() {
        @Override
        protected FilterResults performFiltering(CharSequence charSequence) {

            List<RoutePlanned> filteredRoutes = new ArrayList<>();
            if (charSequence == null || charSequence.length() == 0) {
                filteredRoutes.addAll(routesPlannedFull);
            } else {
                String filterPattern = charSequence.toString().toLowerCase().trim();
                for (RoutePlanned r : routesPlannedFull) {
                    //Query field.
                    if (r.getRoute_name().toLowerCase().contains(filterPattern)) {
                        filteredRoutes.add(r);
                    }
                }
            }
            FilterResults results = new FilterResults();
            results.values = filteredRoutes;
            return results;
        }

    @Override
    protected void publishResults(CharSequence charSequence, FilterResults filterResults) {
        routesPlanned.clear();
        routesPlanned.addAll((List) filterResults.values);
        notifyDataSetChanged();
    }
    };


    public  static class MyViewHolder extends RecyclerView.ViewHolder {
      public TextView textViewRouteName;
      public TextView textViewBusFare;
      public TextView textViewCompany;
      public TextView textViewBusLicensePlate;



        public MyViewHolder(@NonNull View itemView) {
            super(itemView);
             textViewRouteName = (TextView) itemView.findViewById(R.id.textView_RouteName);
             textViewBusFare = (TextView) itemView.findViewById(R.id.textView_BusFare);
             textViewCompany= (TextView) itemView.findViewById(R.id.textView_Company);
             textViewBusLicensePlate = (TextView) itemView.findViewById(R.id.textView_BusLicensePlate);

        }
    }}
