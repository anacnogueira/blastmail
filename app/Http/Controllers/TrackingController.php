<?php

namespace App\Http\Controllers;

use App\Models\CampaignEmail;

class TrackingController extends Controller
{
    public function openings(CampaignEmail $email)
    {
        if (! $mail->campaign->track_open) {
            return;
        }

        $email->openings++;
        $email->save();
    }

    public function click(CampaignEmail $email)
    {
        if ($mail->campaign->track_click) {
            $email->clicks++;
            $email->save();
        }

        return redirect()->away(request()->get('f'));
    }
}

