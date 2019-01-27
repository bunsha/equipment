<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class EquipmentMutation extends Model
{
    use SoftDeletes;

    protected $table = 'equipment_mutations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'display_name', 'data_type', 'values', 'is_nullable', 'is_replace', 'is_hidden', 'account_id', 'deleted_at'
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

    protected $casts = [
        'values' => 'array'
    ];

    public $searchable = [
        'name'
    ];

    public function createRules(){
        return [
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'data_type' => 'required|string|max:128',
            'values' => 'json',
            'is_nullable' => 'boolean',
            'is_replace' => 'boolean',
            'is_hidden' => 'boolean',
            'account_id' => 'required|integer',
        ];
    }
    public function updateRules(){
        return [
            'name' => 'string|max:255',
            'display_name' => 'string|max:255',
            'data_type' => 'string|max:128',
            'values' => 'json',
            'is_nullable' => 'boolean',
            'is_replace' => 'boolean',
            'is_hidden' => 'boolean',
            'account_id' => 'integer',
        ];
    }
    public function getTableName(){
        return $this->table;
    }
}
