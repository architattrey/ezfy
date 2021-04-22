<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'cat_id',
        'sub_cat_name',
        'delete_status',
        'created_at',
        'updated_at',
    ];
    #get cat
    public function getCat()
    {
        return $this->belongsTo('App\models\Categories','cat_id');
    }
    #get products
    public function getProduct()
    {
        return $this->hasMany('App\models\Products','subcat_id','id');
    }
}
