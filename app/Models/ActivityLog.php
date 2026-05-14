<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'model_label',
        'old_values',
        'new_values',
        'ip_address',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Convenience: log an activity from anywhere.
     */
    public static function record(
        string $action,
        string $modelType,
        ?int $modelId = null,
        ?string $modelLabel = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'model_label' => $modelLabel,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'description' => $description,
        ]);
    }

    /* Helpers for badge colors in views */
    public function actionColor(): string
    {
        switch ($this->action) {
            case 'created':
                return 'green';
            case 'updated':
                return 'blue';
            case 'deleted':
                return 'red';
            case 'active':
                return 'green';
            case 'expiring':
                return 'amber';
            case 'expired':
                return 'red';
            case 'renewed':
                return 'amber';
            case 'imported':
                return 'violet';
            case 'login':
                return 'gray';
            case 'logout':
                return 'gray';
            default:
                return 'yellow';
        }
    }

    public function actionIcon(): string
    {
        switch ($this->action) {
            case 'created':
                return '+';
            case 'updated':
                return '✎';
            case 'deleted':
                return '✕';
            case 'renewed':
                return '↻';
            case 'imported':
                return '⇩';
            default:
                return '•';
        }
    }
}