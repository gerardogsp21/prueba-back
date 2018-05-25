<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use SoftDeletes;

    protected $table = "usuarios";

    protected $fillable = ['id', 'nombres', 'apellidos', 'email', 'password'];
    protected $hidden = ['password'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
