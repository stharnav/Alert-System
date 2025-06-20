package com.example.alertsystem;

import android.Manifest;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.content.ContentValues;
import android.database.sqlite.SQLiteDatabase;
import android.media.AudioAttributes;
import android.net.Uri;
import android.os.Build;
import android.provider.Settings;
import android.util.Log;
import android.widget.Toast;

import androidx.annotation.RequiresPermission;
import androidx.core.app.NotificationCompat;
import androidx.core.app.NotificationManagerCompat;

import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

public class MyFirebaseMessagingService extends FirebaseMessagingService {

    private static final String CHANNEL_ID = "default_channel";
    private DatabaseHelper dbHelper;
    private SQLiteDatabase db;
    private NotificationAdapter adapter;

    @RequiresPermission(Manifest.permission.POST_NOTIFICATIONS)
    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {
        Log.d("FCM", "Message received");

        String title = "New Notification";
        String message = "You have a new message.";

        if (remoteMessage.getNotification() != null) {
            title = remoteMessage.getNotification().getTitle();
            message = remoteMessage.getNotification().getBody();
        }

        dbHelper =new DatabaseHelper(this);
        db = dbHelper.getWritableDatabase();
        ContentValues values =new ContentValues();
        values.put("title", title);
        values.put("message", message);
        try{
            db.insert("alert_table", null, values);
            //adapter.notifyDataSetChanged();
            Toast.makeText(this, "Saving Notificatio", Toast.LENGTH_SHORT).show();
        }catch(Exception e){
            Log.d("FCM","Error saving Notification");
        }
        db.close();

        showNotification(title, message);
    }

    @RequiresPermission(Manifest.permission.POST_NOTIFICATIONS)
    private void showNotification(String title, String message) {
        createNotificationChannel();

        NotificationCompat.Builder builder = new NotificationCompat.Builder(this, CHANNEL_ID)
                .setSmallIcon(android.R.drawable.ic_dialog_info) // Replace with your app icon
                .setContentTitle(title)
                .setContentText(message)
                .setPriority(NotificationCompat.PRIORITY_HIGH) // Makes it show as a heads-up notification
                .setAutoCancel(true);

        NotificationManagerCompat notificationManager = NotificationManagerCompat.from(this);
        notificationManager.notify(1, builder.build()); // ID=1, or use random for multiple

        Log.d("FCM", title);
        Log.d("FMC", message);
    }

    private void createNotificationChannel() {
        Uri soundUri = Uri.parse("android.resource://" + getPackageName() + "/" + R.raw.classic_alarm_995);
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel(
                    CHANNEL_ID,
                    "Default Channel",
                    NotificationManager.IMPORTANCE_HIGH
            );
            channel.setDescription("Used for FCM notifications");

            // Set custom sound
            AudioAttributes audioAttributes = new AudioAttributes.Builder()
                    .setUsage(AudioAttributes.USAGE_NOTIFICATION)
                    .build();
            channel.setSound(soundUri, audioAttributes);
            channel.enableLights(true);
            channel.enableVibration(true);
            channel.setVibrationPattern(new long[]{0, 250, 250, 250});


            NotificationManager manager = getSystemService(NotificationManager.class);
            if (manager != null) {
                manager.createNotificationChannel(channel);
            }
        }
    }

    @Override
    public void onNewToken(String token) {
        Log.d("FCM", "FCM Token refreshed: " + token);
        // Optionally send the token to your server
    }
}
