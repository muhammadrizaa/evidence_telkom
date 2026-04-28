<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waspang extends Model
{
    protected $table = 'waspang';
    protected $fillable = ['nama_waspang', 'nik_waspang'];

    public function projects()
    {
        return $this->hasMany(Project::class, 'waspang_id');
    }

    public function evidence()
    {
        return $this->hasMany(Evidence::class);
    }
}