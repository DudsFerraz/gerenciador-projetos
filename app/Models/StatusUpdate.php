<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusUpdate extends Model
{
    use HasFactory, HasUuids, SoftDeletes, Auditable;

    protected function casts(): array
    {
        return [
            'begin_date' => 'date',
            'end_date' => 'date',
        ];
    }

    protected $fillable = [
        'project_id',
        'meeting_id',
        'update_doc',
        'begin_date',
        'end_date',
    ];


    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }
}
