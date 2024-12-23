<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'staff_id',
        'result',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }



}
