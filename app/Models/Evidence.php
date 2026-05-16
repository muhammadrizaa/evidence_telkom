<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    protected $table = 'evidences';
    protected $guarded = [];

    protected $casts = [
        'file_path' => 'array',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Assignment::class,
            'id',        // Foreign key on assignments
            'id',        // Foreign key on users
            'assignment_id', // Local key on evidences
            'user_id'    // Local key on assignments
        );
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function waspang()
    {
        return $this->belongsTo(Waspang::class);
    }

    public function tematik()
    {
        return $this->belongsTo(Tematik::class);
    }

    public function report()
    {
        return $this->hasOne(Report::class);
    }
}