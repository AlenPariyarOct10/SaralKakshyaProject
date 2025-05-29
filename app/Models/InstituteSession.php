<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstituteSession extends Model
{
    protected $fillable = [
        'institute_id',
        'date',
        'start_time',
        'end_time',
        'notes',
        'status',
        'creator_type',
        'creator_id',
        'specific_group',
        'specific_group_id',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    /**
     * Scope for holiday sessions
     */
    public function scopeHolidays($query)
    {
        return $query->where('status', 'holiday');
    }

    /**
     * Scope for a specific date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Check if this session is a holiday
     */
    public function isHoliday()
    {
        return $this->status === 'holiday';
    }
}
