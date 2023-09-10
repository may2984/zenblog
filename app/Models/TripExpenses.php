<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripExpenses extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'member_id', 'date_time', 'amount'];
}
