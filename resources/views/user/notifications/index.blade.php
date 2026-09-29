@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-800">Notifikasi</h1>
                    <div class="flex space-x-2">
                        <button onclick="markAllAsRead()" class="text-blue-600 hover:text-blue-800 text-sm">
                            Tandai Semua Dibaca
                        </button>
                        <button onclick="clearReadNotifications()" class="text-gray-600 hover:text-gray-800 text-sm">
                            Hapus Yang Sudah Dibaca
                        </button>
                    </div>
                </div>

                <!-- Notifications List -->
                <div class="divide-y divide-gray-200">
                    @forelse($notifications as $notification)
                        <div class="px-6 py-4 hover:bg-gray-50 {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50' }}"
                            data-notification-id="{{ $notification->id }}">
                            <div class="flex items-start space-x-3">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    @php
                                        $iconClass = match ($notification->data['icon'] ?? 'info') {
                                            'check-circle' => 'text-green-500',
                                            'x-circle' => 'text-red-500',
                                            'truck' => 'text-blue-500',
                                            'package' => 'text-purple-500',
                                            'clock' => 'text-yellow-500',
                                            'settings' => 'text-gray-500',
                                            default => 'text-blue-500',
                                        };
                                    @endphp
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center {{ $iconClass }} bg-opacity-20">
                                        @switch($notification->data['icon'] ?? 'info')
                                            @case('check-circle')
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @break

                                            @case('x-circle')
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @break

                                            @case('truck')
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z">
                                                    </path>
                                                    <path
                                                        d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707L16 7.586A1 1 0 0015.414 7H14z">
                                                    </path>
                                                </svg>
                                            @break

                                            @default
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                        @endswitch
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium text-gray-900">
                                            {{ $notification->data['title'] ?? 'Notifikasi' }}
                                        </h3>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                            @if (!$notification->read_at)
                                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>

                                    @if (isset($notification->data['admin_notes']) && $notification->data['admin_notes'])
                                        <p class="text-xs text-gray-500 mt-2 italic">
                                            Catatan Admin: {{ $notification->data['admin_notes'] }}
                                        </p>
                                    @endif

                                    <!-- Actions -->
                                    <div class="mt-3 flex items-center space-x-3">
                                        @if (isset($notification->data['action_url']))
                                            <a href="{{ $notification->data['action_url'] }}"
                                                class="text-blue-600 hover:text-blue-800 text-sm"
                                                onclick="markNotificationAsRead('{{ $notification->id }}')">
                                                Lihat Detail
                                            </a>
                                        @endif

                                        @if (!$notification->read_at)
                                            <button onclick="markNotificationAsRead('{{ $notification->id }}')"
                                                class="text-gray-600 hover:text-gray-800 text-sm">
                                                Tandai Dibaca
                                            </button>
                                        @endif

                                        <button onclick="deleteNotification('{{ $notification->id }}')"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                            <div class="px-6 py-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-5 5v-5zM9 17H4l5 5v-5zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada notifikasi</h3>
                                <p class="mt-1 text-sm text-gray-500">Anda belum memiliki notifikasi apapun.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if ($notifications->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <script>
            function markNotificationAsRead(notificationId) {
                fetch(`/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const element = document.querySelector(`[data-notification-id="${notificationId}"]`);
                            element.classList.remove('bg-blue-50');
                            element.classList.add('opacity-75');
                            const unreadDot = element.querySelector('.bg-blue-500');
                            if (unreadDot) unreadDot.remove();
                        }
                    });
            }

            function markAllAsRead() {
                fetch('/notifications/read-all', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }

            function clearReadNotifications() {
                fetch('/notifications/clear-read', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }

            function deleteNotification(notificationId) {
                if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
                    fetch(`/notifications/${notificationId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.querySelector(`[data-notification-id="${notificationId}"]`).remove();
                            }
                        });
                }
            }
        </script>
    @endsection
