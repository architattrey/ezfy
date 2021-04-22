<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'category',
        'image',
        'delete_status',
        'created_at',
        'updated_at'
    ];
}
