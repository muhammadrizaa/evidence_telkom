<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Mapping extends Model
{
    protected $table = 'mapping';
    protected $fillable = ['nama_area', 'kode_mapping'];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}