<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'course_id', 'marks', 'grade'];

    protected static function booted(): void
    {
        static::saving(function (Mark $mark): void {
            // Keep grade assignment centralized so create/update always stays consistent.
            $mark->grade = self::calculateGrade((int) $mark->marks);
        });
    }

    public static function calculateGrade(int $marks): string
    {
        return match (true) {
            $marks >= 80 => 'A',
            $marks >= 70 => 'B',
            $marks >= 60 => 'C',
            $marks >= 50 => 'D',
            default => 'F',
        };
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
