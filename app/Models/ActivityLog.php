<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'model_type',
        'model_id',
    ];

    // Relationship with user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Static method to log activity
    public static function logActivity($userId, $action, $description = null, $modelType = null, $modelId = null)
    {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'model_type' => $modelType,
            'model_id' => $modelId,
        ]);
    }
}
