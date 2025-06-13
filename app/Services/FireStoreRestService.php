<?php

namespace App\Services;

use Google\Auth\OAuth2;
use Illuminate\Support\Facades\Http;

class FirestoreRestService
{
    protected string $projectId;
    protected string $accessToken;

    public function __construct()
    {
        $keyFile = storage_path('app/alert-system-93cdc-2f368e1df579.json');
        $jsonKey = json_decode(file_get_contents($keyFile), true);

        $this->projectId = $jsonKey['project_id'];

        $oauth = new OAuth2([
            'audience' => 'https://oauth2.googleapis.com/token',
            'issuer' => $jsonKey['client_email'],
            'signingAlgorithm' => 'RS256',
            'signingKey' => $jsonKey['private_key'],
            'tokenCredentialUri' => $jsonKey['token_uri'],
            'scope' => ['https://www.googleapis.com/auth/datastore'],
        ]);

        $oauth->fetchAuthToken();
        $this->accessToken = $oauth->getLastReceivedToken()['access_token'];
    }

    public function getCollectionDocuments($collection)
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}";

        $response = Http::withToken($this->accessToken)->get($url);

        return $response->successful() ? $response->json() : [];
    }

}
