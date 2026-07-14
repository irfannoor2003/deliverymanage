<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('messages.app_name'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="antialiased bg-gray-50 dark:bg-[#050505] transition-colors duration-500 font-poppins">

<!-- System Info Bar -->
<div id="system-info" class="fixed top-3 left-1/2 -translate-x-1/2 z-[99999] flex items-center gap-2 sm:gap-4 px-2 sm:px-4 py-1.5 sm:py-2 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 shadow-lg text-[10px] sm:text-xs opacity-0 -translate-y-10 pointer-events-none transition-all duration-500 w-auto justify-center max-w-[96vw] sm:max-w-none">
    <div class="flex flex-col leading-tight text-center sm:text-left">
        <span id="time" class="font-bold text-gray-900 dark:text-white text-[10px] sm:text-xs"></span>
        <span id="date" class="text-gray-500 dark:text-gray-400 hidden sm:block"></span>
    </div>
    <div class="w-px h-6 sm:h-8 bg-gray-200 dark:bg-white/10 hidden sm:block"></div>
    <div class="flex flex-col leading-tight text-center sm:text-left hidden sm:block">
        <span id="temp" class="font-bold text-pink-600 dark:text-pink-400"></span>
        <span id="city" class="text-gray-500 dark:text-gray-400 text-[10px]"></span>
    </div>
    <div class="w-px h-6 sm:h-8 bg-gray-200 dark:bg-white/10"></div>
    <div class="flex items-center border border-gray-200 dark:border-white/10 rounded-lg sm:rounded-xl overflow-hidden">
        <a href="{{ url('lang/en') }}" class="px-2 sm:px-3 py-1 sm:py-2 text-[10px] sm:text-xs font-bold transition-all {{ app()->getLocale() === 'en' ? 'bg-pink-500 text-white' : 'bg-white/80 dark:bg-gray-900/80 text-gray-700 dark:text-gray-300' }}">EN</a>
        <a href="{{ url('lang/ur') }}" class="px-2 sm:px-3 py-1 sm:py-2 text-[10px] sm:text-xs font-bold transition-all {{ app()->getLocale() === 'ur' ? 'bg-pink-500 text-white' : 'bg-white/80 dark:bg-gray-900/80 text-gray-700 dark:text-gray-300' }}">اردو</a>
    </div>
    <button onclick="toggleTheme()" class="flex h-8 w-8 sm:h-10 sm:w-10 items-center justify-center rounded-lg sm:rounded-xl border border-gray-200 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md shadow-sm transition-all hover:scale-110">
        <svg id="sunIcon" class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-500 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="5" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
        <svg id="moonIcon" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-700 dark:text-gray-300 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
    </button>
</div>

<!-- Background Blobs -->
<div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
    <div class="absolute w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] bg-pink-500/2 dark:bg-pink-500/5 blur-[100px] sm:blur-[120px] top-[-120px] left-[-120px] rounded-full"></div>
    <div class="absolute w-[250px] h-[250px] sm:w-[400px] sm:h-[400px] bg-purple-500/2 dark:bg-[#d6007b]/5 blur-[100px] sm:blur-[120px] bottom-[-120px] right-[-120px] rounded-full"></div>
</div>

<!-- Brand Header -->
<form action="{{ route('logout') }}" method="POST" class="fixed top-4 left-4 z-[99999]">
    @csrf
    <button type="submit" class="p-2.5 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-xl shadow-lg text-gray-400 hover:text-pink-500 hover:bg-pink-500/10 transition-all" title="{{ __('messages.logout') }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
    </button>
</form>

<!-- Notification Bell (fixed) -->
<div id="notifContainer" class="fixed top-4 right-4 z-[99999]">
    <button onclick="toggleNotifDropdown()" class="p-2.5 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-xl shadow-lg text-gray-400 hover:text-pink-500 hover:bg-pink-500/10 transition-all relative">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        <span id="notifBadge" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-white text-[8px] font-bold flex items-center justify-center hidden">0</span>
    </button>
    <!-- Dropdown -->
    <div id="notifDropdown" class="absolute right-0 mt-2 w-80 rounded-2xl bg-white dark:bg-[#111] border border-gray-200 dark:border-white/10 shadow-2xl shadow-black/20 overflow-hidden hidden z-[99999]">
        <div class="p-3 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
            <span class="text-sm font-bold text-gray-900 dark:text-white">Notifications</span>
            <button onclick="markAllNotifRead()" class="text-[10px] font-bold text-pink-500 hover:underline">Mark all read</button>
        </div>
        <div id="notifList" class="max-h-80 overflow-y-auto">
            <div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">Loading...</div>
        </div>
    </div>
</div>

@auth
<div class="relative z-[9999] pt-4 px-4 sm:px-8 max-w-7xl mx-auto flex items-center justify-between">
    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'saleshead' ? route('saleshead.dashboard') : route('deliveryboy.dashboard')) }}" class="flex items-center gap-3 group no-underline">
        <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg shadow-pink-500/20 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
            </svg>
        </div>
        <span class="font-bold text-gray-900 dark:text-white text-lg tracking-tight">{{ __('messages.app_name') }}</span>
    </a>
    <div class="flex items-center gap-2">
        <span class="px-3 py-1.5 rounded-xl bg-pink-500/10 text-pink-500 text-[10px] font-bold uppercase">{{ str_replace('-', ' ', auth()->user()->role) }}</span>
        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-pink-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
    </div>
</div>
@endauth

<!-- Main Content -->
<main class="relative z-10 min-h-screen pb-28 sm:pb-24">
    @if(session('success'))
    <div class="fixed top-20 right-4 z-50 animate-slide-down">
        <div class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm font-medium shadow-lg backdrop-blur-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="fixed top-20 right-4 z-50 animate-slide-down">
        <div class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-rose-500/10 dark:bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-sm font-medium shadow-lg backdrop-blur-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    </div>
    @endif

    <div class="p-4 sm:p-8 max-w-7xl mx-auto">
        @yield('content')
    </div>
</main>

<!-- Floating Nav Buttons -->
@auth
@php
$role = auth()->user()->role;
$navItems = [];

if ($role === 'admin') {
    $navItems = [
        ['route' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => __('messages.dashboard')],
        ['route' => 'admin.members', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => __('messages.members')],
        ['route' => 'admin.reports', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => __('messages.reports')],
        ['route' => 'admin.blocked', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z', 'label' => __('messages.blocked')],
        ['route' => 'admin.activity-logs', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'label' => __('messages.activity')],
    ];
} elseif ($role === 'saleshead') {
    $navItems = [
        ['route' => 'saleshead.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => __('messages.dashboard')],
        ['route' => 'saleshead.reports', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => __('messages.reports')],
    ];
} elseif ($role === 'deliveryboy') {
    $navItems = [
        ['route' => 'deliveryboy.dashboard', 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7', 'label' => __('messages.my_routes')],
        ['route' => 'deliveryboy.routes.create', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6', 'label' => __('messages.new_route')],
    ];
}
@endphp

<div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 flex items-center gap-1 sm:gap-3 px-2 sm:px-4 py-2 sm:py-3 rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-2xl border border-gray-200 dark:border-white/10 shadow-2xl shadow-black/5 max-w-[96vw] sm:max-w-none overflow-x-auto">
    @foreach($navItems as $item)
    <a href="{{ route($item['route']) }}" class="flex flex-col items-center gap-0.5 sm:gap-1 px-2 sm:px-3 py-1.5 sm:py-2 rounded-xl transition-all hover:bg-pink-500/10 {{ request()->routeIs($item['route']) ? 'bg-pink-500/15 text-pink-500' : 'text-gray-500 dark:text-gray-400 hover:text-pink-500' }} no-underline group flex-shrink-0">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ request()->routeIs($item['route']) ? 'text-pink-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
        <span class="text-[8px] sm:text-[9px] font-bold uppercase tracking-wide whitespace-nowrap">{{ $item['label'] }}</span>
    </a>
    @endforeach
    <a href="{{ route('chat') }}" class="flex flex-col items-center gap-0.5 sm:gap-1 px-2 sm:px-3 py-1.5 sm:py-2 rounded-xl transition-all hover:bg-pink-500/10 {{ request()->routeIs('chat*') ? 'bg-pink-500/15 text-pink-500' : 'text-gray-500 dark:text-gray-400 hover:text-pink-500' }} no-underline group relative flex-shrink-0">
        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <span class="text-[8px] sm:text-[9px] font-bold uppercase tracking-wide">{{ __('messages.chat') }}</span>
        <span id="chatBadge" class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 rounded-full text-white text-[8px] font-bold flex items-center justify-center hidden">0</span>
    </a>
</div>
@endauth

<script>
// Theme
const sunIcon = document.getElementById('sunIcon');
const moonIcon = document.getElementById('moonIcon');
function applyTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    if(sunIcon) sunIcon.classList.toggle('hidden', !isDark);
    if(moonIcon) moonIcon.classList.toggle('hidden', isDark);
}
function toggleTheme() {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    applyTheme();
}
applyTheme();

// Time + Date
function updateTime() {
    const now = new Date();
    const timeEl = document.getElementById('time');
    const dateEl = document.getElementById('date');
    if(timeEl) timeEl.innerText = now.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit',hour12:true});
    if(dateEl) dateEl.innerText = now.toLocaleDateString([], {weekday:'short',day:'numeric',month:'short'});
}
setInterval(updateTime, 1000);
updateTime();

// Weather
async function getWeather() {
    try {
        const res = await fetch("https://api.open-meteo.com/v1/forecast?latitude=31.5204&longitude=74.3587&current_weather=true");
        const data = await res.json();
        const tempEl = document.getElementById('temp');
        const cityEl = document.getElementById('city');
        if(tempEl) tempEl.innerText = data.current_weather.temperature + "°C";
        if(cityEl) cityEl.innerText = "Lahore";
    } catch {
        const tempEl = document.getElementById('temp');
        const cityEl = document.getElementById('city');
        if(tempEl) tempEl.innerText = "--°C";
        if(cityEl) cityEl.innerText = "Offline";
    }
}
getWeather();

// Show system info bar
setTimeout(function(){
    var si = document.getElementById('system-info');
    if(si){si.classList.remove('opacity-0','-translate-y-10','pointer-events-none');si.classList.add('opacity-100','translate-y-0','pointer-events-auto');}
}, 500);

// Browser notifications
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}

// Notifications
const notifContainer = document.getElementById('notifContainer');
const notifBadge = document.getElementById('notifBadge');
const notifDropdown = document.getElementById('notifDropdown');
const notifList = document.getElementById('notifList');
let notifOpen = false;
let prevCount = 0;

function showBrowserNotif(title, body) {
    if (!('Notification' in window) || Notification.permission !== 'granted') return;
    try {
        const n = new Notification(title, { body, icon: '/favicon.ico', tag: 'delivery-notif' });
        n.onclick = () => { window.focus(); n.close(); };
    } catch(e) {}
}

function toggleNotifDropdown() {
    notifOpen = !notifOpen;
    notifDropdown.classList.toggle('hidden', !notifOpen);
    if (notifOpen) fetchNotifications();
}

function fetchNotifCount() {
    fetch('/notifications/unread-count')
        .then(r => r.json())
        .then(d => {
            const count = d.count;
            if (count > 0) {
                notifBadge.classList.remove('hidden');
                notifBadge.textContent = count > 9 ? '9+' : count;
                if (count > prevCount && prevCount > 0) {
                    // Fetch latest notification for detailed browser notification
                    fetch('/notifications?limit=1')
                        .then(r => r.json())
                        .then(notifs => {
                            if (notifs.length > 0) {
                                const data = notifs[0].data || {};
                                showBrowserNotif(data.title || 'Notification', data.message || '');
                            }
                        }).catch(() => {});
                }
                prevCount = count;
            } else {
                notifBadge.classList.add('hidden');
                prevCount = 0;
            }
        }).catch(() => {});
}

function fetchNotifications() {
    notifList.innerHTML = '<div class="p-4 text-center text-sm text-gray-500">Loading...</div>';
    fetch('/notifications')
        .then(r => r.json())
        .then(notifs => {
            if (notifs.length === 0) {
                notifList.innerHTML = '<div class="p-4 text-center text-sm text-gray-500">No notifications</div>';
                return;
            }
            let html = '';
            notifs.forEach(n => {
                const data = n.data || {};
                const isUnread = n.read_at === null;
                html += `<div class="p-3 border-b border-gray-100 dark:border-white/5 flex items-start gap-3 ${isUnread ? 'bg-pink-500/5' : ''}">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">${data.title || 'Notification'}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">${data.message || ''}</div>
                        <div class="text-[10px] text-gray-400 mt-1">${new Date(n.created_at).toLocaleString()}</div>
                    </div>
                    ${isUnread ? `<button onclick="markNotifRead('${n.id}')" class="text-[10px] text-pink-500 hover:underline flex-shrink-0">Read</button>` : ''}
                </div>`;
            });
            notifList.innerHTML = html;
        }).catch(() => {
            notifList.innerHTML = '<div class="p-4 text-center text-sm text-red-500">Failed to load</div>';
        });
}

function markNotifRead(id) {
    fetch('/notifications/' + id + '/read', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
        .then(() => { fetchNotifCount(); fetchNotifications(); }).catch(() => {});
}

function markAllNotifRead() {
    fetch('/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
        .then(() => { fetchNotifCount(); fetchNotifications(); }).catch(() => {});
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (notifOpen && !notifContainer.contains(e.target) && !notifDropdown.contains(e.target)) {
        notifOpen = false;
        notifDropdown.classList.add('hidden');
    }
});

// Poll for notification count every 10s
fetchNotifCount();
setInterval(fetchNotifCount, 10000);
</script>
@yield('scripts')
</body>
</html>
