<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">📅 Calendar</h2>
                <p class="text-sm text-gray-600 mt-1">View your tasks schedule</p>
            </div>
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
                    ['label' => 'Overdue', 'value' => $stats['overdue_tasks'], 'color' => 'red', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Unfinished', 'value' => $stats['unfinished_tasks'], 'color' => 'gray', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z']
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
                            ['id' => 'filter-done', 'label' => 'Completed', 'color' => 'green'],
                            ['id' => 'filter-unfinished', 'label' => 'Unfinished', 'color' => 'gray'],
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
                        ['color' => 'gray', 'label' => 'Unfinished'],
                        ['color' => 'red', 'label' => 'Overdue']
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
                editable: false,
                selectable: false,
                dayMaxEvents: 3,
                
                events: function(info, successCallback, failureCallback) {
                    fetch('{{ route("calendar.events") }}')
                        .then(res => res.json())
                        .then(data => {
                            allEvents = data;
                            applyFilters();
                        })
                        .catch(err => {
                            console.error('Failed to load events:', err);
                            failureCallback(err);
                        });
                },

                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    showEventModal(info.event);
                },

                eventContent: function(arg) {
                    const props = arg.event.extendedProps;
                    let icon = '';
                    
                    if (props.isDone) {
                        icon = '✓ ';
                    } else if (props.isOverdue) {
                        icon = '⚠️ ';
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
                done: document.getElementById('filter-done').checked,
                unfinished: document.getElementById('filter-unfinished').checked,
                overdue: document.getElementById('filter-overdue').checked
            };

            const filtered = allEvents.filter(e => {
                const p = e.extendedProps;
                
                if (p.isDone && !filters.done) return false;
                if (p.isOverdue && !filters.overdue) return false;
                if (!p.isDone && !p.isOverdue && !filters.unfinished) return false;
                
                return true;
            });

            calendar.getEventSources().forEach(s => s.remove());
            calendar.addEventSource(filtered);
        }

        function setupFilters() {
            ['filter-done', 'filter-unfinished', 'filter-overdue'].forEach(id => {
                document.getElementById(id).addEventListener('change', applyFilters);
            });
        }

        function showEventModal(event) {
            const p = event.extendedProps;
            
            document.getElementById('eventModalTitle').textContent = event.title;
            document.getElementById('eventModalLink').href = event.url;
            
            const priorityColors = {
                urgent: 'bg-red-100 text-red-800',
                high: 'bg-orange-100 text-orange-800',
                medium: 'bg-yellow-100 text-yellow-800',
                low: 'bg-green-100 text-green-800'
            };
            
            let content = `
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700">Workspace:</span>
                        <span class="text-gray-800">${p.workspaceName || 'N/A'}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700">Status:</span>
                        ${p.isDone ? 
                            '<span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">✓ DONE</span>' : 
                            p.isOverdue ? 
                                '<span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">⚠️ OVERDUE</span>' :
                                '<span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">UNFINISHED</span>'
                        }
                    </div>
                    ${p.dueDate ? `
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700">Due Date:</span>
                        <span class="text-gray-800">${p.dueDate}</span>
                    </div>
                    ` : ''}
                    ${p.priority ? `
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700">Priority:</span>
                        <span class="px-2 py-1 rounded text-xs font-medium ${priorityColors[p.priority] || 'bg-gray-100 text-gray-800'}">
                            ${p.priority.toUpperCase()}
                        </span>
                    </div>
                    ` : ''}
                    ${p.isOverdue ? `
                    <div class="mt-2 p-3 bg-red-50 text-red-700 rounded-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span class="font-medium">This task is overdue!</span>
                    </div>
                    ` : ''}
                    ${p.description ? `
                    <div class="mt-3">
                        <h4 class="font-medium text-gray-700 mb-1">Description</h4>
                        <p class="text-gray-600 text-sm">${p.description}</p>
                    </div>
                    ` : ''}
                </div>
            `;
            
            document.getElementById('eventModalContent').innerHTML = content;
            document.getElementById('eventModal').classList.remove('hidden');
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.add('hidden');
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
                closeEventModal();
            }
        });
    </script>
</x-app-layout>