<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailRegister;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Queueable;
    public $tries = 1;
     protected $emailData;

    /**
     * Create a new job instance.
     */
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       Log::info('Processing SendEmailJob for: ' . $this->emailData['to']);
       Mail::to($this->emailData['to'])->send(new SendEmailRegister($this->emailData));
    }
}