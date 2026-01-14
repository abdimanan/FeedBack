<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    /** @use HasFactory<\Database\Factories\FeedbackFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($feedback) {
            if (empty($feedback->created_at)) {
                $feedback->created_at = now();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'statement_1_rating',
        'statement_2_rating',
        'statement_3_rating',
        'likes_text',
        'dislikes_text',
        'overall_rating',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'statement_1_rating' => 'integer',
            'statement_2_rating' => 'integer',
            'statement_3_rating' => 'integer',
            'overall_rating' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the project that owns the feedback.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
