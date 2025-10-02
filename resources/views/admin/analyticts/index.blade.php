@extends('admin.layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Global Analytics (Admin)</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Task Analytics -->
        <div class="bg-white shadow rounded p-6">
            <h3 class="text-lg font-semibold mb-4">All Tasks Status</h3>
            <canvas id="taskChart"></canvas>
        </div>

        <!-- Project Analytics -->
        <div class="bg-white shadow rounded p-6">
            <h3 class="text-lg font-semibold mb-4">All Projects</h3>
            <canvas id="projectChart"></canvas>
        </div>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("{{ route('analytict.data') }}")
                .then(res => res.json())
                .then(data => {
                    // Task Chart
                    const taskCtx = document.getElementById('taskChart').getContext('2d');
                    new Chart(taskCtx, {
                        type: 'doughnut',
                        data: {
                            labels: Object.keys(data.tasks),
                            datasets: [{
                                data: Object.values(data.tasks),
                                backgroundColor: ['#ef4444', '#f59e0b', '#10b981'], // merah, kuning, hijau
                            }]
                        }
                    });

                    // Project Chart
                    const projectCtx = document.getElementById('projectChart').getContext('2d');
                    new Chart(projectCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Active', 'Finished'],
                            datasets: [{
                                label: 'Projects',
                                data: [data.projects.active, data.projects.finished],
                                backgroundColor: ['#3b82f6', '#6366f1'], // biru, ungu
                            }]
                        },
                        options: {
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                });
        });
    </script>
@endsection
