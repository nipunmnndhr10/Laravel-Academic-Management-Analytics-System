<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Mark;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Base queries are adjusted by role so each user sees only allowed data.
        $courseQuery = Course::query();
        $markQuery = Mark::query();
        $studentQuery = Student::query();

        if ($user->isTeacher()) {
            $courseIds = Course::query()->where('teacher_id', $user->id)->pluck('id');
            $courseQuery->whereIn('id', $courseIds);
            $markQuery->whereIn('course_id', $courseIds);
            $studentQuery->whereHas('courses', function ($builder) use ($courseIds): void {
                $builder->whereIn('courses.id', $courseIds);
            });
        }

        if ($user->isStudent()) {
            $studentId = $user->studentProfile?->id;
            $courseIds = $studentId
                ? DB::table('enrollments')->where('student_id', $studentId)->pluck('course_id')
                : collect();

            $courseQuery->whereIn('id', $courseIds);
            $markQuery->where('student_id', $studentId ?? 0);
            $studentQuery->whereKey($studentId ?? 0);
        }

        $totalStudents = $studentQuery->distinct('students.id')->count('students.id');
        $totalCourses = $courseQuery->distinct('courses.id')->count('courses.id');

        $averageMarksByCourse = (clone $markQuery)
            ->join('courses', 'marks.course_id', '=', 'courses.id')
            ->select('courses.name', DB::raw('ROUND(AVG(marks.marks), 2) as average_marks'))
            ->groupBy('courses.id', 'courses.name')
            ->orderByDesc('average_marks')
            ->get();

        $passFail = (clone $markQuery)
            ->selectRaw('SUM(CASE WHEN marks >= 50 THEN 1 ELSE 0 END) as pass_count')
            ->selectRaw('SUM(CASE WHEN marks < 50 THEN 1 ELSE 0 END) as fail_count')
            ->first();

        $topStudents = (clone $markQuery)
            ->join('students', 'marks.student_id', '=', 'students.id')
            ->select('students.name', DB::raw('ROUND(AVG(marks.marks), 2) as avg_marks'))
            ->groupBy('students.id', 'students.name')
            ->orderByDesc('avg_marks')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'totalCourses' => $totalCourses,
            'averageMarksByCourse' => $averageMarksByCourse,
            'passFail' => $passFail,
            'topStudents' => $topStudents,
        ]);
    }
}
