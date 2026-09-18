<div class="topbar">
    {{-- Toggle Button --}}
    <button class="topbar-toggler" id="sidebarToggler" type="button">
        <i class="fa-solid fa-bars"></i>
    </button>

    {{-- Right side --}}
    <div class="topbar-right">
        {{-- Notifications --}}
        @php
            $unread = App\Models\Notification::where('user_id', auth()->id())
                ->where('is_read', 0)->count();
            $notifs = App\Models\Notification::where('user_id', auth()->id())
                ->latest()->limit(8)->get();
        @endphp

        <div class="dropdown">
            <button class="notif-btn" data-bs-toggle="dropdown" type="button">
                <i class="fa-solid fa-bell"></i>
                @if($unread > 0)
                    <span class="notif-badge">{{ $unread > 9 ? '9+' : $unread }}</span>
                @endif
            </button>

            <div class="dropdown-menu dropdown-menu-end" style="width: 340px; padding: 0;">
                <div class="p-3 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid var(--border);">
                    <span style="font-weight: 700; font-size: 14px;">Notifikasi</span>
                    @if($unread > 0)
                        <form action="{{ route('notifications.read-all') }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-ghost" style="font-size: 11px;" type="submit">Tandai semua dibaca</button>
                        </form>
                    @endif
                </div>
                <div style="max-height: 300px; overflow-y: auto;">
                    @forelse($notifs as $notif)
                        <a href="{{ route('notifications.read', $notif->id) }}"
                           class="dropdown-item"
                           style="white-space: normal; padding: 12px; border-bottom: 1px solid var(--border-light);">
                            <div style="font-weight: 600; font-size: 13px;">{{ $notif->title }}</div>
                            <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">{{ Str::limit($notif->message, 60) }}</div>
                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">{{ $notif->created_at->diffForHumans() }}</div>
                        </a>
                    @empty
                        <div class="empty-state" style="padding: 24px;">
                            <p style="font-size: 13px;">Tidak ada notifikasi</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- User Dropdown --}}
        <div class="dropdown">
            <div class="topbar-user" data-bs-toggle="dropdown" role="button" tabindex="0">
                <div class="topbar-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="topbar-user-info d-none d-md-block">
                    <div class="topbar-user-name">{{ auth()->user()->name }}</div>
                    <div class="topbar-user-role">{{ auth()->user()->role_label }}</div>
                </div>
                <i class="fa-solid fa-chevron-down d-none d-md-block" style="font-size: 10px; color: var(--text-muted);"></i>
            </div>

            <div class="dropdown-menu dropdown-menu-end" style="min-width: 180px;">
                <a href="{{ route('profile.show') }}" class="dropdown-item">
                    <i class="fa-solid fa-user"></i> Profil
                </a>
                <div style="border-top: 1px solid var(--border); margin: 4px 0;"></div>
                <a href="{{ route('logout') }}" class="dropdown-item" style="color: var(--danger);">
                    <i class="fa-solid fa-right-from-bracket" style="color: var(--danger);"></i> Log Out
                </a>
            </div>
        </div>
    </div>
</div>
