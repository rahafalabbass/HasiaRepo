<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable =[
        'terminalId',
        'lang',
        'amount',
        'sub_id',
        'notes'
    ];


    public function subscription(){
        return $this->belongsTo(subscriptions::class);

    }

}
