<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Task extends Model
{
    protected $fillable = [
        'class_id',
        'title',
        'description',
        'status',
        'deadline_at',
    ];

    protected $casts = [
        'deadline_at' => 'datetime',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function updateStatus(): void
    {
        if ($this->deadline_at instanceof Carbon && $this->deadline_at->isPast() && $this->status !== 'Expired') {
            $this->status = 'Expired';
            $this->save();
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function getStatusAttribute($value): string
    {
        if ($this->deadline_at->isPast()) {
            return 'Expired';
        }
        return 'Active';
}
}