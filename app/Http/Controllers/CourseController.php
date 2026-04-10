<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $user = $request->user();

            if (! $user) {
                abort(403);
            }

            // Students can only view course data, not manage it.
            if ($user->isStudent() && in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy'], true)) {
                abort(403, 'You do not have permission to manage courses.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of courses.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            $student = $user->studentProfile;

            $courses = $student
                ? $student->courses()->with('teacher')->latest()->paginate(10)
                : Course::query()->whereRaw('1 = 0')->paginate(10);

            return view('courses.index', compact('courses'));
        }

        $query = Course::with('teacher')->latest();

        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        }

        $courses = $query->paginate(10);

        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $this->authorizeManage();

        $teachers = User::query()
            ->where('role', 'teacher')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('courses.create', compact('teachers'));
    }

    /**
     * Store a newly created course.
     */
    public function store(Request $request)
    {
        $this->authorizeManage();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'course_code' => ['required', 'string', 'max:50', 'unique:courses,course_code'],
            'credit_hours' => ['required', 'integer', 'min:1', 'max:10'],
            'teacher_id' => ['nullable', 'exists:users,id'],
        ]);

        // Teachers can only create courses assigned to themselves.
        if ($request->user()->isTeacher()) {
            $validated['teacher_id'] = $request->user()->id;
        }

        Course::create($validated);

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            $student = $user->studentProfile;

            if (! $student || ! $student->courses()->whereKey($course->id)->exists()) {
                abort(403, 'You can only view your own enrolled courses.');
            }
        }

        if ($user->isTeacher() && $course->teacher_id !== $user->id) {
            abort(403, 'You can only view courses assigned to you.');
        }

        $course->load(['teacher', 'students']);

        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $this->authorizeManage();
        $this->authorizeTeacherScope($course);

        $teachers = User::query()
            ->where('role', 'teacher')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('courses.edit', compact('course', 'teachers'));
    }

    /**
     * Update the specified course.
     */
    public function update(Request $request, Course $course)
    {
        $this->authorizeManage();
        $this->authorizeTeacherScope($course);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'course_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('courses', 'course_code')->ignore($course->id),
            ],
            'credit_hours' => ['required', 'integer', 'min:1', 'max:10'],
            'teacher_id' => ['nullable', 'exists:users,id'],
        ]);

        if ($request->user()->isTeacher()) {
            $validated['teacher_id'] = $request->user()->id;
        }

        $course->update($validated);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course)
    {
        $this->authorizeAdminOnly();

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }

    private function authorizeManage(): void
    {
        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->isTeacher()) {
            abort(403, 'Only admin or teacher can perform this action.');
        }
    }

    private function authorizeTeacherScope(Course $course): void
    {
        $user = auth()->user();

        if ($user->isTeacher() && $course->teacher_id !== $user->id) {
            abort(403, 'You can only manage your own courses.');
        }
    }

    private function authorizeAdminOnly(): void
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admin can perform this action.');
        }
    }
}
