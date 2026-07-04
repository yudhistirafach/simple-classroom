<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassParticipant extends Pivot
{
    protected $table = 'class_participants';

    protected $fillable = [
        'class_id',
        'user_id',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];
}