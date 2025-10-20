<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">🔔 Notifikasi</h2>
                <p class="text-sm text-gray-600 mt-1">Tetap update dengan penugasan tugas Anda</p>
            </div>
            @if($notifications->total() > 0)
            <form action="{{ route('notifikasi.markAllAsRead') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="hidden sm:inline">Tandai Semua Dibaca</span>
                    <span class="sm:hidden">Tandai Semua</span>
                </button>
            </form>
            @endif
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if ($notifications->count() > 0)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        @foreach ($notifications as $notification)
                            @php
                                $data = $notification->data;
                                $isUnread = is_null($notification->read_at);
                                
                                // Tentukan URL redirect
                                $redirectUrl = '#';
                                if (isset($data['task_id']) && isset($data['workspace_id'])) {
                                    $redirectUrl = route('my-workspaces.task.show', [
                                        'workspace' => $data['workspace_id'],
                                        'task' => $data['task_id']
                                    ]);
                                }
                            @endphp
                            
                            <li class="{{ $isUnread ? 'bg-indigo-50' : 'bg-white' }} hover:bg-gray-50 transition-colors">
                                <a href="{{ $redirectUrl }}" 
                                   onclick="event.preventDefault(); markAsReadAndRedirect('{{ route('notifikasi.read', $notification) }}', '{{ $redirectUrl }}')"
                                   class="block px-4 sm:px-6 py-4">
                                    <div class="flex items-start gap-3 sm:gap-4">
                                        <!-- Status Indicator -->
                                        <div class="flex-shrink-0 mt-1">
                                            @if ($isUnread)
                                                <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-pulse"></div>
                                            @else
                                                <div class="w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                                            @endif
                                        </div>

                                        <!-- Icon -->
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full {{ $isUnread ? 'bg-indigo-100' : 'bg-gray-100' }} flex items-center justify-center">
                                                <svg class="w-5 h-5 sm:w-6 sm:h-6 {{ $isUnread ? 'text-indigo-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2 mb-1">
                                                <h3 class="text-sm sm:text-base font-semibold {{ $isUnread ? 'text-gray-900' : 'text-gray-700' }}">
                                                    {{ $data['title'] ?? 'Notifikasi' }}
                                                </h3>
                                                @if ($isUnread)
                                                <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    Baru
                                                </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                                                {{ $data['message'] ?? 'Anda memiliki notifikasi baru' }}
                                            </p>

                                            <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                                
                                                @if(isset($data['task_title']))
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                    </svg>
                                                    {{ Str::limit($data['task_title'], 30) }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Arrow Icon -->
                                        <div class="flex-shrink-0 hidden sm:block">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Pagination -->
                    @if($notifications->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <a href="{{ $notifications->previousPageUrl() ?? '#' }}" 
                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ $notifications->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    Sebelumnya
                                </a>
                                <a href="{{ $notifications->nextPageUrl() ?? '#' }}" 
                                   class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ !$notifications->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    Selanjutnya
                                </a>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Menampilkan
                                        <span class="font-medium">{{ $notifications->firstItem() }}</span>
                                        hingga
                                        <span class="font-medium">{{ $notifications->lastItem() }}</span>
                                        dari
                                        <span class="font-medium">{{ $notifications->total() }}</span>
                                        hasil
                                    </p>
                                </div>
                                <div>
                                    {{ $notifications->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white shadow-lg rounded-lg p-8 sm:p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Tidak Ada Notifikasi</h3>
                        <p class="text-sm text-gray-500">Anda sudah update! Periksa nanti untuk notifikasi baru.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Hidden form for marking as read -->
    <form id="markAsReadForm" method="POST" class="hidden">
        @csrf
        @method('PUT')
    </form>

    <script>
        function markAsReadAndRedirect(markAsReadUrl, redirectUrl) {
            if (redirectUrl === '#') {
                return;
            }

            const form = document.getElementById('markAsReadForm');
            form.action = markAsReadUrl;
            
            // Submit form via AJAX
            fetch(markAsReadUrl, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(() => {
                // Redirect to task page
                window.location.href = redirectUrl;
            })
            .catch(error => {
                console.error('Error:', error);
                // Redirect anyway
                window.location.href = redirectUrl;
            });
        }
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</x-app-layout>