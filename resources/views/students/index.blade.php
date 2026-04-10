<x-app-layout>
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Students Management</h1>
    
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
        <a href="{{ route('students.create') }}" 
           class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
            + Add New Student
        </a>
    @endif
</div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Phone</th>
                        <th class="px-6 py-3 text-left">Enrollment Year</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $student->name }}</td>
                            <td class="px-6 py-4">{{ $student->email }}</td>
                            <td class="px-6 py-4">{{ $student->phone ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $student->enrollment_year }}</td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="{{ route('students.show', $student) }}"
                                   class="text-gray-700 hover:underline">View</a>

                                @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                                    <a href="{{ route('students.edit', $student) }}"
                                       class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure you want to delete this student?')"
                                                class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                @endif
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

            <div class="mt-4">
                {{ $students->links() }}
            </div>
        </div>
</x-app-layout>