<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Equipment extends Model
{
    use SoftDeletes;

    protected $table = 'equipment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'serial', 'model', 'bar_code',
        'type_id', 'account_id', 'status_id', 'purchased_at', 'last_service_at',
        'next_service_at', 'insurance_valid_until', 'registration_renewal_at',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
        'purchased_at', 'last_service_at', 'next_service_at', 'insurance_valid_until', 'registration_renewal_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public $searchable = [
        'name', 'serial', 'bar_code', 'type_id',
        'status_id', 'account_id', 'purchased_at',
        'last_service_at', 'next_service_at', 'insurance_valid_until',
        'registration_renewal_at', 'created_at', 'updated_at'
    ];

    public function rules(){
        return [
            'account_id' => 'required|integer',
            'type_id' => 'integer|exists:equipment_types,id',
            'status_id' => 'integer|exists:equipment_statuses,id',
            'name' => 'required|string|max:255',
            'description' => 'string|max:2048',
            'serial' => 'string|max:255',
            'model' => 'string|max:255',
            'bar_code' => 'string|max:128',
            'purchased_at' => 'date',
            'last_service_at' => 'date',
            'next_service_at' => 'date',
            'insurance_valid_until' => 'date',
            'registration_renewal_at' => 'date',
        ];
    }

//    public function setRegistrationRenewalAtAttribute($date){
//        $this->attributes['registration_renewal_at'] = Carbon::parse($date);
//    }
//    public function setInsuranceValidUntilAttribute($date){
//        $this->attributes['insurance_valid_until'] = Carbon::parse($date);
//    }
//    public function setNextServiceAtAttribute($date){
//        $this->attributes['next_service_at'] = Carbon::parse($date);
//    }
//    public function setLastServiceAtAttribute($date){
//        $this->attributes['last_service_at'] = Carbon::parse($date);
//    }
//    public function setPurchasedAtAttribute($date){
//        $this->attributes['purchased_at'] = Carbon::parse($date);
//    }



    public function status()
    {
        return $this->belongsTo(EquipmentStatus::class, 'status_id');
    }

    public function type()
    {
        return $this->belongsTo(EquipmentType::class, 'type_id');
    }

    public function history()
    {
        return $this->hasMany(EquipmentHistory::class, 'equipment_id');
    }
}
