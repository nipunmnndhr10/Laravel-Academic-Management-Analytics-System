<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Course listing page -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">Course Management</h1>
            @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                <a href="{{ route('courses.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                    + Add Course
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 rounded-lg px-4 py-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg">
            <table class="min-w-full">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-3">Course Name</th>
                        <th class="px-4 py-3">Course Code</th>
                        <th class="px-4 py-3">Credit Hours</th>
                        <th class="px-4 py-3">Teacher</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $course->name }}</td>
                            <td class="px-4 py-3">{{ $course->course_code }}</td>
                            <td class="px-4 py-3">{{ $course->credit_hours }}</td>
                            <td class="px-4 py-3">{{ $course->teacher?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <a href="{{ route('courses.show', $course) }}" class="text-gray-700 hover:underline">View</a>
                                @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                                    <a href="{{ route('courses.edit', $course) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('courses.destroy', $course) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete this course?')" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 px-4 py-8">No courses found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $courses->links() }}</div>
    </div>
</x-app-layout>
