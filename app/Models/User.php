<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Tambah relasi project
    public function projects()
    {
        return $this->hasMany(Project::class, 'team_leader_id');
    }

    // HAPUS method getAuthIdentifierName() yang lama
    // Biarkan Laravel pakai 'id' secara default
}