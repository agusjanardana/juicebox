<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\SendEmailJob;

class DispatchWelcomeEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:welcome {emailTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing Purpose for Queue Email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $emailTo = $this->argument('emailTo');
        $emailTo = [
            'to' => $emailTo
            ];

        SendEmailJob::dispatch($emailTo);

        $this->info('Welcome email job dispatched.');
        return 0;
    }
}