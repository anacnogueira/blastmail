<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriberFactory> */
    use HasFactory;

     /**
     * Get the user that owns the Subscriber
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function emailList(): BelongsTo
    {
        return $this->belongsTo(EmailList::class);
    }
}
