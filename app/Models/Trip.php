<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['name','status'];

    protected $default = [
        'status' => false,
    ];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'trip_members')->withTimestamps();
    }

    public function getTrips()
    {
        return Trip::where('status', 1)->get();
    }
}
