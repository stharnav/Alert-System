<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FireStoreRestService;

class FirestoreRestServiceController extends Controller
{
    public function index(FireStoreRestService $firestore)
    {
        $documents = $firestore->getCollectionDocuments('users');

        $users = [];

        foreach ($documents['documents'] ?? [] as $doc) {
            $users[] = [
                'name' => basename($doc['name']),
                'token' => $doc['fields']['token']['stringValue'] ?? '',
                'lng' => $doc['fields']['lng']['doubleValue'] ?? '',
                'lat' => $doc['fields']['lat']['doubleValue'] ?? '',
            ];
        }

        return view('users', compact('users'));
    }

}
