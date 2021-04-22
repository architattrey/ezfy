<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'subcat_id',
        'institute_id',
        'plan',
        'price',
        'washes',
        'delete_status',
        'created_at',
        'updated_at',
    ];
    #get subcat
    public function getSubCat()
    {
         return $this->belongsTo('App\models\SubCategory','subcat_id');
    }
    #get institute
    public function getInstitute()
    {
        return $this->belongsTo('App\models\Institutes','institute_id');
    }
}
