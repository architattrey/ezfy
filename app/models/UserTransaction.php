<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    protected $table = 'user_transactions';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'order_id',
        'user_id',
        'name',
        'product_id',
        'invoice_id',
        'institute_id',
        'manager_id',
        'amount',
        'no_of_clothes',
        'status',
        'user_type',
        'dlvry_placed',
        'dlvry_started',
        'dlvry_washed',
        'dlvry_delivered',
        'start_type',
        'remaining_washes',
        'transaction_type',
        'expire_date',
        'created_at',
        'updated_at',
    ];
    #get manager
    public function getManager(){
        return $this->belongsTo('App\models\AppManagers','manager_id');
    }
    #get user
    public function getUser(){
        return $this->belongsTo('App\models\Appusers','user_id');
    }
    #get institute
    public function getInstitute(){
        return $this->belongsTo('App\models\Institutes','institute_id');
    }
    #get products
    public function getProduct(){
        return $this->belongsTo('App\models\Products','product_id');
    }
}
