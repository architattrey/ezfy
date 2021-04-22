<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Institutes extends Model
{
    protected $table = 'institutes';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'institute',
        'delete_status',
        'created_at',
        'updated_at',
    ];
}
