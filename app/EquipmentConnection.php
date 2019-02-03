<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class EquipmentConnection extends Model
{

    protected $table = 'equipment_connection';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'equipment_id', 'service_id', 'user_id','service', 'meta', 'attached_at', 'detached_at', 'deleted_at'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'attached_at', 'detached_at',
    ];

    protected $casts = ['meta' => 'array'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'item_id');
    }
}
