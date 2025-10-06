<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">📅 Calendar</h2>
                <p class="text-sm text-gray-600 mt-1">Manage your tasks and projects</p>
            </div>
            <button onclick="openQuickAddModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition text-sm sm:text-base font-medium shadow-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden sm:inline">Quick Add Task</span>
                <span class="sm:hidden">Add Task</span>
            </button>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            
            {{-- Statistics Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                @php
                $statCards = [
                    ['label' => 'Total Tasks', 'value' => $stats['total_tasks'], 'color' => 'blue', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['label' => 'Completed', 'value' => $stats['completed_tasks'], 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Overdue', 'value' => $stats['overdue_tasks'], 'color' => 'red', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Projects', 'value' => $stats['total_projects'], 'color' => 'purple', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z']
                ];
                @endphp

                @foreach($statCards as $card)
                <div class="bg-white rounded-lg shadow p-3 sm:p-4 border-l-4 border-{{ $card['color'] }}-500 transition-all hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 font-medium">{{ $card['label'] }}</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">{{ $card['value'] }}</p>
                        </div>
                        <div class="bg-{{ $card['color'] }}-100 p-2 sm:p-3 rounded-full">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-{{ $card['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Filter Panel --}}
            <div class="bg-white rounded-lg shadow p-3 sm:p-4">
                <div class="flex flex-col sm:flex-row flex-wrap gap-3 items-start sm:items-center">
                    <span class="text-sm font-semibold text-gray-700">Filters:</span>
                    
                    <div class="flex flex-wrap gap-3">
                        @foreach([
                            ['id' => 'filter-tasks', 'label' => 'Tasks', 'color' => 'blue'],
                            ['id' => 'filter-projects', 'label' => 'Projects', 'color' => 'purple'],
                            ['id' => 'filter-completed', 'label' => 'Completed', 'color' => 'green'],
                            ['id' => 'filter-overdue', 'label' => 'Overdue', 'color' => 'red']
                        ] as $filter)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="{{ $filter['id'] }}" checked class="rounded text-{{ $filter['color'] }}-600 focus:ring-{{ $filter['color'] }}-500">
                            <span class="text-xs sm:text-sm text-gray-700">{{ $filter['label'] }}</span>
                        </label>
                        @endforeach
                    </div>

                    <button onclick="calendar.today()" class="ml-auto text-xs sm:text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Today
                    </button>
                </div>
            </div>

            {{-- Calendar --}}
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-4 text-white">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3 id="calendar-title" class="text-lg sm:text-xl font-semibold">Calendar</h3>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="calendar.prev()" class="bg-white bg-opacity-20 hover:bg-opacity-30 p-2 rounded-md transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button onclick="calendar.today()" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-1 rounded-md text-sm font-medium transition">
                                Today
                            </button>
                            <button onclick="calendar.next()" class="bg-white bg-opacity-20 hover:bg-opacity-30 p-2 rounded-md transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="calendar" class="p-3 sm:p-6"></div>
            </div>

            {{-- Legend --}}
            <div class="bg-white rounded-lg shadow p-3 sm:p-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Legend</h3>
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-3 sm:gap-4">
                    @foreach([
                        ['color' => 'green', 'label' => 'Completed'],
                        ['color' => 'blue', 'label' => 'In Progress'],
                        ['color' => 'yellow', 'label' => 'Pending'],
                        ['color' => 'red', 'label' => 'Overdue'],
                        ['color' => 'purple', 'label' => 'Project']
                    ] as $legend)
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 sm:w-4 sm:h-4 bg-{{ $legend['color'] }}-500 rounded"></div>
                        <span class="text-xs sm:text-sm text-gray-600">{{ $legend['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Add Task Modal --}}
    <div id="quickAddModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 p-4">
        <div class="relative top-10 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Quick Add Task</h3>
                <button onclick="closeQuickAddModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="quickAddForm" onsubmit="submitQuickAdd(event)">
                @foreach([
                    ['id' => 'quick-title', 'label' => 'Task Title', 'type' => 'text', 'placeholder' => 'Enter task title...'],
                    ['id' => 'quick-date', 'label' => 'Due Date', 'type' => 'date']
                ] as $field)
                <div class="mb-4">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">{{ $field['label'] }}</label>
                    <input type="{{ $field['type'] }}" id="{{ $field['id'] }}" required 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif>
                </div>
                @endforeach

                <div class="mb-4">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select id="quick-priority" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $val => $label)
                        <option value="{{ $val }}" {{ $val === 'medium' ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 sm:gap-3">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg transition text-sm">
                        Add Task
                    </button>
                    <button type="button" onclick="closeQuickAddModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-lg transition text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Event Details Modal --}}
    <div id="eventModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 p-4">
        <div class="relative top-10 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 id="eventModalTitle" class="text-base sm:text-lg font-semibold text-gray-900 pr-4"></h3>
                <button onclick="closeEventModal()" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div id="eventModalContent" class="space-y-3 text-sm"></div>

            <div class="mt-6 flex gap-2 sm:gap-3">
                <a id="eventModalLink" href="#" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg transition text-sm">
                    View Details
                </a>
                <button onclick="closeEventModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-lg transition text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>

    {{-- Toast Notification --}}
    <div id="toast" class="hidden fixed bottom-4 right-4 left-4 sm:left-auto bg-gray-800 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg z-50 text-sm sm:text-base"></div>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <style>
        #calendar { font-family: 'Inter', sans-serif; }
        
        .fc .fc-button {
            background-color: #4f46e5;
            border: none;
            padding: 0.4rem 0.8rem;
            text-transform: capitalize;
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: 6px;
        }
        .fc .fc-button:hover { 
            background-color: #4338ca; 
        }
        .fc .fc-button-active { 
            background-color: #4338ca !important; 
        }
        .fc-theme-standard td, .fc-theme-standard th { 
            border-color: #e5e7eb; 
        }
        .fc-day-today { 
            background-color: #ede9fe !important; 
        }
        .fc-event {
            cursor: pointer;
            border-radius: 6px;
            padding: 2px 4px;
            font-size: 0.75rem;
            font-weight: 500;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        .fc-event:hover { 
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .fc-daygrid-event { 
            white-space: normal; 
        }
        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }
        .fc-col-header-cell {
            background-color: #f9fafb;
            font-weight: 600;
            color: #6b7280;
            padding: 0.75rem 0;
            font-size: 0.875rem;
        }
        
        @media (max-width: 640px) {
            .fc .fc-toolbar { 
                flex-direction: column; 
                gap: 0.5rem; 
            }
            .fc .fc-toolbar-chunk { 
                width: 100%; 
            }
            .fc .fc-toolbar-title { 
                font-size: 1rem; 
                text-align: center; 
            }
            .fc-header-toolbar { 
                margin-bottom: 1rem !important; 
            }
            .fc .fc-button { 
                padding: 0.375rem 0.75rem; 
                font-size: 0.75rem; 
            }
            .fc-daygrid-day-number { 
                font-size: 0.875rem; 
            }
        }
    </style>

    <script>
        let calendar, allEvents = [];

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listWeek'
                },
                height: 'auto',
                navLinks: true,
                editable: true,
                selectable: true,
                dayMaxEvents: 3,
                
                events: function(info, successCallback, failureCallback) {
                    fetch('{{ route("calendar.events") }}')
                        .then(res => res.json())
                        .then(data => {
                            allEvents = data;
                            applyFilters();
                        })
                        .catch(err => failureCallback(err));
                },

                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    showEventModal(info.event);
                },

                select: function(info) {
                    document.getElementById('quick-date').value = info.startStr;
                    openQuickAddModal();
                },

                eventDrop: function(info) { updateEventDate(info.event); },
                eventResize: function(info) { updateEventDate(info.event); },

                eventContent: function(arg) {
                    const props = arg.event.extendedProps;
                    let icon = '';
                    if (props.type === 'task') {
                        if (props.isOverdue) icon = '⚠️ ';
                        else if (props.status === 'done') icon = '✓ ';
                    }
                    return { html: `<div class="fc-event-title">${icon}${arg.event.title}</div>` };
                }
            });

            calendar.render();
            setupFilters();
            
            // Update calendar title
            updateCalendarTitle();
            
            // Responsive view switch
            window.addEventListener('resize', () => {
                if (window.innerWidth < 768 && calendar.view.type === 'dayGridMonth') {
                    calendar.changeView('listWeek');
                }
            });
        });

        function updateCalendarTitle() {
            const titleElement = document.getElementById('calendar-title');
            const currentDate = calendar.getDate();
            const options = { year: 'numeric', month: 'long' };
            
            if (calendar.view.type === 'dayGridMonth') {
                titleElement.textContent = currentDate.toLocaleDateString(undefined, options);
            } else if (calendar.view.type === 'listWeek') {
                const weekStart = new Date(currentDate);
                weekStart.setDate(weekStart.getDate() - currentDate.getDay());
                const weekEnd = new Date(weekStart);
                weekEnd.setDate(weekEnd.getDate() + 6);
                
                titleElement.textContent = `${weekStart.toLocaleDateString(undefined, { month: 'short', day: 'numeric' })} - ${weekEnd.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' })}`;
            }
        }

        function applyFilters() {
            const filters = {
                tasks: document.getElementById('filter-tasks').checked,
                projects: document.getElementById('filter-projects').checked,
                completed: document.getElementById('filter-completed').checked,
                overdue: document.getElementById('filter-overdue').checked
            };

            const filtered = allEvents.filter(e => {
                const p = e.extendedProps;
                if (p.type === 'task' && !filters.tasks) return false;
                if (p.type === 'project' && !filters.projects) return false;
                if (p.type === 'task') {
                    if (p.status === 'done' && !filters.completed) return false;
                    if (p.isOverdue && !filters.overdue) return false;
                }
                return true;
            });

            calendar.getEventSources().forEach(s => s.remove());
            calendar.addEventSource(filtered);
        }

        function setupFilters() {
            ['filter-tasks', 'filter-projects', 'filter-completed', 'filter-overdue'].forEach(id => {
                document.getElementById(id).addEventListener('change', applyFilters);
            });
        }

        function openQuickAddModal() {
            document.getElementById('quickAddModal').classList.remove('hidden');
            document.getElementById('quick-title').focus();
        }

        function closeQuickAddModal() {
            document.getElementById('quickAddModal').classList.add('hidden');
            document.getElementById('quickAddForm').reset();
        }

        function submitQuickAdd(e) {
            e.preventDefault();
            
            fetch('{{ route("calendar.quick-create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    title: document.getElementById('quick-title').value,
                    due_date: document.getElementById('quick-date').value,
                    priority: document.getElementById('quick-priority').value
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    closeQuickAddModal();
                    calendar.refetchEvents();
                }
            })
            .catch(() => showToast('Failed to create task', 'error'));
        }

        function showEventModal(event) {
            const p = event.extendedProps;
            const statusColors = {
                done: 'bg-green-100 text-green-800',
                in_progress: 'bg-blue-100 text-blue-800',
                pending: 'bg-yellow-100 text-yellow-800'
            };
            
            document.getElementById('eventModalTitle').textContent = event.title;
            document.getElementById('eventModalLink').href = event.url;
            
            let content = p.type === 'task' ? `
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700">Status:</span>
                        <span class="px-2 py-1 rounded text-xs font-medium ${statusColors[p.status] || 'bg-gray-100 text-gray-800'}">
                            ${p.status.replace('_', ' ').toUpperCase()}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700">Due:</span>
                        <span class="text-gray-800">${event.startStr}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700">Priority:</span>
                        <span class="px-2 py-1 rounded text-xs font-medium ${
                            p.priority === 'high' ? 'bg-red-100 text-red-800' : 
                            p.priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 
                            'bg-green-100 text-green-800'
                        }">
                            ${p.priority.toUpperCase()}
                        </span>
                    </div>
                    ${p.isOverdue ? '<div class="mt-2 p-3 bg-red-50 text-red-700 rounded-lg flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> <span class="font-medium">Overdue</span></div>' : ''}
                    ${p.description ? `<div class="mt-3"><h4 class="font-medium text-gray-700 mb-1">Description</h4><p class="text-gray-600">${p.description}</p></div>` : ''}
                </div>
            ` : `
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-700">Start:</span>
                        <span class="text-gray-800">${event.startStr}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">End:</span>
                        <span class="text-gray-800">${event.endStr}</span>
                    </div>
                    ${p.description ? `<div class="mt-3"><h4 class="font-medium text-gray-700 mb-1">Description</h4><p class="text-gray-600">${p.description}</p></div>` : ''}
                </div>
            `;
            
            document.getElementById('eventModalContent').innerHTML = content;
            document.getElementById('eventModal').classList.remove('hidden');
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.add('hidden');
        }

        function updateEventDate(event) {
            const p = event.extendedProps;
            const id = event.id.split('-')[1];
            const url = p.type === 'task' ? `/calendar/tasks/${id}/update-date` : `/calendar/projects/${id}/update-date`;
            const data = p.type === 'task' ? { due_date: event.startStr } : { start_date: event.startStr, end_date: event.endStr };
            
            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => data.success && showToast(data.message))
            .catch(() => {
                showToast('Failed to update date', 'error');
                event.revert();
            });
        }

        function showToast(msg, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = msg;
            toast.className = `fixed bottom-4 right-4 left-4 sm:left-auto px-4 sm:px-6 py-3 rounded-lg shadow-lg z-50 text-sm ${
                type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'
            }`;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeQuickAddModal();
                closeEventModal();
            }
        });
    </script>
</x-app-layout>