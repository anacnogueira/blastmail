<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\EmailListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\TrackingController;
use App\Http\Middleware\CampaignCreateSessionControl;
use App\Mail\EmailCampaign;
use App\Models\Campaign;
use App\Models\CampaignEmail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::get('/emails', function(){
    $campaign = Campaign::find(12);
    $email = $campaign->emails()->first();

    $emailCampaign = new EmailCampaign($campaign, $email);

    return $emailCampaign->render();
});

Route::get('/t/{email}/o', [TrackingController::class, 'openings'])->name('tracking.openings');

Route::view('/', 'welcome');

Route::view('/dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    //region Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //endregion

    //region EmailLists
    Route::get('/email-lists',[EmailListController::class,'index'])->name('email-lists.index');
    Route::get('/email-lists/create',[EmailListController::class,'create'])->name('email-lists.create');
    Route::post('/email-lists/store',[EmailListController::class,'store'])->name('email-lists.store');
    //endregion

    Route::get('/email-lists/{emailList}/subscribers',[SubscriberController::class,'index'])->name('subscribers.index');
    Route::get('/email-lists/{emailList}/subscribers/create',[SubscriberController::class,'create'])->name('subscribers.create');
    Route::post('/email-lists/{emailList}/subscribers/store',[SubscriberController::class,'store'])->name('subscribers.store');
    Route::delete('/email-lists/{emailList}/subscribers/{subscriber}',[SubscriberController::class,'destroy'])->name('subscribers.destroy');
    //endregion

    //region Templates
    Route::resource("templates", TemplateController::class);
    //endregion

    //region Campaigns
    Route::get('campaigns/', [CampaignController::class, 'index'])->name('campaigns.index');

    Route::get('campaigns/create/{tab?}', [CampaignController::class, 'create'])
        ->middleware(CampaignCreateSessionControl::class)
        ->name('campaigns.create');

    Route::get('/campaigns/{campaign}/{what?}', [CampaignController::class, 'show'])->name('campaigns.show')->withTrashed();

    Route::post('campaigns/{tab?}', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::delete("campaigns/{campaign}", [CampaignController::class,'destroy'])->name('campaigns.destroy');
    Route::patch("campaigns/{campaign}/restore", [CampaignController::class, 'restore'])->withTrashed()->name('campaigns.restore');
    //endregion
});

require __DIR__.'/auth.php';
