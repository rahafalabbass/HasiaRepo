<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'firstBatch',
        'SecondBatch',
        'thirdBatch_25',  
    ];

    public function subscription(){
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}
