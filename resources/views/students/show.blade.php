<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h1 class="text-2xl font-bold mb-4">Student Details</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Name</p>
                    <p class="font-medium text-gray-900">{{ $student->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Email</p>
                    <p class="font-medium text-gray-900">{{ $student->email }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Phone</p>
                    <p class="font-medium text-gray-900">{{ $student->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Enrollment Year</p>
                    <p class="font-medium text-gray-900">{{ $student->enrollment_year }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500">Address</p>
                    <p class="font-medium text-gray-900">{{ $student->address ?? '-' }}</p>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('students.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
                    Back
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
