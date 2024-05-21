<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $fillable =[
        
        'subscription_id',
        'description'
    ];
    public function subscriptions(){
        return $this->belongsTo(User::class,'id');
    }
    
    public function subscription(){
        return $this->belongsTo(subscriptions::class);
    }
}
