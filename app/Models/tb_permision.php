<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_permision extends Model
{
    use HasFactory;
    protected $table = 'tb_permision';
    protected $fillable =[
        'Name_User',
        'Perm_user'
    ];
}
