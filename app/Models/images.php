<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class images extends Model
{
    use HasFactory;
    protected $fillable=[
        'url',
        'subscription_id'
    ];
    public function subscription(){

        return $this->belongsTo(subscriptions::class);
    }
}
