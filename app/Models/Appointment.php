<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'reserve_id',
        'position',
        'status',
        'detail'
    ];


    /**
     * @return BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function reserve(): BelongsTo
    {
        return $this->belongsTo(Reserve::class);
    }

    public function getStatusFaAttribute()
    {
        return match ($this->status) {
            'active' => "فعال",
            'inactive' => "غیر فعال",
            'pending' => "در انتظار بررسی",
            'done' => "تکمیل شده",
            'rejected' => "رد شده",
            'canceled' => "کنسل شده",
            'checked' => "بررسی شده",
        };
    }

    protected $casts = [
        'detail' => 'array'
    ];
}
