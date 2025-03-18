<?php

namespace App\Jobs;

use App\Models\StockRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\StockRequestApproval;
use App\Models\SystemSetting;

class SendStockRequestApprovalEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $stockRequest;
    public function __construct(StockRequest $stockRequest)
    {
        $this->stockRequest = $stockRequest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $settings = SystemSetting::first();
        $approverEmail = $settings->notification_email;
        Mail::to($approverEmail)->send(new StockRequestApproval($this->stockRequest));
    }
}
