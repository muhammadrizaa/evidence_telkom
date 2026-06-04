<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'project';
    protected $guarded = [];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function tematik()
    {
        return $this->belongsTo(Tematik::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function waspang()
    {
        return $this->belongsTo(Waspang::class);
    }

    public function teamLeader()
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }
}