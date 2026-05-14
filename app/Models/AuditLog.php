<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'changes',
        'ip_address',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getModelAttribute(): ?Model
    {
        if (!$this->model_type || !$this->model_id) {
            return null;
        }

        return $this->model_type::find($this->model_id);
    }

    public function getDescriptionAttribute(): string
    {
        $modelName = class_basename($this->model_type);
        $actionLabel = match ($this->action) {
            'create' => 'membuat',
            'update' => 'memperbarui',
            'delete' => 'menghapus',
            default => $this->action,
        };

        return ($this->user?->name ?? 'System') . " {$actionLabel} {$modelName}";
    }
}