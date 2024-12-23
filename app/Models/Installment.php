<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = ['payment_id', 'amount', 'paid_at'];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    protected static function booted()
    {
        static::created(function ($installment) {
            $payment = $installment->payment;
            $payment->paid_amount += $installment->amount;
            $payment->save();
        });
    }

}
