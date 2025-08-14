<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Services\FirebaseService;
use App\Services\FireStoreRestService;

class CampaignController extends Controller
{
    public function create() {
        return view('welcome');
    }

    public function index() {
        $campaigns = Campaign::orderBy('created_at', 'desc')->get();
        return view('campaigns', compact('campaigns'));
    }


    public function store(Request $request, FirebaseService $firebase, FireStoreRestService $firestore) {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'latitude' => 'required|numeric',
            'longtitude' => 'required|numeric',
            'location' => 'nullable|string|max:255',
        ]);

        $tokens = $this->getUserWithRadius($request->latitude, $request->longtitude, $request->radius, $firestore);
        //dd($tokens);
        //$tokens = array_filter(array_map('trim', explode(',', $request->tokens)));

        if(empty($tokens)) {
            return redirect()->back()->with('status', 'No users found within the specified radius.');
        }
        
        $campaign = Campaign::create([
            'title' => $request->title,
            'body' => $request->body,
            'location' => $request->location ?? 'Default Location',
            'tokens' => json_encode($tokens),
            'status' => 'sent',
        ]);

        $count = 0;
        foreach ($tokens as $token) {
            $firebase->sendNotification($token, $campaign->title, $campaign->body. ' ' . $campaign->location);
            $count++;
        }

        return redirect()->back()->with('status', "Campaign sent to $count users!");
    }


    public function getUserWithRadius($centerLat, $centerLng, $radiusKm, FireStoreRestService $firestore) {

        $documents = $firestore->getCollectionDocuments('users');
        $matchedUsers = [];
        $matchedTokens = [];

         foreach ($documents['documents'] ?? [] as $doc) {
            $lat = $doc['fields']['lat']['doubleValue'] ?? null;
            $lng = $doc['fields']['lng']['doubleValue'] ?? null;

            if ($lat !== null && $lng !== null) {
                $distance = $this->haversineDistance($centerLat, $centerLng, $lat, $lng);

                if ($distance <= $radiusKm) {
                    $matchedUsers[] = [
                        'name' => basename($doc['name']),
                        'token' => $doc['fields']['token']['stringValue'] ?? null,
                        'lat' => $lat,
                        'lng' => $lng,
                        'distance_km' => round($distance, 2),
                    ];
                    $matchedTokens[] = $doc['fields']['token']['stringValue'] ?? null;
                }
            }
        }

        // Debug output
        foreach ($matchedUsers as $user) {
            echo "Name: {$user['name']} | Token: {$user['token']} | Lat: {$user['lat']} | Lng: {$user['lng']} | Distance: {$user['distance_km']} km<br>";
        }

        return $matchedTokens;
    }


    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius of Earth in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function destroyAll()
    {
        Campaign::truncate(); // This deletes all records in the campaigns table
        return redirect()->back()->with('status', 'All campaigns deleted successfully.');
    }

}