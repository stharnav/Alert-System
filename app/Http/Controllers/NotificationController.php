<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index(){
        return view('welcome');
    }

    public function sendPush(Request $request)
    {
        $deviceToken = $request->input('token'); // from request
        $title = $request->input('title', 'Hello');
        $body = $request->input('body', 'This is a test notification.');
        $location = $request->input('location', 'Default Location');

        $result = $this->firebase->sendNotification($deviceToken, $title, $body + $location);

        return redirect()->back()->with('status', 'Notification sent!');
    }
}
