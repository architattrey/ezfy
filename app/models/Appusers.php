<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Appusers extends Model
{
    protected $table = 'appusers';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [

        'name',
        'phone_number',
        'mail_id',
        'address',
        'gender',
        'image',
        'institute_id',
        'user_type',
        'delete_status',
        'firebase_token',
        'cncted_with_google'
    ];
    #get institutes
    public function getInstitute(){
        return $this->belongsTo('App\models\Institutes','institute_id');
    }
}
