@extends('admin.layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-4 sm:py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Create New Task</h1>
                    <p class="text-sm sm:text-base text-gray-600">Assign a task to one or multiple users</p>
                </div>
                <div>
                    <a href="{{ route('tugas.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition border border-gray-300 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Back to Tasks</span>
                        <span class="sm:hidden">Back</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm animate-fade-in">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm sm:text-base text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm animate-fade-in">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm sm:text-base text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('tugas.store') }}" method="POST" id="taskForm">
                @csrf
                
                <div class="p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8">
                    <!-- User Assignment Section -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-semibold text-gray-900">
                                Assign to Users <span class="text-red-500">*</span>
                            </label>
                            <span id="selectedCount" class="text-xs sm:text-sm text-gray-500 font-medium">
                                0 selected
                            </span>
                        </div>
                        
                        <!-- Assign to All Option -->
                        <div class="bg-indigo-50 border-2 border-indigo-200 rounded-lg p-4">
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" id="assign_to_all" name="assign_to_all" value="1" 
                                       class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500 mt-0.5 flex-shrink-0">
                                <div class="flex-1">
                                    <span class="text-sm sm:text-base font-semibold text-indigo-900 group-hover:text-indigo-700">
                                        Assign to all users
                                    </span>
                                    <p class="text-xs sm:text-sm text-indigo-700 mt-1">
                                        Select this to automatically assign the task to all users in the system
                                    </p>
                                </div>
                            </label>
                        </div>

                        <!-- Individual Users Selection -->
                        <div id="userSelectionContainer">
                            <div class="mb-3">
                                <input type="text" id="searchUsers" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                placeholder="Search users by name or email...">
                            </div>

                            <div class="border border-gray-300 rounded-lg divide-y divide-gray-200 max-h-64 sm:max-h-80 overflow-y-auto">
                                @forelse($users as $user)
                                <label class="flex items-start gap-3 p-3 sm:p-4 hover:bg-gray-50 cursor-pointer transition user-item">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" 
                                           class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500 mt-0.5 flex-shrink-0 user-checkbox">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                            <span class="text-sm sm:text-base font-medium text-gray-900 truncate user-name">
                                                {{ $user->name }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 w-fit">
                                                User
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5 truncate user-email">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </label>
                                @empty
                                <div class="p-8 text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <p class="text-gray-500 text-sm">No users available</p>
                                </div>
                                @endforelse
                            </div>
                            
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Select one or more users to assign this task
                            </p>
                        </div>
                        
                        @error('user_ids')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-gray-200"></div>

                    <!-- Task Details Section -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900">Task Details</h3>

                        <!-- Task Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-900 mb-2">
                                Task Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required
                                   value="{{ old('title') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm sm:text-base"
                                   placeholder="Enter a clear and concise task title">
                            @error('title')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-900 mb-2">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm sm:text-base"
                                      placeholder="Provide detailed instructions and context for this task...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status and Priority Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-900 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="status" name="status" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm sm:text-base">
                                    <option value="todo" {{ old('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                                </select>
                                @error('status')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-900 mb-2">
                                    Priority <span class="text-red-500">*</span>
                                </label>
                                <select id="priority" name="priority" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm sm:text-base">
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('priority')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-900 mb-2">
                                Due Date
                            </label>
                            <input type="date" id="due_date" name="due_date"
                                   value="{{ old('due_date') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm sm:text-base">
                            @error('due_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button Section -->
                <div class="border-t border-gray-200 px-4 py-4 sm:px-6 sm:py-5 lg:px-8 bg-gray-50 rounded-b-xl">
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('tugas.index') }}" 
                           class="w-full sm:w-auto text-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                            Cancel
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm">
                            Create Task
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Styles and Scripts -->
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }

    /* Custom scrollbar for user list */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const assignToAllCheckbox = document.getElementById('assign_to_all');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const userSelectionContainer = document.getElementById('userSelectionContainer');
    const selectedCountSpan = document.getElementById('selectedCount');
    const searchInput = document.getElementById('searchUsers');
    const userItems = document.querySelectorAll('.user-item');

    // Function to update selected count
    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked').length;
        selectedCountSpan.textContent = `${checkedBoxes} selected`;
    }

    // Handle "Assign to All" checkbox
    assignToAllCheckbox.addEventListener('change', function() {
        if (this.checked) {
            userSelectionContainer.style.opacity = '0.5';
            userSelectionContainer.style.pointerEvents = 'none';
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.disabled = true;
            });
            searchInput.disabled = true;
            selectedCountSpan.textContent = 'All users';
        } else {
            userSelectionContainer.style.opacity = '1';
            userSelectionContainer.style.pointerEvents = 'auto';
            userCheckboxes.forEach(checkbox => {
                checkbox.disabled = false;
            });
            searchInput.disabled = false;
            updateSelectedCount();
        }
    });

    // Update count when individual checkboxes change
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        userItems.forEach(item => {
            const name = item.querySelector('.user-name').textContent.toLowerCase();
            const email = item.querySelector('.user-email').textContent.toLowerCase();
            
            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Form validation
    document.getElementById('taskForm').addEventListener('submit', function(e) {
        const assignToAll = assignToAllCheckbox.checked;
        const checkedUsers = document.querySelectorAll('.user-checkbox:checked').length;
        
        if (!assignToAll && checkedUsers === 0) {
            e.preventDefault();
            alert('Please select at least one user or check "Assign to all users"');
            return false;
        }
    });

    // Initialize count on page load
    updateSelectedCount();
});
</script>
@endsection