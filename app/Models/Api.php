<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $table = 'api';

    protected $fillable = 
    [
        'memory_used',
        'execution_time',
        'execution_date'
    ];
}
