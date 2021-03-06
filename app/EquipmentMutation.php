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
        'name', 'display_name', 'data_type', 'values', 'external', 'uses_external_value', 'dependencies', 'account_id',
        'deleted_at', 'is_required', 'is_hidden', 'is_function','is_replaceable', 'is_replacing'
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
        'value' => 'array',
        'external' => 'array',
        'dependencies' => 'array',
    ];

    public $searchable = [
        'name'
    ];

    public function createRules(){
        return [
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'data_type' => 'required|string|max:128',
            'value' => 'json',
            'external' => 'json',
            'dependencies' => 'json',
            'is_required' => 'boolean',
            'uses_external_value' => 'boolean',
            'is_replaceable' => 'boolean',
            'is_replacing' => 'boolean',
            'is_hidden' => 'boolean',
            'is_function' => 'boolean',
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
