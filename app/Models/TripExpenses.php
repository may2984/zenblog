<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripExpenses extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'item', 'member_id', 'date_time', 'amount'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
