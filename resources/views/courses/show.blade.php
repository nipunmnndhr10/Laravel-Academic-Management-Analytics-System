<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Course detail page with enrolled students list -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">{{ $course->name }}</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mb-6">
                <div>
                    <p class="text-gray-500">Course Code</p>
                    <p class="font-medium text-gray-900">{{ $course->course_code }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Credit Hours</p>
                    <p class="font-medium text-gray-900">{{ $course->credit_hours }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Teacher</p>
                    <p class="font-medium text-gray-900">{{ $course->teacher?->name ?? '-' }}</p>
                </div>
            </div>

            <h2 class="text-lg font-semibold mb-2">Enrolled Students</h2>
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($course->students as $student)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $student->name }}</td>
                                <td class="px-4 py-2">{{ $student->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-6 text-center text-gray-500">No students enrolled yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                <a href="{{ route('courses.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">Back</a>
            </div>
        </div>
    </div>
</x-app-layout>
