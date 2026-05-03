<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ShowCampaignRequest extends FormRequest
{
    public function checkWhat()
    {
        if (is_null($this->route('what'))) {
            return to_route('campaigns.show', ['campaign' => $this->route('campaign'), 'what' => 'statistics']);
        }

        return;
    }

    public function authorize(): bool
    {
        $what = $this->route('what') ?: 'statistics';

        abort_unless(in_array($what, ['statistics', 'open', 'clicked']), 404);

        return true;
    }
}
