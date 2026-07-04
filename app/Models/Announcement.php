<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Announcement extends Model
{
    protected $fillable = [
        'class_id',
        'title',
        'description',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function isActive(): bool
    {
        if (is_null($this->expired_at)) {
            return true;
        }
        $expired = $this->expired_at instanceof Carbon ? $this->expired_at : Carbon::parse($this->expired_at);
        return $expired->isFuture();
    }
}