<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Mark edit form -->
        <h1 class="text-3xl font-bold mb-6">Edit Mark</h1>

        <form action="{{ route('marks.update', $mark) }}" method="POST" class="bg-white border border-gray-200 rounded-lg p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Student</label>
                <select name="student_id" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                    <option value="">-- Select Student --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected(old('student_id', $mark->student_id) == $student->id)>
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
                        <option value="{{ $course->id }}" @selected(old('course_id', $mark->course_id) == $course->id)>
                            {{ $course->name }} ({{ $course->course_code }})
                        </option>
                    @endforeach
                </select>
                @error('course_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Marks (0-100)</label>
                <input type="number" min="0" max="100" name="marks" value="{{ old('marks', $mark->marks) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                @error('marks') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button class="bg-blue-600 text-black px-5 py-2 rounded-lg hover:bg-blue-700">Update Mark</button>
                <a href="{{ route('marks.index') }}" class="bg-gray-200 text-gray-800 px-5 py-2 rounded-lg hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
