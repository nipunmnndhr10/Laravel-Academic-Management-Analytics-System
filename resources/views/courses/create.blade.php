<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Course create form -->
        <h1 class="text-3xl font-bold mb-6">Create Course</h1>

        <form action="{{ route('courses.store') }}" method="POST" class="bg-white border border-gray-200 rounded-lg p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course Code</label>
                <input type="text" name="course_code" value="{{ old('course_code') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                @error('course_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Credit Hours</label>
                <input type="number" min="1" max="10" name="credit_hours" value="{{ old('credit_hours') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                @error('credit_hours') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            @if(auth()->user()->isAdmin())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Teacher</label>
                    <select name="teacher_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">-- Select Teacher --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    @error('teacher_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 pt-2">
                <button class="inline-flex items-center justify-center bg-blue-600 text-black px-6 py-3 rounded-lg shadow-sm hover:bg-blue-700 font-semibold text-sm">Save Course</button>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold text-sm">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
