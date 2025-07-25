<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FireStoreRestService;

class GetUserWithRadius extends Controller
{
     public function index(Request $request, FireStoreRestService $firestore)
    {
        dd($request->all());
        $centerLat = $request->input('lat');
        $centerLng = $request->input('lng');
        $radiusKm = $request->input('radius', 10); // default 10 km

        $documents = $firestore->getCollectionDocuments('users');

        $matchedUsers = [];

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
                }
            }
        }

        // Debug output
        foreach ($matchedUsers as $user) {
            echo "Name: {$user['name']} | Token: {$user['token']} | Lat: {$user['lat']} | Lng: {$user['lng']} | Distance: {$user['distance_km']} km<br>";
        }

        // You can optionally return JSON if it's an API endpoint:
        // return response()->json($matchedUsers);
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
}
