<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PaymentStatusEnum;

class Payment extends Model
{
    protected $cast = ['status' => PaymentStatusEnum::class, 'paid_at' => 'datetime'];
    protected $fillable = ['nopendaftaran', 'status', 'user_id', 'nominal', 'paid_at', 'kodekelas', 'kodejalur', 'kodeta', 'waktuakhir'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
