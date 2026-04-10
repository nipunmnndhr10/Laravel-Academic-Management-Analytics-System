<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Marks listing -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Marks & Grades</h1>
            @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                <a href="{{ route('marks.create') }}" class="inline-flex items-center justify-center bg-blue-600 text-black px-6 py-3 rounded-lg shadow-sm hover:bg-blue-700 font-semibold text-sm">
                    + Add Mark
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 rounded-lg px-4 py-3 mb-4">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl shadow-sm">
            <table class="min-w-full">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600">Student</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600">Course</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600">Marks</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-600">Grade</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($marks as $mark)
                        <tr class="hover:bg-gray-50/70">
                            <td class="px-6 py-4">{{ $mark->student?->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $mark->course?->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $mark->marks }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $mark->grade }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('marks.show', $mark) }}" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-200">View</a>
                                @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                                    <a href="{{ route('marks.edit', $mark) }}" class="inline-flex items-center rounded-md bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-200">Edit</a>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <form action="{{ route('marks.destroy', $mark) }}" method="POST" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete this mark entry?')" class="inline-flex items-center rounded-md bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-200">Delete</button>
                                    </form>
                                @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 px-4 py-8">No marks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $marks->links() }}</div>
    </div>
</x-app-layout>
