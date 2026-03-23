<?php

namespace App\Models;

use App\Enums\MeetingStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use HasFactory, HasUuids, SoftDeletes, Auditable;

    protected function casts(): array
    {
        return [
            'status' => MeetingStatus::class,
            'meeting_date' => 'datetime',
        ];
    }

    protected $fillable = [
        'title',
        'status',
        'scope',
        'meeting_date',
    ];

    public function statusUpdates(): HasMany
    {
        return $this->hasMany(StatusUpdate::class);
    }
}
