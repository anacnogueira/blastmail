<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignEmail extends Model
{
    /** @use HasFactory<\Database\Factories\CampaignEmailFactory> */
    use HasFactory;

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function scopeStatistics(Builder $query)
    {
        return $query->selectRaw("
            sum(openings) as total_openings,
            count(subscriber_id) as total_subscribers,
            count(case when openings > 0 then subscriber_id end) as unique_opens,
            round((cast(count(case when openings > 0 then subscriber_id end) as float) / cast(count(subscriber_id) as float)) * 100) as openings_rate,
            sum(clicks) as total_clicks,
            count(case when clicks > 0 then subscriber_id end) as unique_clicks,
            round((cast(count(case when clicks > 0 then subscriber_id end) as float) / cast(count(subscriber_id) as float)) * 100) as clicks_rate
            ")
        ->first();
    }
}
