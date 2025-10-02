<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Analytics</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Task Analytics -->
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-semibold mb-4">Task Status</h3>
                <canvas id="taskChart"></canvas>
            </div>

            <!-- Project Analytics -->
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-semibold mb-4">Projects</h3>
                <canvas id="projectChart"></canvas>
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("{{ route('analytics.data') }}")
                .then(res => res.json())
                .then(data => {
                    // === Task Chart ===
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

                    // === Project Chart ===
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
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        });
    </script>
</x-app-layout>
