<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <!-- Enrollment details -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Enrollment Details</h1>

            <div class="space-y-3 text-sm">
                <p><span class="text-gray-500">Student:</span> <span class="font-medium">{{ $enrollment->student?->name ?? '-' }}</span></p>
                <p><span class="text-gray-500">Student Email:</span> <span class="font-medium">{{ $enrollment->student?->email ?? '-' }}</span></p>
                <p><span class="text-gray-500">Course:</span> <span class="font-medium">{{ $enrollment->course?->name ?? '-' }}</span></p>
                <p><span class="text-gray-500">Course Code:</span> <span class="font-medium">{{ $enrollment->course?->course_code ?? '-' }}</span></p>
                <p><span class="text-gray-500">Teacher:</span> <span class="font-medium">{{ $enrollment->course?->teacher?->name ?? '-' }}</span></p>
            </div>

            <div class="mt-6">
                <a href="{{ route('enrollments.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">Back</a>
            </div>
        </div>
    </div>
</x-app-layout>
