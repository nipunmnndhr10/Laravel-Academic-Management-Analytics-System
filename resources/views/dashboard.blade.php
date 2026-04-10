<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Academic Analytics Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Simple KPI cards for quick academic overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Students</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $totalStudents }}</h3>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Courses</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $totalCourses }}</h3>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Average Marks Per Course (Bar Chart)</h3>
                <canvas id="averageMarksChart" height="110"></canvas>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Pass vs Fail Ratio (Pie Chart)</h3>
<div class="h-48 w-48 mx-auto">
    <canvas id="passFailChart"></canvas>
</div>                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Top Performing Students (Line Chart)</h3>
                    <canvas id="topStudentsChart" height="160"></canvas>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-800">
                Logged in as <strong>{{ auth()->user()->role }}</strong>.
                @if(auth()->user()->isStudent())
                    You are seeing your personal academic insights.
                @elseif(auth()->user()->isTeacher())
                    You are seeing data for your assigned courses.
                @else
                    You are seeing complete system analytics.
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data arrays sent from controller and converted to plain JS arrays.
        const averageCourseLabels = @json($averageMarksByCourse->pluck('name')->values());
        const averageCourseValues = @json($averageMarksByCourse->pluck('average_marks')->values());

        const passCount = {{ (int) ($passFail->pass_count ?? 0) }};
        const failCount = {{ (int) ($passFail->fail_count ?? 0) }};

        const topStudentLabels = @json($topStudents->pluck('name')->values());
        const topStudentValues = @json($topStudents->pluck('avg_marks')->values());

        new Chart(document.getElementById('averageMarksChart'), {
            type: 'bar',
            data: {
                labels: averageCourseLabels,
                datasets: [{
                    label: 'Average Marks',
                    data: averageCourseValues,
                    backgroundColor: '#2563eb',
                }],
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, max: 100 },
                },
            },
        });

        new Chart(document.getElementById('passFailChart'), {
            type: 'pie',
            data: {
                labels: ['Pass', 'Fail'],
                datasets: [{
                    data: [passCount, failCount],
                    backgroundColor: ['#16a34a', '#dc2626'],
                }],
            },
            options: { responsive: true},
        });

        new Chart(document.getElementById('topStudentsChart'), {
            type: 'line',
            data: {
                labels: topStudentLabels,
                datasets: [{
                    label: 'Average Marks',
                    data: topStudentValues,
                    borderColor: '#ea580c',
                    backgroundColor: 'rgba(234, 88, 12, 0.15)',
                    tension: 0.35,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, max: 100 },
                },
            },
        });
    </script>
</x-app-layout>
