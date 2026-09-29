<!-- Notification Bell Icon -->
<div class="position-relative" x-data="{
    open: false,
    notifications: [],
    unreadCount: 0,
    loading: false,

    init() {
        this.loadUnreadCount();
        setInterval(() => this.loadUnreadCount(), 30000);
    },

    loadUnreadCount() {
        fetch('/notifications/count')
            .then(response => response.json())
            .then(data => {
                this.unreadCount = data.count;
            })
            .catch(console.error);
    },

    loadRecentNotifications() {
        this.loading = true;
        fetch('/notifications/recent')
            .then(response => response.json())
            .then(data => {
                console.log('Notifications received:', data.notifications);
                this.notifications = data.notifications;
                this.loading = false;
            })
            .catch(error => {
                console.error(error);
                this.loading = false;
            });
    },

    markAllAsRead() {
        fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.unreadCount = 0;
                    this.notifications = this.notifications.map(n => ({
                        ...n,
                        read_at: new Date()
                    }));
                }
            })
            .catch(console.error);
    },

    openNotification(notification) {
        if (!notification.read_at) {
            fetch('/notifications/' + notification.id + '/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notification.read_at = new Date();
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                })
                .catch(console.error);
        }

        if (notification.data && notification.data.action_url) {
            window.location.href = notification.data.action_url;
        }
    },

    formatTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 1) return 'Baru saja';
        if (diffMins < 60) return diffMins + ' menit lalu';
        if (diffHours < 24) return diffHours + ' jam lalu';
        if (diffDays < 7) return diffDays + ' hari lalu';

        return date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }
}">
    <!-- Bell Icon Button -->
    <button @click="open = !open; if(open) loadRecentNotifications()"
        class="nav-link position-relative border-0 bg-transparent" data-bs-toggle="tooltip" title="Notifikasi">
        <i class="fas fa-bell"></i>

        <!-- Unread Count Badge -->
        <span x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount"
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
            style="font-size: 0.6rem; margin-left: -8px;">
        </span>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="position-absolute end-0 mt-2 bg-white rounded shadow border" style="width: 320px; z-index: 1050;">

        <!-- Header -->
        <div class="px-3 py-3 border-bottom bg-light rounded-top">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark">Notifikasi</h6>
                <div class="d-flex gap-2">
                    <button @click="markAllAsRead()" class="btn btn-link btn-sm text-primary p-0">
                        Tandai Semua Dibaca
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="px-3 py-4 text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <p class="small text-muted mt-2 mb-0">Memuat notifikasi...</p>
        </div>

        <!-- Notifications List -->
        <div x-show="!loading && notifications.length > 0" class="overflow-auto" style="max-height: 400px;">
            <template x-for="notification in notifications" :key="notification.id">
                <div class="px-3 py-3 border-bottom" :class="{ 'bg-light': !notification.read_at }"
                    @click="openNotification(notification)" style="cursor: pointer;">
                    <div class="d-flex align-items-start">
                        <!-- Icon -->
                        <div class="flex-shrink-0 me-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 24px; height: 24px;"
                                :class="{
                                    'text-success bg-success': notification.data && notification.data
                                        .icon === 'check-circle',
                                    'text-danger bg-danger': notification.data && notification.data.icon === 'x-circle',
                                    'text-info bg-info': notification.data && notification.data.icon === 'truck',
                                    'text-warning bg-warning': notification.data && notification.data
                                        .icon === 'package',
                                    'text-secondary bg-secondary': notification.data && notification.data
                                        .icon === 'clock'
                                }">
                                <i class="fas fa-circle" style="font-size: 6px;"></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-grow-1 min-w-0">
                            <p class="small fw-bold mb-1 text-dark"
                                x-text="notification.data && notification.data.title ? notification.data.title : 'Notifikasi'">
                            </p>
                            <p class="small text-muted mb-1"
                                x-text="notification.data && notification.data.message ? notification.data.message : ''">
                            </p>
                            <p class="small text-muted mb-0" x-text="formatTime(notification.created_at)"></p>
                        </div>

                        <!-- Unread indicator -->
                        <div x-show="!notification.read_at" class="flex-shrink-0">
                            <div class="bg-primary rounded-circle" style="width: 8px; height: 8px;"></div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && notifications.length === 0" class="px-3 py-4 text-center">
            <i class="fas fa-bell-slash text-muted mb-2" style="font-size: 2rem;"></i>
            <p class="small text-muted mb-0">Tidak ada notifikasi</p>
        </div>

        <!-- Footer -->
        <div class="px-3 py-3 bg-light border-top rounded-bottom">
            <a href="{{ route('user.orders.index') }}"
                class="d-block text-center small text-primary text-decoration-none">
                Lihat Pesanan Saya
            </a>
        </div>
    </div>
</div>
