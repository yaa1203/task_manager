<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Notifikasi</h2>
                <p class="text-sm text-gray-600 mt-1">Tetap update dengan penugasan tugas Anda</p>
            </div>
            @if(Auth::user()->unreadNotifications()->count() > 0)
                <form action="{{ route('notifikasi.markAllAsRead') }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" 
                            class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="hidden sm:inline">Tandai Semua Dibaca</span>
                        <span class="sm:hidden">Baca Semua</span>
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Statistik Kartu --}}
            <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-5">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-3 sm:p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <span class="text-xs text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">Total</span>
                    </div>
                    <p class="text-xs text-gray-600">Semua</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ Auth::user()->notifications()->count() }}</p>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-3 sm:p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full">Belum</span>
                    </div>
                    <p class="text-xs text-gray-600">Perhatian</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ Auth::user()->unreadNotifications()->count() }}</p>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-3 sm:p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs text-green-700 bg-green-50 px-2 py-0.5 rounded-full">Dibaca</span>
                    </div>
                    <p class="text-xs text-gray-600">Selesai</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ Auth::user()->readNotifications()->count() }}</p>
                </div>
            </div>

            {{-- Filter Tabs (Mobile Scrollable) --}}
            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide mb-4">
                <a href="{{ route('notifikasi.index', ['filter' => 'all']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ $filter === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    Semua
                </a>
                <a href="{{ route('notifikasi.index', ['filter' => 'unread']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ $filter === 'unread' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    Belum Dibaca
                </a>
                <a href="{{ route('notifikasi.index', ['filter' => 'read']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ $filter === 'read' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                    Sudah Dibaca
                </a>
            </div>

            {{-- Daftar Notifikasi --}}
            <div class="space-y-3">
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                        $redirectUrl = '#';
                        if (isset($data['task_id']) && isset($data['workspace_id'])) {
                            $redirectUrl = route('my-workspaces.task.show', [
                                'workspace' => $data['workspace_id'],
                                'task' => $data['task_id']
                            ]);
                        }
                    @endphp

                    <div class="bg-white rounded-lg border {{ $isUnread ? 'border-indigo-200 ring-2 ring-indigo-100' : 'border-gray-200' }} shadow-sm hover:shadow-md transition-all cursor-pointer"
                         onclick="markAsReadAndRedirect('{{ route('notifikasi.read', $notification) }}', '{{ $redirectUrl }}', {{ $isUnread ? 'true' : 'false' }})">
                        <div class="p-4 sm:p-5">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <!-- Ikon + Badge -->
                                <div class="flex-shrink-0 relative">
                                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-lg {{ $isUnread ? 'bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg' : 'bg-gray-100' }} flex items-center justify-center">
                                        <svg class="w-6 h-6 {{ $isUnread ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                    </div>
                                    @if($isUnread)
                                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-600"></span>
                                        </span>
                                    @endif
                                </div>

                                <!-- Konten -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="text-sm sm:text-base font-bold text-gray-900 break-words">
                                                    {{ $data['title'] ?? 'Notifikasi' }}
                                                </h3>
                                                @if($isUnread)
                                                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">Baru</span>
                                                @endif
                                            </div>
                                            <p class="text-xs sm:text-sm text-gray-600 leading-relaxed break-words line-clamp-2">
                                                {{ $data['message'] ?? 'Anda memiliki notifikasi baru' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 pt-2 border-t border-gray-100">
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
                                                {{ Str::limit($data['task_title'], 25) }}
                                            </span>
                                        @endif
                                        @if($isUnread)
                                            <span class="ml-auto text-indigo-600 font-medium">Klik untuk baca →</span>
                                        @else
                                            <span class="ml-auto text-green-600 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Dibaca
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8 sm:p-12 text-center">
                        <div class="max-w-sm mx-auto">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">
                                @if($filter === 'unread')
                                    Semua Sudah Dibaca!
                                @elseif($filter === 'read')
                                    Belum Ada Notifikasi Dibaca
                                @else
                                    Semua Bersih!
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500">
                                @if($filter === 'unread')
                                    Tidak ada notifikasi yang belum dibaca.
                                @elseif($filter === 'read')
                                    Tidak ada notifikasi yang sudah dibaca.
                                @else
                                    Tidak ada notifikasi saat ini.
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($notifications->hasPages())
                <div class="mt-6">
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-3">
                        {{ $notifications->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- AJAX Mark as Read -->
    <script>
        function markAsReadAndRedirect(markUrl, redirectUrl, isUnread) {
            if (redirectUrl === '#') return;

            // Hanya mark as read jika masih unread
            if (!isUnread) {
                window.location.href = redirectUrl;
                return;
            }

            fetch(markUrl, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(() => {
                window.location.href = redirectUrl;
            })
            .catch(() => {
                window.location.href = redirectUrl;
            });
        }
    </script>

    <style>
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</x-app-layout>