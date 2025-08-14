<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\FirestoreRestServiceController;
use App\Http\Controllers\GetUserWithRadius;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [NotificationController::class, 'index']);
Route::post('/send-push', [NotificationController::class, 'sendPush']);
Route::post('/campaigns', [CampaignController::class, 'store'])->name('welcome');

Route::get('/users', [FirestoreRestServiceController::class, 'index'])->name('index');


Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns');
Route::delete('/delete-all', [CampaignController::class, 'destroyAll'])->name('campaigns');

