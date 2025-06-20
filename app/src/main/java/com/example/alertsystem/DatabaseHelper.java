package com.example.alertsystem;

import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import androidx.annotation.Nullable;

import java.util.ArrayList;

public class DatabaseHelper extends SQLiteOpenHelper {
    private static final String DB_NAME = "alerts";
    private static final int DB_VERSION = 1;

    public DatabaseHelper(@Nullable Context context) {
        super(context, DB_NAME, null, DB_VERSION);
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        db.execSQL("CREATE TABLE alert_table (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, message TEXT)");
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS alert_table");
        onCreate(db);
    }


    public ArrayList<Notification> getNotification() {
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery("SELECT * FROM  alert_table  ORDER BY id DESC LIMIT 10", null);
        ArrayList<Notification> notiList = new ArrayList<>();
        if (cursor.moveToFirst()) {
            do {
                int id = cursor.getInt(0);
                String title = cursor.getString(1);
                String message = cursor.getString(2);

                Notification notiItem = new Notification(title, message);
                notiItem.setId(id);

                notiList.add(notiItem);
            } while (cursor.moveToNext());
        }
        cursor.close();
        return notiList;
    }

}
