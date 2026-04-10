<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Enrollment create form -->
        <h1 class="text-3xl font-bold mb-6">Create Enrollment</h1>

        <form action="{{ route('enrollments.store') }}" method="POST" class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 md:p-8 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Student</label>
                <select name="student_id" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                    <option value="">-- Select Student --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                            {{ $student->name }} ({{ $student->email }})
                        </option>
                    @endforeach
                </select>
                @error('student_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                <select name="course_id" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>
                            {{ $course->name }} ({{ $course->course_code }})
                        </option>
                    @endforeach
                </select>
                @error('course_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 pt-2">
                <button class="inline-flex items-center justify-center bg-blue-600 text-white px-6 py-3 rounded-lg shadow-sm hover:bg-blue-700 font-semibold text-sm">Save Enrollment</button>
                <a href="{{ route('enrollments.index') }}" class="inline-flex items-center justify-center bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold text-sm">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
