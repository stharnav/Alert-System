<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;

class FirebaseService
{
    public function sendNotification($deviceToken, $title, $body)
    {
        $serverKey = env('FIREBASE_SERVER_KEY');
        $credentialsPath = storage_path('app/alert-system-93cdc-2f368e1df579.json');

        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
        $credentials = new ServiceAccountCredentials($scopes, $credentialsPath);
        $token = $credentials->fetchAuthToken()['access_token'];
        $projectId = 'alert-system-93cdc';
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

         $message = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ]
        ];

        $response = Http::withToken($token)
            ->post($url, $message);

        if ($response->failed()) {
            throw new \Exception('Failed to send notification: ' . $response->body());
        }

        return $response->json();
    }
}
