<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class AppManagers extends Model
{
    protected $table = 'app_managers';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'institute_id',
        'email',
        'password',
        'delete_status',
        'created_at',
        'updated_at',
    ];
    #get institutes
    public function getInstitute()
    {
        return $this->belongsTo('App\models\Institutes','institute_id');
    }
}
