<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Pesanan;

class PaymentRejected extends Notification
{
    use Queueable;

    protected $pesanan;
    protected $adminNotes;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pesanan $pesanan, $adminNotes = null)
    {
        $this->pesanan = $pesanan;
        $this->adminNotes = $adminNotes;
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
        return [
            'title' => 'Pembayaran Ditolak',
            'message' => "Pembayaran untuk pesanan #{$this->pesanan->idPesanan} ditolak. Silakan unggah bukti pembayaran yang valid atau hubungi customer service.",
            'pesanan_id' => $this->pesanan->idPesanan,
            'total_harga' => $this->pesanan->total_harga,
            'admin_notes' => $this->adminNotes,
            'action_url' => route('user.orders.show', $this->pesanan->idPesanan),
            'icon' => 'x-circle',
            'type' => 'payment_rejected'
        ];
    }
}
