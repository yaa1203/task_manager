</x-app-layout>
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $workspace->name }}</h1>
        <p class="text-sm text-gray-600">{{ $workspace->description }}</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Daftar Tasks --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tugas</h2>

        @if($tasks->count() > 0)
            <div class="space-y-6">
                @foreach($tasks as $task)
                    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-semibold text-gray-900">{{ $task->title }}</h3>
                            <span class="text-xs text-gray-500">{{ $task->status ?? 'Pending' }}</span>
                        </div>
                        <p class="text-sm text-gray-700 mb-4">{{ $task->description ?? 'Tidak ada deskripsi' }}</p>

                        @if($task->submissions->count() > 0)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                <p class="font-medium text-green-800 mb-1">Jawaban Anda:</p>
                                @foreach($task->submissions as $submission)
                                    <ul class="text-sm text-green-700 space-y-1">
                                        @if($submission->file_path)
                                            <li>📎 <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-indigo-600 underline">Lihat File</a></li>
                                        @endif
                                        @if($submission->link)
                                            <li>🔗 <a href="{{ $submission->link }}" target="_blank" class="text-indigo-600 underline">{{ $submission->link }}</a></li>
                                        @endif
                                        @if($submission->notes)
                                            <li>📝 {{ $submission->notes }}</li>
                                        @endif
                                        <li class="text-xs text-gray-400">Dikirim {{ $submission->created_at->diffForHumans() }}</li>
                                    </ul>
                                @endforeach
                            </div>
                        @else
                            <form action="{{ route('user.workspaces.task.submit', [$workspace, $task]) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload File</label>
                                    <input type="file" name="file" class="w-full border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Link Jawaban</label>
                                    <input type="url" name="link" class="w-full border-gray-300 rounded-lg" placeholder="https://contoh.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                    <textarea name="notes" class="w-full border-gray-300 rounded-lg" rows="3"></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                        Kirim Jawaban
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada tugas untuk Anda di workspace ini.</p>
        @endif
    </div>

    {{-- Daftar Projects --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Proyek</h2>
        @if($projects->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($projects as $project)
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <h3 class="font-semibold text-gray-900">{{ $project->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $project->description }}</p>
                        <p class="text-xs text-gray-500">Status: {{ ucfirst($project->status ?? 'pending') }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada proyek untuk Anda di workspace ini.</p>
        @endif
    </div>
</div>
</x-app-layout>
