<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $stockRequest;
    protected $action; // created, approved, rejected

    public function __construct($stockRequest, $action)
    {
        $this->stockRequest = $stockRequest;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        //return ['database', 'mail']; // Send via database and email
        return ['database']; // Send via database only
    }

    public function toMail($notifiable)
    {
        $message = "Stock Request #{$this->stockRequest->id} has been {$this->action}.";

        return (new MailMessage)
            ->subject("Stock Request {$this->action}")
            ->line($message)
            ->action('View Request', url('/stock-requests/' . $this->stockRequest->id));
    }

    public function toArray($notifiable)
    {
        return [
            'stock_request_id' => $this->stockRequest->id,
            'action' => $this->action,
            'message' => "Stock Request #{$this->stockRequest->id} has been {$this->action}.",
        ];
    }
}
