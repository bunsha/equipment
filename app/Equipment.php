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
        'name', 'type_id', 'bar_code', 'account_id', 'status_id', 'meta'
    ];

    protected $casts = ['meta' => 'array'];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'meta'
    ];

    public $searchable = [
       'id', 'name',  'bar_code', 'type_id', 'status_id', 'account_id',  'created_at', 'updated_at'
    ];


    public function createRules(){
        return [
            'account_id' => 'required|integer',
            'type_id' => 'integer|exists:equipment_types,id',
            'status_id' => 'integer|exists:equipment_statuses,id',
            'name' => 'required|string|max:255',
            'bar_code' => 'string|max:128',
        ];
    }
    public function updateRules(){
        return [
            'account_id' => 'integer',
            'type_id' => 'integer|exists:equipment_types,id',
            'status_id' => 'integer|exists:equipment_statuses,id',
            'name' => 'string|max:255',
            'bar_code' => 'string|max:128',
        ];
    }
    public function getTableName(){
        return $this->table;
    }



    public function status()
    {
        return $this->belongsTo(EquipmentStatus::class, 'status_id');
    }

    public function type()
    {
        return $this->belongsTo(EquipmentType::class, 'type_id');
    }

    public function connections()
    {
        return $this->hasMany(EquipmentConnection::class, 'item_id');
    }
}
