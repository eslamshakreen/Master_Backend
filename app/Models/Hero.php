<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    use HasFactory;

    const TYPE_HERO = 'hero';
    const TYPE_NEWS = 'news';
    const TYPE_REVIEW = 'review';

    const TYPE_ADVERTISEMENT = 'advertisement';
    const TYPE_EVENT = 'event';



    protected $fillable = [
        'name',
        'title',
        'description',
        'image',
        'order',
        'type',
    ];
}