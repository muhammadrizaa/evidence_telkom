<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'project';
    protected $guarded = [];

    // Team Leader yang membuat project
    public function teamLeader()
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }

    // Admin yang mengawal on-desk
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Waspang yang mengawasi lapangan
    public function waspang()
    {
        return $this->belongsTo(Waspang::class, 'waspang_id');
    }

    // Karyawan yang mengerjakan (via assignments)
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}