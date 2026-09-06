<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $newStatus;
    public $note;

    public function __construct(Order $order, string $newStatus, ?string $note = null)
    {
        $this->order = $order;
        $this->newStatus = $newStatus;
        $this->note = $note;
    }

    public function envelope(): Envelope
    {
        $statusLabels = [
            'confirmed'  => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipping'   => 'Đang giao hàng',
            'delivered'  => 'Đã giao hàng',
            'cancelled'  => 'Đã hủy',
        ];

        $label = $statusLabels[$this->newStatus] ?? $this->newStatus;

        return new Envelope(
            subject: '[Orlis] Đơn hàng #' . $this->order->order_code . ' — ' . $label,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-updated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
