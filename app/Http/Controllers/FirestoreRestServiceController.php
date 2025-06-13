<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FireStoreRestService;

class FirestoreRestServiceController extends Controller
{
     public function index(FireStoreRestService $firestore)
        {
            $documents = $firestore->getCollectionDocuments('users');

            foreach ($documents['documents'] ?? [] as $doc) {
                // echo "Document: " . basename($doc['name']) . "<br>";
                // echo "Fields: <pre>" . print_r($doc['fields'], true) . "</pre><hr>";
                echo $doc['fields']['lat']['doubleValue'] . "<br>";
            }

        }
}
