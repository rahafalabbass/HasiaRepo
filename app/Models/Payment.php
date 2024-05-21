<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'firstBatch',
        'SecondBatch',
        'thirdBatch_25',
        'cartNumber',  
        'amount',
        'batchName'   
    ];

    public function subscription(){
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    
}
?>