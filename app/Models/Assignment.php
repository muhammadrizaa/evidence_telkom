<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $guarded = [];

    // Karyawan yang mengerjakan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function mapping()
    {
        return $this->belongsTo(Mapping::class);
    }

    public function evidences()
    {
        return $this->hasMany(Evidence::class);
    }
}