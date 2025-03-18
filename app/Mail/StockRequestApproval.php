<?php

namespace App\Mail;

use App\Models\StockRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StockRequestApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $stockRequest;

    public function __construct(StockRequest $stockRequest)
    {
        $this->stockRequest = $stockRequest;
    }

    public function build()
    {
        return $this->subject('Stock Request Approval Required')
                    ->view('emails.stock_request_approval');
    }
}