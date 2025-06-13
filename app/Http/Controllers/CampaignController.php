<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Services\FirebaseService;

class CampaignController extends Controller
{
    public function create() {
        return view('welcome');
    }

    public function store(Request $request, FirebaseService $firebase) {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'tokens' => 'nullable|string',
        ]);

        $tokens = array_filter(array_map('trim', explode(',', $request->tokens)));

        $campaign = Campaign::create([
            'title' => $request->title,
            'body' => $request->body,
            'location' => $request->location ?? 'Default Location',
            'tokens' => $tokens,
            'status' => 'sent',
        ]);

        foreach ($tokens as $token) {
            $firebase->sendNotification($token, $campaign->title, $campaign->body. ' ' . $campaign->location);
        }

        return redirect()->back()->with('status', 'Campaign sent!');
    }
}