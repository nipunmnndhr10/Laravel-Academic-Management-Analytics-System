<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index()
    {
        $user = auth()->user();
        $query = Student::with('user')->latest();

        // Students can view only their own profile.
        if ($user->isStudent()) {
            $query->where('user_id', $user->id);
        }

        // Teachers can view only students enrolled in their own courses.
        if ($user->isTeacher()) {
            $query->whereHas('courses', function ($builder) use ($user): void {
                $builder->where('courses.teacher_id', $user->id);
            });
        }

        $students = $query->paginate(10);

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $this->authorizeAdminOnly();

        return view('students.create');
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $this->authorizeAdminOnly();

        $validated = $request->validate([   // Validation rules for student creation
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'enrollment_year' => 'required|integer|min:2000|max:'.date('Y'),
        ]);

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully!');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $user = auth()->user();

        if ($user->isStudent() && $student->user_id !== $user->id) {
            abort(403, 'You can only view your own student profile.');
        }

        if ($user->isTeacher()) {
            $isEnrolledInTeacherCourse = $student->courses()
                ->where('teacher_id', $user->id)
                ->exists();

            if (! $isEnrolledInTeacherCourse) {
                abort(403, 'You can only view students enrolled in your courses.');
            }
        }

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the student.
     */
    public function edit(Student $student)
    {
        $this->authorizeAdminOnly();

        return view('students.edit', compact('student'));
    }

    /**
     * Update the student.
     */
    public function update(Request $request, Student $student)
    {
        $this->authorizeAdminOnly();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,'.$student->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'enrollment_year' => 'required|integer|min:2000|max:'.date('Y'),
        ]);

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the student.
     */
    public function destroy(Student $student)
    {
        $this->authorizeAdminOnly();

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }

    private function authorizeAdminOnly(): void
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admin can perform this action.');
        }
    }
}
