<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'method_name',
    ];

    // العلاقات
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
