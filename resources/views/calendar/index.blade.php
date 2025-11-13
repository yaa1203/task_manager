<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Kalender Tugas</h2>
                <p class="text-sm text-gray-600 mt-1">Lihat jadwal dan kelola tugas Anda dalam tampilan kalender</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            
            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                @php
                    $allTasks = collect($tasks);
                    $todayTasks = $allTasks->filter(function($task) {
                        if (!$task['due_date']) return false;
                        $taskDate = \Carbon\Carbon::parse($task['due_date']);
                        return $taskDate->isToday();
                    })->count();
                    
                    $thisWeekTasks = $allTasks->filter(function($task) {
                        if (!$task['due_date']) return false;
                        $taskDate = \Carbon\Carbon::parse($task['due_date']);
                        return $taskDate->isCurrentWeek();
                    })->count();
                    
                    $upcomingTasks = $allTasks->filter(function($task) {
                        if (!$task['due_date']) return false;
                        $taskDate = \Carbon\Carbon::parse($task['due_date']);
                        $hasSubmission = isset($task['submissions']) && count($task['submissions']) > 0;
                        return $taskDate->isFuture() && !$hasSubmission;
                    })->count();
                    
                    $overdueTasks = $allTasks->filter(function($task) {
                        if (!$task['due_date']) return false;
                        $taskDate = \Carbon\Carbon::parse($task['due_date']);
                        $hasSubmission = isset($task['submissions']) && count($task['submissions']) > 0;
                        return $taskDate->isPast() && !$hasSubmission;
                    })->count();
                @endphp

                <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 border-l-4 border-blue-500 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p id="selectedDateLabel" class="text-xs sm:text-sm text-gray-600 font-medium">Hari Ini</p>
                            <p id="selectedDateCount" class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">{{ $todayTasks }}</p>
                        </div>
                        <div class="bg-blue-100 p-2 sm:p-3 rounded-full">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 border-l-4 border-purple-500 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 font-medium">Minggu Ini</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">{{ $thisWeekTasks }}</p>
                        </div>
                        <div class="bg-purple-100 p-2 sm:p-3 rounded-full">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 border-l-4 border-green-500 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 font-medium">Mendatang</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">{{ $upcomingTasks }}</p>
                        </div>
                        <div class="bg-green-100 p-2 sm:p-3 rounded-full">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-3 sm:p-4 border-l-4 border-red-500 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 font-medium">Terlambat</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">{{ $overdueTasks }}</p>
                        </div>
                        <div class="bg-red-100 p-2 sm:p-3 rounded-full">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Calendar Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <button type="button" 
                                onclick="previousMonth()"
                                class="p-2 rounded-lg bg-white/10 hover:bg-white/20 text-white transition-colors">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        
                        <div class="text-center">
                            <h2 id="currentMonth" class="text-xl sm:text-2xl font-bold text-white"></h2>
                            <p id="currentYear" class="text-sm sm:text-base text-blue-100"></p>
                        </div>
                        
                        <button type="button" 
                                onclick="nextMonth()"
                                class="p-2 rounded-lg bg-white/10 hover:bg-white/20 text-white transition-colors">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <button type="button" 
                            onclick="goToToday()"
                            id="todayButton"
                            class="w-full py-2 px-4 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors font-medium text-sm sm:text-base">
                            <span id="todayButtonText">Hari Ini</span>
                    </button>
                </div>

                <!-- Calendar Grid -->
                <div class="p-3 sm:p-6">
                    <!-- Days of Week -->
                    <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-2">
                        <div class="text-center py-2 text-xs sm:text-sm font-bold text-gray-700">Min</div>
                        <div class="text-center py-2 text-xs sm:text-sm font-bold text-gray-700">Sen</div>
                        <div class="text-center py-2 text-xs sm:text-sm font-bold text-gray-700">Sel</div>
                        <div class="text-center py-2 text-xs sm:text-sm font-bold text-gray-700">Rab</div>
                        <div class="text-center py-2 text-xs sm:text-sm font-bold text-gray-700">Kam</div>
                        <div class="text-center py-2 text-xs sm:text-sm font-bold text-gray-700">Jum</div>
                        <div class="text-center py-2 text-xs sm:text-sm font-bold text-gray-700">Sab</div>
                    </div>

                    <!-- Calendar Days -->
                    <div id="calendarDays" class="grid grid-cols-7 gap-1 sm:gap-2">
                        <!-- Days will be generated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Legend -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Keterangan:</h3>
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500 flex-shrink-0"></div>
                        <span class="text-xs sm:text-sm text-gray-700">Segera</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-orange-500 flex-shrink-0"></div>
                        <span class="text-xs sm:text-sm text-gray-700">Tinggi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-yellow-500 flex-shrink-0"></div>
                        <span class="text-xs sm:text-sm text-gray-700">Sedang</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-gray-400 flex-shrink-0"></div>
                        <span class="text-xs sm:text-sm text-gray-700">Rendah</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-green-500 flex-shrink-0"></div>
                        <span class="text-xs sm:text-sm text-gray-700">Selesai</span>
                    </div>
                </div>
            </div>

            <!-- Task Details Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span id="selectedDateTitle" class="truncate">Tugas Hari Ini</span>
                    </h3>
                    <span id="taskCount" class="text-xs sm:text-sm font-semibold text-gray-600 bg-gray-100 px-3 py-1 rounded-full flex-shrink-0">0 tugas</span>
                </div>

                <!-- Task List -->
                <div id="taskList" class="space-y-3">
                    <!-- Tasks will be populated by JavaScript -->
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center py-8 sm:py-12 hidden">
                    <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 font-medium text-sm sm:text-base">Tidak ada tugas pada tanggal ini</p>
                    <p class="text-gray-400 text-xs sm:text-sm mt-1">Pilih tanggal lain untuk melihat tugas</p>
                </div>
            </div>
        </div>
    </div>

<script>
// Task data from backend
const tasks = @json($tasks);
const today = new Date();
let currentDate = new Date();
let selectedDate = new Date();

// Month names in Indonesian
const monthNames = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

// Initialize calendar
document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();
    displayTasksForDate(today);
});

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    // Update header
    document.getElementById('currentMonth').textContent = monthNames[month];
    document.getElementById('currentYear').textContent = year;
    
    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    
    const calendarDays = document.getElementById('calendarDays');
    calendarDays.innerHTML = '';
    
    // Previous month days
    for (let i = firstDay - 1; i >= 0; i--) {
        const day = daysInPrevMonth - i;
        const dayElement = createDayElement(day, month - 1, year, true);
        calendarDays.appendChild(dayElement);
    }
    
    // Current month days
    for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = createDayElement(day, month, year, false);
        calendarDays.appendChild(dayElement);
    }
    
    // Next month days
    const totalCells = calendarDays.children.length;
    const remainingCells = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
    for (let day = 1; day <= remainingCells; day++) {
        const dayElement = createDayElement(day, month + 1, year, true);
        calendarDays.appendChild(dayElement);
    }
}

function createDayElement(day, month, year, isOtherMonth) {
    const div = document.createElement('div');
    const date = new Date(year, month, day);
    const dateStr = formatDateToString(date);
    
    // Get tasks for this date
    const dayTasks = getTasksForDate(date);
    
    // Check if it's today
    const isToday = date.toDateString() === today.toDateString();
    
    // Check if it's selected date
    const isSelected = date.toDateString() === selectedDate.toDateString();
    
    // Base classes - Optimized for mobile
    let classes = 'relative rounded-lg transition-all cursor-pointer ';
    classes += 'min-h-[50px] sm:min-h-[70px] '; // Responsive height
    
    if (isOtherMonth) {
        classes += 'text-gray-300 hover:bg-gray-50 ';
    } else {
        classes += 'text-gray-900 hover:bg-blue-50 hover:shadow-sm ';
    }
    
    if (isToday && !isOtherMonth) {
        classes += 'bg-blue-100 font-bold border-2 border-blue-500 ';
    } else if (isSelected) {
        classes += 'bg-blue-50 border-2 border-blue-300 ';
    } else {
        classes += 'border border-gray-200 ';
    }
    
    div.className = classes;
    div.onclick = () => selectDate(date);
    
    // Day number
    const dayNumber = document.createElement('div');
    dayNumber.className = 'absolute top-1 left-1 sm:top-2 sm:left-2 text-xs sm:text-sm font-semibold';
    dayNumber.textContent = day;
    div.appendChild(dayNumber);
    
    // Task indicators
    if (dayTasks.length > 0 && !isOtherMonth) {
        const indicators = document.createElement('div');
        indicators.className = 'absolute bottom-1 left-1/2 transform -translate-x-1/2 flex gap-0.5 sm:gap-1';
        
        // Show max 3 indicators on mobile, 4 on desktop
        const isMobile = window.innerWidth < 640;
        const maxIndicators = Math.min(dayTasks.length, isMobile ? 3 : 4);
        
        for (let i = 0; i < maxIndicators; i++) {
            const task = dayTasks[i];
            const indicator = document.createElement('div');
            indicator.className = 'w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full ' + getPriorityColor(task);
            indicators.appendChild(indicator);
        }
        
        // Show count if more than max
        if (dayTasks.length > maxIndicators) {
            const moreCount = document.createElement('div');
            moreCount.className = 'text-[10px] sm:text-xs font-bold text-gray-600';
            moreCount.textContent = '+' + (dayTasks.length - maxIndicators);
            indicators.appendChild(moreCount);
        }
        
        div.appendChild(indicators);
    }
    
    return div;
}

function getTasksForDate(date) {
    const dateStr = formatDateToString(date);
    return tasks.filter(task => {
        if (!task.due_date) return false;
        const taskDate = new Date(task.due_date);
        return formatDateToString(taskDate) === dateStr;
    });
}

function formatDateToString(date) {
    return date.getFullYear() + '-' + 
           String(date.getMonth() + 1).padStart(2, '0') + '-' + 
           String(date.getDate()).padStart(2, '0');
}

function getPriorityColor(task) {
    // Check if completed
    const userSubmission = task.submissions && task.submissions.length > 0;
    if (userSubmission) {
        return 'bg-green-500';
    }
    
    // Check priority
    switch(task.priority) {
        case 'urgent': return 'bg-red-500';
        case 'high': return 'bg-orange-500';
        case 'medium': return 'bg-yellow-500';
        default: return 'bg-gray-400';
    }
}

function getPriorityLabel(priority) {
    switch(priority) {
        case 'urgent': return 'Segera';
        case 'high': return 'Tinggi';
        case 'medium': return 'Sedang';
        case 'low': return 'Rendah';
        default: return 'Tidak Diketahui';
    }
}

function selectDate(date) {
    selectedDate = date;
    renderCalendar();
    displayTasksForDate(date);
}

function updateTodayButton(date) {
    const todayButtonText = document.getElementById('todayButtonText');
    const isToday = date.toDateString() === today.toDateString();
    
    if (isToday) {
        todayButtonText.textContent = 'Hari Ini';
    } else {
        // Format tanggal singkat
        const options = { day: 'numeric', month: 'short' };
        const formattedDate = date.toLocaleDateString('id-ID', options);
        todayButtonText.textContent = formattedDate;
    }
}

function displayTasksForDate(date) {
    const dateStr = formatDateToString(date);
    const dayTasks = getTasksForDate(date);
    
    // Update title - Format tanggal sesuai yang diklik
    const titleElement = document.getElementById('selectedDateTitle');
    const taskCountElement = document.getElementById('taskCount');
    
    // Format tanggal dalam bahasa Indonesia
    const options = { 
        weekday: 'long',
        day: 'numeric', 
        month: 'long', 
        year: 'numeric'
    };
    const formattedDate = date.toLocaleDateString('id-ID', options);
    
    // Update judul dengan tanggal yang dipilih
    titleElement.textContent = formattedDate;
    
    // Update task count
    taskCountElement.textContent = dayTasks.length + ' tugas';
    
    // Update card "Hari Ini"
    const selectedDateLabel = document.getElementById('selectedDateLabel');
    const selectedDateCount = document.getElementById('selectedDateCount');
    
    // Cek apakah tanggal yang dipilih adalah hari ini
    const isToday = date.toDateString() === today.toDateString();
    
    if (isToday) {
        selectedDateLabel.textContent = 'Hari Ini';
    } else {
        // Format singkat untuk label card
        const shortOptions = { day: 'numeric', month: 'short' };
        selectedDateLabel.textContent = date.toLocaleDateString('id-ID', shortOptions);
    }
    
    selectedDateCount.textContent = dayTasks.length;
    
    // Update button "Hari Ini"
    updateTodayButton(date);
    
    // Display tasks
    const taskList = document.getElementById('taskList');
    const emptyState = document.getElementById('emptyState');
    
    if (dayTasks.length === 0) {
        taskList.classList.add('hidden');
        emptyState.classList.remove('hidden');
    } else {
        taskList.classList.remove('hidden');
        emptyState.classList.add('hidden');
        
        taskList.innerHTML = dayTasks.map(task => createTaskCard(task)).join('');
    }
}

function createTaskCard(task) {
    const hasSubmitted = task.submissions && task.submissions.length > 0;
    const dueDate = new Date(task.due_date);
    const isPast = dueDate < today && !hasSubmitted;
    
    // Priority colors
    let priorityClass = '';
    switch(task.priority) {
        case 'urgent':
            priorityClass = 'text-red-800 bg-red-100 border-red-200';
            break;
        case 'high':
            priorityClass = 'text-orange-800 bg-orange-100 border-orange-200';
            break;
        case 'medium':
            priorityClass = 'text-yellow-800 bg-yellow-100 border-yellow-200';
            break;
        default:
            priorityClass = 'text-gray-800 bg-gray-100 border-gray-200';
    }
    
    return `
        <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:border-gray-300 transition-all hover:shadow-sm">
            <div class="flex items-start justify-between gap-2 sm:gap-3 mb-3">
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base truncate">${task.title}</h4>
                    <p class="text-xs sm:text-sm text-gray-600 truncate">${task.workspace ? task.workspace.name : 'Workspace'}</p>
                </div>
                <div class="flex flex-col gap-1.5 items-end flex-shrink-0">
                    <span class="px-2 sm:px-2.5 py-0.5 sm:py-1 text-xs font-bold rounded-full border ${priorityClass}">
                        ${getPriorityLabel(task.priority)}
                    </span>
                    ${hasSubmitted ? `
                        <span class="px-2 sm:px-2.5 py-0.5 sm:py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                            <svg class="w-3 h-3 inline mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Selesai
                        </span>
                    ` : ''}
                </div>
            </div>
            
            <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600 mb-3 pb-3 border-b border-gray-100">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="${isPast ? 'text-red-600 font-semibold' : ''} truncate">
                    ${formatDateTime(task.due_date)}
                    ${isPast ? ' (Terlambat)' : ''}
                </span>
            </div>
            
            ${task.description ? `
                <p class="text-xs sm:text-sm text-gray-600 mb-3 line-clamp-2">${task.description}</p>
            ` : ''}
            
            <a href="/my-workspaces/${task.workspace_id}/task/${task.id}" 
               class="flex items-center justify-center gap-2 w-full px-3 sm:px-4 py-2 sm:py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs sm:text-sm font-medium shadow-sm">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Lihat Detail
            </a>
        </div>
    `;
}

function formatDateTime(dateTimeStr) {
    const date = new Date(dateTimeStr);
    const isMobile = window.innerWidth < 640;
    
    if (isMobile) {
        // Format lebih ringkas untuk mobile
        const options = { 
            day: 'numeric', 
            month: 'short',
            hour: '2-digit',
            minute: '2-digit'
        };
        return date.toLocaleDateString('id-ID', options);
    } else {
        const options = { 
            day: 'numeric', 
            month: 'short', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return date.toLocaleDateString('id-ID', options);
    }
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
}

function goToToday() {
    currentDate = new Date();
    selectedDate = new Date();
    renderCalendar();
    displayTasksForDate(today);
}

// Handle window resize for responsive calendar
let resizeTimer;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
        renderCalendar();
        displayTasksForDate(selectedDate);
    }, 250);
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Calendar day hover effect */
#calendarDays > div:hover {
    transform: translateY(-2px);
}

/* Smooth transitions */
#calendarDays > div {
    transition: all 0.2s ease;
}

/* Task card animation */
#taskList > div {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Optimize touch targets for mobile */
@media (max-width: 640px) {
    #calendarDays > div {
        min-height: 50px;
    }
    
    button {
        min-height: 44px;
        min-width: 44px;
    }
}

/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Loading animation */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Improve text readability on mobile */
@media (max-width: 640px) {
    body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
}

/* Stats card hover effect */
.hover\:shadow-md:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
}
</style>
</x-app-layout>