@extends('admin.layouts.admin')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Task Detail</h1>

<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">{{ $task->title }}</h2>
    <p><strong>Owner:</strong> {{ $task->user->name ?? '-' }}</p>
    <p><strong>Description:</strong> {{ $task->description ?? '-' }}</p>
    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $task->status)) }}</p>
    <p><strong>Priority:</strong> {{ ucfirst($task->priority) }}</p>
    <p><strong>Due Date:</strong> {{ $task->due_date ?? '-' }}</p>
</div>

<div class="mt-6">
    <a href="{{ route('task.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Back to Tasks</a>
</div>
@endsection
