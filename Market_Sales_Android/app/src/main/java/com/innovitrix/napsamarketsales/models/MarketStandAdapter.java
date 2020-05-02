package com.innovitrix.napsamarketsales.models;


import android.support.annotation.NonNull;
import android.support.v7.widget.CardView;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;
import com.innovitrix.napsamarketsales.R;
import java.util.ArrayList;
import android.util.Log;

public class MarketStandAdapter extends RecyclerView.Adapter<MarketStandAdapter.ViewHolder> {

    private static final String TAG = "MarketStandAdapter";

    private ArrayList<MarketStand> mMarketStands = new ArrayList<>();
    private OnMarketStandListener mOnMarketStandListener;


    public MarketStandAdapter(ArrayList<MarketStand> mMarketStands, OnMarketStandListener onMarketStandListener) {
        this.mMarketStands = mMarketStands;
        this.mOnMarketStandListener = onMarketStandListener;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.card_view_market_stand, parent, false);
        return new ViewHolder(view, mOnMarketStandListener);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        holder.textViewStandNumber.append(mMarketStands.get(position).getStand_number());
        holder.textViewStandPrice.append(String.valueOf(mMarketStands.get(position).getStand_price()));
    }

    @Override
    public int getItemCount() {
        return mMarketStands.size();
    }

    public class ViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {

        CardView card;
        TextView textViewStandNumber;
        TextView textViewStandPrice;
        LinearLayout linearLayout;
        OnMarketStandListener mOnMarketStandListener;

        public ViewHolder(View itemView,OnMarketStandListener onMarketStandListener) {
            super(itemView);

            //card = (CardView) itemView.findViewById(R.id.cardViewMarketStand);
            textViewStandNumber = (TextView) itemView.findViewById(R.id.textView_Market_Stand_Number);
            textViewStandPrice = (TextView) itemView.findViewById(R.id.textView_Market_Stand_Price);
           // linearLayout = (LinearLayout) itemView.findViewById(R.id.linearLayoutMarketStand);
            mOnMarketStandListener = onMarketStandListener;

            itemView.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            Log.d(TAG, "onClick: " + getAdapterPosition());
            mOnMarketStandListener.onMarketStandClick(getAdapterPosition());
        }
    }

    public interface OnMarketStandListener{
        void onMarketStandClick(int position);
    }
}
