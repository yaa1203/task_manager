@extends('admin.layouts.admin')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Project Detail</h1>

<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">{{ $project->name }}</h2>
    <p><strong>Owner:</strong> {{ $project->user->name ?? '-' }}</p>
    <p><strong>Description:</strong> {{ $project->description ?? '-' }}</p>
    <p><strong>Start Date:</strong> {{ $project->start_date ?? '-' }}</p>
    <p><strong>End Date:</strong> {{ $project->end_date ?? '-' }}</p>
</div>

<div class="mt-6">
    <a href="{{ route('project.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Back to Projects</a>
</div>
@endsection
