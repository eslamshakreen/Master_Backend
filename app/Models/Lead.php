<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'converted_at',
        'address',
        'company',
        'position',
        'labels',
        'email_subscriber_status',
        'sms_subscriber_status',
        'last_activity'
    ];


}
