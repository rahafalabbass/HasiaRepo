<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class earths extends Model
{
    protected $fillable = [
        'id',
        'number',
        'space',
        'electricity',
        'available',
        'price',
        'area_id'
    ];
    use HasFactory;
    public function area(){  
        return $this->belongsTo(Areas::class);
    }

    public function subscription(){
        return $this->hasMany(subscriptions::class);
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'earth_user', 'earth_id', 'user_id');
    }
    
   
}
