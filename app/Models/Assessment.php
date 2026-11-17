<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'age',
        'gender',
        'department',
        'work_tenure',
        'screen_time',
        'nbm_responses',
        'rosa_responses',
        'nbm_total_score',
        'rosa_total_score',
        'total_score',
        'risk_level',
        'risk_label',
        'risk_description',
        'smart_recommendations',
        'pdf_path',
        'email_status',
        'email_sent_at',
    ];

    protected $casts = [
        'nbm_responses' => 'array',
        'rosa_responses' => 'array',
        'smart_recommendations' => 'array',
        'email_sent_at' => 'datetime',
        'age' => 'integer',
        'nbm_total_score' => 'integer',
        'rosa_total_score' => 'integer',
        'total_score' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($assessment) {
            if (empty($assessment->uuid)) {
                $assessment->uuid = (string) Str::uuid();
            }
        });
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d M Y, H:i') : '-';
    }

    public function getRiskBadgeClassAttribute(): string
    {
        return match ($this->risk_level) {
            'low' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500',
            'moderate' => 'bg-yellow-500/20 text-yellow-300 border-yellow-400',
            'high' => 'bg-orange-500/20 text-orange-400 border-orange-500',
            'very_high' => 'bg-red-500/20 text-red-400 border-red-500',
            default => 'bg-slate-500/20 text-slate-300 border-slate-500',
        };
    }
}
