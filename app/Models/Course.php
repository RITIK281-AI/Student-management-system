<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_name',
        'description',
        'duration',
    ];

    // Relationship with students
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    // Get count of students in this course
    public function getStudentCountAttribute()
    {
        return $this->students()->count();
    }

    // Get average marks for this course
    public function getAverageMarksAttribute()
    {
        return $this->students()->avg('marks') ?? 0;
    }
}
