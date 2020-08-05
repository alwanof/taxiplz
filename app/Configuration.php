<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Configuration extends Model
{
    protected $fillable = ['name', 'value'];
    protected $appends = ['getroles'];
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_configuration');
    }
    public function getGetrolesAttribute()
    {
        return $this->roles;
    }
}