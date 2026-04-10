<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <!-- Mark details -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Mark Details</h1>

            <div class="space-y-3 text-sm">
                <p><span class="text-gray-500">Student:</span> <span class="font-medium">{{ $mark->student?->name ?? '-' }}</span></p>
                <p><span class="text-gray-500">Course:</span> <span class="font-medium">{{ $mark->course?->name ?? '-' }}</span></p>
                <p><span class="text-gray-500">Marks:</span> <span class="font-medium">{{ $mark->marks }}</span></p>
                <p><span class="text-gray-500">Grade:</span> <span class="font-medium">{{ $mark->grade }}</span></p>
                <p><span class="text-gray-500">Teacher:</span> <span class="font-medium">{{ $mark->course?->teacher?->name ?? '-' }}</span></p>
            </div>

            <div class="mt-6">
                <a href="{{ route('marks.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">Back</a>
            </div>
        </div>
    </div>
</x-app-layout>
