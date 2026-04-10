<x-app-layout>
    <x-slot name="slot">
        <div class="max-w-2xl mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-6">Edit Student</h1>

            <form method="POST" action="{{ route('students.update', $student) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Address</label>
                    <textarea name="address" rows="3" 
                              class="w-full border border-gray-300 rounded-lg px-4 py-3">{{ old('address', $student->address) }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Enrollment Year</label>
                    <input type="number" name="enrollment_year" value="{{ old('enrollment_year', $student->enrollment_year) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700">
                        Update Student
                    </button>
                    <a href="{{ route('students.index') }}" 
                       class="bg-gray-300 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-400">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </x-slot>
</x-app-layout>