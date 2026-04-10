<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Mark;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MarkController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $user = $request->user();

            if (! $user) {
                abort(403);
            }

            // Students can read their marks but cannot create/update/delete.
            if ($user->isStudent() && in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy'], true)) {
                abort(403, 'You do not have permission to manage marks.');
            }

            return $next($request);
        });
    }

    /**
     * Display marks.
     */
    public function index()
    {
        $user = auth()->user();
        $query = Mark::query()->with(['student', 'course.teacher'])->latest();

        if ($user->isStudent()) {
            $query->where('student_id', $user->studentProfile?->id ?? 0);
        }

        if ($user->isTeacher()) {
            $query->whereHas('course', function ($builder) use ($user): void {
                $builder->where('teacher_id', $user->id);
            });
        }

        $marks = $query->paginate(10);

        return view('marks.index', compact('marks'));
    }

    /**
     * Show mark entry form.
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

        return view('marks.create', compact('students', 'courses'));
    }

    /**
     * Save a new mark.
     */
    public function store(Request $request)
    {
        $this->authorizeManage();

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::unique('marks')->where(function ($builder) use ($request) {
                    return $builder
                        ->where('student_id', $request->input('student_id'))
                        ->where('course_id', $request->input('course_id'));
                }),
            ],
            'marks' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $course = Course::findOrFail($validated['course_id']);

        if ($request->user()->isTeacher() && $course->teacher_id !== $request->user()->id) {
            abort(403, 'You can only add marks for your own courses.');
        }

        // Ensure marks are recorded only for enrolled student-course pairs.
        $isEnrolled = Enrollment::query()
            ->where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->exists();

        if (! $isEnrolled) {
            return back()->withErrors(['student_id' => 'Student is not enrolled in the selected course.'])->withInput();
        }

        Mark::create($validated);

        return redirect()->route('marks.index')->with('success', 'Mark saved successfully.');
    }

    /**
     * Display a single mark.
     */
    public function show(Mark $mark)
    {
        $user = auth()->user();
        $mark->load(['student', 'course.teacher']);

        if ($user->isStudent() && $mark->student_id !== $user->studentProfile?->id) {
            abort(403);
        }

        if ($user->isTeacher() && $mark->course?->teacher_id !== $user->id) {
            abort(403);
        }

        return view('marks.show', compact('mark'));
    }

    /**
     * Show mark edit form.
     */
    public function edit(Mark $mark)
    {
        $this->authorizeManage();

        if (auth()->user()->isTeacher() && $mark->course?->teacher_id !== auth()->id()) {
            abort(403, 'You can only edit marks for your own courses.');
        }

        $students = Student::query()->orderBy('name')->get(['id', 'name', 'email']);

        $courses = Course::query()
            ->when(auth()->user()->isTeacher(), function ($builder): void {
                $builder->where('teacher_id', auth()->id());
            })
            ->orderBy('name')
            ->get(['id', 'name', 'course_code']);

        return view('marks.edit', compact('mark', 'students', 'courses'));
    }

    /**
     * Update an existing mark.
     */
    public function update(Request $request, Mark $mark)
    {
        $this->authorizeManage();

        if ($request->user()->isTeacher() && $mark->course?->teacher_id !== $request->user()->id) {
            abort(403, 'You can only update marks for your own courses.');
        }

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::unique('marks')->where(function ($builder) use ($request) {
                    return $builder
                        ->where('student_id', $request->input('student_id'))
                        ->where('course_id', $request->input('course_id'));
                })->ignore($mark->id),
            ],
            'marks' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $course = Course::findOrFail($validated['course_id']);

        if ($request->user()->isTeacher() && $course->teacher_id !== $request->user()->id) {
            abort(403, 'You can only move marks to your own courses.');
        }

        $isEnrolled = Enrollment::query()
            ->where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->exists();

        if (! $isEnrolled) {
            return back()->withErrors(['student_id' => 'Student is not enrolled in the selected course.'])->withInput();
        }

        $mark->update($validated);

        return redirect()->route('marks.index')->with('success', 'Mark updated successfully.');
    }

    /**
     * Delete mark.
     */
    public function destroy(Mark $mark)
    {
        $this->authorizeAdminOnly();

        $mark->delete();

        return redirect()->route('marks.index')->with('success', 'Mark deleted successfully.');
    }

    private function authorizeManage(): void
    {
        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->isTeacher()) {
            abort(403, 'Only admin or teacher can perform this action.');
        }
    }

    private function authorizeAdminOnly(): void
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admin can perform this action.');
        }
    }
}
