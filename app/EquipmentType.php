<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class EquipmentType extends Model
{
    use SoftDeletes;

    protected $table = 'equipment_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'account_id',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public $searchable = [
        'name'
    ];

    public function createRules(){
        return [
            'account_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'description' => 'string|max:2048',
        ];
    }

    public function updateRules(){
        return [
            'account_id' => 'integer',
            'name' => 'string|max:255',
            'description' => 'string|max:2048',
        ];
    }

    public function getTableName(){
        return $this->table;
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'type_id');
    }
}
