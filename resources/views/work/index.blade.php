<x-app-layout>
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Workspace Saya</h1>

    @if($workspaces->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($workspaces as $workspace)
                <a href="{{ route('user.workspaces.show', $workspace) }}" 
                   class="block bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 flex items-center justify-center text-xl rounded-lg" 
                             style="background-color: {{ $workspace->color }}20;">
                            {{ $workspace->icon }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $workspace->name }}</h3>
                            <p class="text-xs text-gray-500">{{ ucfirst($workspace->type) }}</p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $workspace->description }}</p>

                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>{{ $workspace->tasks_count }} Task</span>
                        <span>{{ $workspace->projects_count }} Project</span>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center bg-white border border-dashed border-gray-300 rounded-xl py-16">
            <p class="text-gray-500">Belum ada workspace yang berisi tugas untuk Anda.</p>
        </div>
    @endif
</div>
</x-app-layout>
