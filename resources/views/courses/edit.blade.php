<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Course edit form -->
        <h1 class="text-3xl font-bold mb-6">Edit Course</h1>

        <form action="{{ route('courses.update', $course) }}" method="POST" class="bg-white border border-gray-200 rounded-lg p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course Name</label>
                <input type="text" name="name" value="{{ old('name', $course->name) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course Code</label>
                <input type="text" name="course_code" value="{{ old('course_code', $course->course_code) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                @error('course_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Credit Hours</label>
                <input type="number" min="1" max="10" name="credit_hours" value="{{ old('credit_hours', $course->credit_hours) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                @error('credit_hours') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            @if(auth()->user()->isAdmin())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Teacher</label>
                    <select name="teacher_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">-- Select Teacher --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('teacher_id', $course->teacher_id) == $teacher->id)>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    @error('teacher_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">Update Course</button>
                <a href="{{ route('courses.index') }}" class="bg-gray-200 text-gray-800 px-5 py-2 rounded-lg hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
