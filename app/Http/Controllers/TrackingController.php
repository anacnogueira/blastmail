<?php

namespace App\Http\Controllers;

use App\Models\CampaignEmail;

class TrackingController extends Controller
{
    public function openings(CampaignEmail $email)
    {
        $email->openings++;
        $email->save();
    }

    public function click(CampaignEmail $email)
    {
        $email->clicks++;
        $email->save();

        return redirect()->away(request()->get('f'));
    }
}

