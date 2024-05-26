<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class permision extends Model
{
    use HasFactory;
    protected $table = 'tb_permission';
    protected $fillable =[
        'Name_User',
        'Perm_user',
    ];
         
}
