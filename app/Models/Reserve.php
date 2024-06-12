<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use function PHPUnit\Framework\matches;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reserve extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'capacity',
        'used',
        'status'
    ];

    /**
     * @return HasMany
     */
    public function appointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * @return string
     */
    public function getStatusFaAttribute(): string
    {
        return match ($this->status) {
            "active" => "فعال",
            "inactive" => "غیر فعال",
            "expired" => "منقضی شده",
            default => "نامشخص",
        };
    }
}
