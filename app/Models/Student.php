<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'course_id',
        'marks',
        'grade',
    ];

    // Relationship with course
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Calculate and set grade based on marks
    public function calculateGrade(): string
    {
        if ($this->marks >= 80) {
            return 'A';
        } elseif ($this->marks >= 60) {
            return 'B';
        } elseif ($this->marks >= 40) {
            return 'C';
        } else {
            return 'Fail';
        }
    }

    // Mutator to automatically calculate grade when marks are set
    public function setMarksAttribute($value)
    {
        $this->attributes['marks'] = $value;
        $this->attributes['grade'] = $this->calculateGrade();
    }

    // Get status (Pass/Fail)
    public function getStatusAttribute(): string
    {
        return $this->marks >= 40 ? 'Pass' : 'Fail';
    }
}
