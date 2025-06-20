package com.example.alertsystem;

public class Notification {
    private int id;
    private String notificationTitle;
    private String notificationMessage;

    public Notification(String title, String message){
        this.notificationTitle = title;
        this.notificationMessage = message;
    }

    public int getId() {
        return id;
    }

    public String getNotificationMessage() {
        return notificationMessage;
    }

    public String getNotificationTitle() {
        return notificationTitle;
    }

    public void setNotificationMessage(String notificationMessage) {
        this.notificationMessage = notificationMessage;
    }

    public void setNotificationTitle(String notificationTitle) {
        this.notificationTitle = notificationTitle;
    }

    public void setId(int id) {
        this.id = id;
    }
}
