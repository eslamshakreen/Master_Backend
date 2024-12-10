<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'discount_percentage',
        'expiry_date',
        'usage_limit',
    ];

    // العلاقات
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }


    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
