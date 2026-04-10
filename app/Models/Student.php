<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    // HasFactory is a Laravel trait that allows your model to use factories, a feature used to generate fake data for testing or seeding your database

    protected $fillable = ['name', 'email', 'phone', 'address', 'enrollment_year', 'user_id'];

    // relationships

    // A Student belongs to one User - in students table one user_id points to only one student
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // many to many - A Student can have many Courses, and a Course can have many Students.
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments');
    }

    // A Student has many Marks.
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }
}
