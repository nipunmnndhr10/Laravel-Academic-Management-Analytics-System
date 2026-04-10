<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $user = $request->user();

            if (! $user) {
                abort(403);
            }

            // Students are allowed only to see their own enrollments.
            if ($user->isStudent() && in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy'], true)) {
                abort(403, 'You do not have permission to manage enrollments.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of enrollments.
     */
    public function index()
    {
        $user = auth()->user();

        $query = Enrollment::query()->with(['student', 'course.teacher'])->latest();

        if ($user->isStudent()) {
            $student = $user->studentProfile;
            $query->where('student_id', $student?->id ?? 0);
        }

        if ($user->isTeacher()) {
            $query->whereHas('course', function ($builder) use ($user): void {
                $builder->where('teacher_id', $user->id);
            });
        }

        $enrollments = $query->paginate(10);

        return view('enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new enrollment.
     */
    public function create()
    {
        $this->authorizeManage();

        $students = Student::query()->orderBy('name')->get(['id', 'name', 'email']);

        $courses = Course::query()
            ->when(auth()->user()->isTeacher(), function ($builder): void {
                $builder->where('teacher_id', auth()->id());
            })
            ->orderBy('name')
            ->get(['id', 'name', 'course_code']);

        return view('enrollments.create', compact('students', 'courses'));
    }

    /**
     * Store a newly created enrollment.
     */
    public function store(Request $request)
    {
        $this->authorizeManage();

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::unique('enrollments')->where(function ($builder) use ($request) {
                    return $builder
                        ->where('student_id', $request->input('student_id'))
                        ->where('course_id', $request->input('course_id'));
                }),
            ],
        ]);

        $course = Course::findOrFail($validated['course_id']);

        if ($request->user()->isTeacher() && $course->teacher_id !== $request->user()->id) {
            abort(403, 'You can only enroll students in your own courses.');
        }

        Enrollment::create($validated);

        return redirect()->route('enrollments.index')->with('success', 'Student enrolled successfully.');
    }

    /**
     * Display the specified enrollment.
     */
    public function show(Enrollment $enrollment)
    {
        $user = auth()->user();
        $enrollment->load(['student', 'course.teacher']);

        if ($user->isStudent() && $enrollment->student_id !== $user->studentProfile?->id) {
            abort(403);
        }

        if ($user->isTeacher() && $enrollment->course?->teacher_id !== $user->id) {
            abort(403);
        }

        return view('enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the specified enrollment.
     */
    public function edit(Enrollment $enrollment)
    {
        $this->authorizeManage();

        if (auth()->user()->isTeacher() && $enrollment->course?->teacher_id !== auth()->id()) {
            abort(403, 'You can only update enrollments for your own courses.');
        }

        $students = Student::query()->orderBy('name')->get(['id', 'name', 'email']);

        $courses = Course::query()
            ->when(auth()->user()->isTeacher(), function ($builder): void {
                $builder->where('teacher_id', auth()->id());
            })
            ->orderBy('name')
            ->get(['id', 'name', 'course_code']);

        return view('enrollments.edit', compact('enrollment', 'students', 'courses'));
    }

    /**
     * Update the specified enrollment.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $this->authorizeManage();

        if ($request->user()->isTeacher() && $enrollment->course?->teacher_id !== $request->user()->id) {
            abort(403, 'You can only update enrollments for your own courses.');
        }

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::unique('enrollments')->where(function ($builder) use ($request) {
                    return $builder
                        ->where('student_id', $request->input('student_id'))
                        ->where('course_id', $request->input('course_id'));
                })->ignore($enrollment->id),
            ],
        ]);

        $course = Course::findOrFail($validated['course_id']);

        if ($request->user()->isTeacher() && $course->teacher_id !== $request->user()->id) {
            abort(403, 'You can only move enrollment to your own courses.');
        }

        $enrollment->update($validated);

        return redirect()->route('enrollments.index')->with('success', 'Enrollment updated successfully.');
    }

    /**
     * Remove the specified enrollment.
     */
    public function destroy(Enrollment $enrollment)
    {
        $this->authorizeManage();

        if (auth()->user()->isTeacher() && $enrollment->course?->teacher_id !== auth()->id()) {
            abort(403, 'You can only delete enrollments for your own courses.');
        }

        $enrollment->delete();

        return redirect()->route('enrollments.index')->with('success', 'Enrollment removed successfully.');
    }

    private function authorizeManage(): void
    {
        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->isTeacher()) {
            abort(403, 'Only admin or teacher can perform this action.');
        }
    }
}
