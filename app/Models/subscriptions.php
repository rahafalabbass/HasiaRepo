<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscriptions extends Model
{
    use HasFactory;
    protected $fillable = ['name','father_name','mother_name','birth','ID_personal','address', 
    
    'phone1','phone2','email','factory_name','factory_ent' ,'Industry_name','ID_classification', 
     
    'Money','Num_Worker','Value_equipment','Num_Year_Worker','Num_Exce','Q_Water','Q_Electricity','payment_method',
    'area_id','earth_id','notes','state_checked','state_approval','state_complated','state_specialize','state_data',
    'state_cancelled','user_id','area_id','earth_id'

];

    public function images(){
        return $this->hasMany(images::class, 'subscription_id');
    }
    

    public function earth(){

        return $this->belongsTo(earths::class);
        
    }
   
    public function customizes(){

        return $this->hasMany(Custmition::class);
    }
    public function user(){
        return $this->belongsTo(User::class,'id');
    }

    public function notes(){
        return $this->hasMany(Note::class,'subscription_id');
    }

    public function payments(){
        return $this->hasMany(Payment::class, 'subscription_id');
    }

    public function paymentStatus()
    {
        return $this->hasOne(PaymentStatus::class, 'subscription_id');
    }

    protected $hidden = [
        'timestamps',
       
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'Q_electricity' => 'string',
    ];
}
