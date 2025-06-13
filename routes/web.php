<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\FirestoreRestServiceController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [NotificationController::class, 'index']);
Route::post('/send-push', [NotificationController::class, 'sendPush']);
Route::post('/campaigns', [CampaignController::class, 'store'])->name('welcome');

Route::get('/firestore-rest', [FirestoreRestServiceController::class, 'index'])->name('index');