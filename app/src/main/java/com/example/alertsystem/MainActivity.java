package com.example.alertsystem;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.database.sqlite.SQLiteDatabase;
import android.location.Location;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.Manifest;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.annotation.RequiresPermission;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.google.android.gms.location.LocationServices;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.messaging.FirebaseMessaging;

import com.google.android.gms.location.FusedLocationProviderClient;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class MainActivity extends AppCompatActivity {
    private RecyclerView body;
    private DatabaseHelper dbHelper;
    private NotificationAdapter adapter;
    private ArrayList<Notification>notiList;
    private FusedLocationProviderClient fusedLocationClient;
    private FirebaseFirestore db;
    private FirebaseAuth auth;
    private static final int LOCATION_PERMISSION_REQUEST_CODE = 1001;
    private String token;
    private  FirebaseUser user;
    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        //
        setContentView(R.layout.activity_main);
        body = findViewById(R.id.main_body);
        body.setLayoutManager(new LinearLayoutManager(this));
        dbHelper = new DatabaseHelper(this);
        notiList = dbHelper.getNotification();

        adapter = new NotificationAdapter(notiList, this);
        body.setAdapter(adapter);


        //
        FirebaseMessaging.getInstance().getToken()
                .addOnCompleteListener(task -> {
                    if (!task.isSuccessful()) {
                        Log.w("FCM", "Fetching FCM registration token failed", task.getException());
                        return;
                    }

                    // Get new FCM registration token
                    token = task.getResult();
                    Log.d("FCM", "FCM Token: " + token);
                });

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            if (ContextCompat.checkSelfPermission(this, Manifest.permission.POST_NOTIFICATIONS)
                    != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(this,
                        new String[]{Manifest.permission.POST_NOTIFICATIONS},
                        1); // 1 = request code, change as needed
            }
        }

        fusedLocationClient = LocationServices.getFusedLocationProviderClient(this);
        db = FirebaseFirestore.getInstance();
        auth = FirebaseAuth.getInstance();
        requestLocation();


    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(@NonNull MenuItem item) {
        String id = (String) item.getTitle();

        if(id.equals("Delete")){
            dbHelper.delete();
            Toast.makeText(this, "Alerts deleted", Toast.LENGTH_SHORT).show();
        }

        if(id.equals("Restart")){
            Intent intent = getIntent();
            finish();
            startActivity(intent);
        }

        notiList = dbHelper.getNotification();
        adapter = new NotificationAdapter(notiList, this);
        body.setAdapter(adapter);
        Toast.makeText(this, "Page refreshed", Toast.LENGTH_SHORT).show();

        return super.onOptionsItemSelected(item);
    }

    public void requestLocation(){
        if(ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED){
            ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, LOCATION_PERMISSION_REQUEST_CODE);
        }else{
            getLocationAndSave();
        }
    }

    @RequiresPermission(allOf = {Manifest.permission.ACCESS_FINE_LOCATION, Manifest.permission.ACCESS_COARSE_LOCATION})
    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults, int deviceId) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults, deviceId);
        if (requestCode == LOCATION_PERMISSION_REQUEST_CODE &&
                grantResults.length > 0 &&
                grantResults[0] == PackageManager.PERMISSION_GRANTED) {
            getLocationAndSave();
        }
    }


    @RequiresPermission(allOf = {Manifest.permission.ACCESS_FINE_LOCATION, Manifest.permission.ACCESS_COARSE_LOCATION})
    private void getLocationAndSave() {
        fusedLocationClient.getLastLocation()
                .addOnSuccessListener(this, new OnSuccessListener<Location>() {
                    @Override
                    public void onSuccess(Location location) {
                        if (location != null ) {
                            double lat = location.getLatitude();
                            double lng = location.getLongitude();

                            Map<String, Object> locationData = new HashMap<>();
                            locationData.put("lat", lat);
                            locationData.put("lng", lng);
                            locationData.put("timestamp", System.currentTimeMillis());
                            locationData.put("token", token);


                            auth.signInAnonymously().addOnCompleteListener(task -> {
                                user = auth.getCurrentUser();
                                String userId = user.getUid();
                                db.collection("users").document(userId).set(locationData)
                                        .addOnSuccessListener(unused -> {
                                            Toast.makeText(MainActivity.this, "Location has been updated successfully", Toast.LENGTH_SHORT).show();
                                        })
                                        .addOnFailureListener(e -> {
                                            // Handle failure
                                            Toast.makeText(MainActivity.this, "Error", Toast.LENGTH_SHORT).show();
                                        });
                            });

                        }
                    }
                });
    }
}
