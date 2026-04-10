<x-app-layout>
    <x-slot name="slot">
        <div class="max-w-2xl mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-6">Add New Student</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('students.store') }}" class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 md:p-8">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Full Name</label>
                    <input type="text" name="name" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
                           value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" name="email" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
                           value="{{ old('email') }}" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                    <input type="text" name="phone" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
                           value="{{ old('phone') }}">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Address</label>
                    <textarea name="address" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500">{{ old('address') }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Enrollment Year</label>
                    <input type="number" name="enrollment_year" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
                           value="{{ old('enrollment_year', date('Y')) }}" required>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3 pt-2">
                    <button type="submit" 
                            class="inline-flex items-center justify-center bg-blue-600 text-black px-8 py-3 rounded-lg shadow-sm hover:bg-blue-700 font-semibold text-sm">
                        Save Student
                    </button>
                    <a href="{{ route('students.index') }}" 
                       class="inline-flex items-center justify-center bg-gray-200 text-gray-800 px-8 py-3 rounded-lg hover:bg-gray-300 font-semibold text-sm">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </x-slot>
</x-app-layout>