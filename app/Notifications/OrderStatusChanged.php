<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Pesanan;

class OrderStatusChanged extends Notification
{
    use Queueable;

    protected $pesanan;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pesanan $pesanan, $oldStatus, $newStatus)
    {
        $this->pesanan = $pesanan;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusMessages = [
            'waiting_payment' => 'Menunggu pembayaran',
            'waiting_confirmation' => 'Menunggu konfirmasi pembayaran',
            'confirmed' => 'Pembayaran dikonfirmasi',
            'processing' => 'Sedang diproses',
            'shipped' => 'Dalam pengiriman',
            'delivered' => 'Telah diterima',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'payment_rejected' => 'Pembayaran ditolak'
        ];

        $message = "Status pesanan #{$this->pesanan->idPesanan} telah diperbarui menjadi: {$statusMessages[$this->newStatus]}";

        // Add shipping number to message if available and status is shipped
        if ($this->newStatus === 'shipped' && $this->pesanan->nomor_resi) {
            $message .= " dengan nomor resi: {$this->pesanan->nomor_resi}";
        }

        return [
            'title' => 'Status Pesanan Diperbarui',
            'message' => $message,
            'pesanan_id' => $this->pesanan->idPesanan,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'nomor_resi' => $this->pesanan->nomor_resi,
            'total_harga' => $this->pesanan->total_harga,
            'action_url' => route('user.orders.show', $this->pesanan->idPesanan),
            'icon' => $this->getStatusIcon($this->newStatus),
            'type' => 'order_status'
        ];
    }

    /**
     * Get icon based on order status.
     */
    private function getStatusIcon($status)
    {
        $icons = [
            'waiting_payment' => 'clock',
            'waiting_confirmation' => 'clock',
            'confirmed' => 'check-circle',
            'processing' => 'settings',
            'shipped' => 'truck',
            'delivered' => 'package',
            'cancelled' => 'x-circle',
            'payment_rejected' => 'x-circle'
        ];

        return $icons[$status] ?? 'info';
    }
}
