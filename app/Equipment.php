<?php

namespace App;

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
        'type_id', 'account_id', 'status_id',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'purchased_at', 'last_service_at',
         'next_service_at', 'insurance_valid_until', 'registration_renewal_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

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
