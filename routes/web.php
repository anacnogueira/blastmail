<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\EmailListController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TemplateController;
use App\Http\Middleware\CampaignCreateSessionControl;
use Illuminate\Support\Facades\Route;
use App\Mail\EmailCampaign;
use App\Models\Campaign;

Route::view('/', 'welcome');

Route::view('/dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/email-lists',[EmailListController::class,'index'])->name('email-lists.index');
    Route::get('/email-lists/create',[EmailListController::class,'create'])->name('email-lists.create');
    Route::post('/email-lists/store',[EmailListController::class,'store'])->name('email-lists.store');

    Route::get('/email-lists/{emailList}/subscribers',[SubscriberController::class,'index'])->name('subscribers.index');
    Route::get('/email-lists/{emailList}/subscribers/create',[SubscriberController::class,'create'])->name('subscribers.create');
    Route::post('/email-lists/{emailList}/subscribers/store',[SubscriberController::class,'store'])->name('subscribers.store');
    Route::delete('/email-lists/{emailList}/subscribers/{subscriber}',[SubscriberController::class,'destroy'])->name('subscribers.destroy');

    Route::resource("templates", TemplateController::class);

    Route::get('campaigns/create/{tab?}', [CampaignController::class, 'create'])
        ->middleware(CampaignCreateSessionControl::class)
        ->name('campaigns.create');
    Route::post('campaigns/{tab?}', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::resource("campaigns", CampaignController::class);


    Route::patch("campaigns/{campaign}/restore", [CampaignController::class, 'restore'])->withTrashed()->name('campaigns.restore');
});

require __DIR__.'/auth.php';
