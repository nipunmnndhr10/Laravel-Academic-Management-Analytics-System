<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Students Management</h1>

            @if(auth()->user()->isAdmin())
                <a href="{{ route('students.create') }}"
                    class="inline-flex items-center justify-center bg-blue-600 text-white px-6 py-3 rounded-lg shadow-sm hover:bg-blue-700 font-semibold text-sm">
                    + Add New Student
                </a>
            @endif
        </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl shadow-sm">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Enrollment Year</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($students as $student)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-6 py-4">{{ $student->name }}</td>
                                <td class="px-6 py-4">{{ $student->email }}</td>
                                <td class="px-6 py-4">{{ $student->phone ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $student->enrollment_year }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('students.show', $student) }}"
                                            class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-200">View</a>

                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('students.edit', $student) }}"
                                                class="inline-flex items-center rounded-md bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-200">Edit</a>
                                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline-flex">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Are you sure you want to delete this student?')"
                                                    class="inline-flex items-center rounded-md bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-200">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No students found yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $students->links() }}
            </div>
    </div>
</x-app-layout>