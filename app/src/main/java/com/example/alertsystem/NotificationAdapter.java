package com.example.alertsystem;

import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;


public class NotificationAdapter extends RecyclerView.Adapter<NotificationAdapter.NotificationViewHolder> {

    private ArrayList<Notification> notiList;
    private Context context;

    public NotificationAdapter(ArrayList<Notification> notiList, MainActivity mainActivity) {
        this.notiList = notiList;
        this.context = mainActivity;
    }

    @NonNull
    @Override
    public NotificationAdapter.NotificationViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.notification_card, parent, false);
        return new NotificationViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull NotificationAdapter.NotificationViewHolder holder, int position) {
        Notification noti = this.notiList.get(position);
        Log.d("FMC", String.valueOf(noti.getNotificationTitle()));
        holder.title.setText(noti.getNotificationTitle());
        holder.message.setText(noti.getNotificationMessage());


    }

    @Override
    public int getItemCount() {
        if(notiList.size() == 0 ){
            Toast.makeText(context, "No alerts", Toast.LENGTH_SHORT).show();
        }
        return notiList.size();
    }

    static class NotificationViewHolder extends RecyclerView.ViewHolder{

        public TextView title;
        public TextView message;
        public NotificationViewHolder(@NonNull View itemView) {
            super(itemView);

            this.title = itemView.findViewById(R.id.notification_title);
            this.message = itemView.findViewById(R.id.notification_description);

        }
    }
}
