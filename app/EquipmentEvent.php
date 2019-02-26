<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class EquipmentEvent extends Model
{
    use SoftDeletes;

    protected $table = 'equipment_events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'action', 'params', 'deleted_at', 'conditions', 'microservice', 'attach_to', 'attach_from'
    ];

    protected $casts = [
        'params' => 'array',
        'attach_to' => 'array',
        'attach_from' => 'array',
        'conditions' => 'array'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public $searchable = [
       'id', 'name'
    ];


    public function createRules(){
        return [
            'name' => 'required|string|max:255',
        ];
    }
    public function updateRules(){
        return [
            'name' => 'string|max:255',
        ];
    }
    public function getTableName(){
        return $this->table;
    }
}
