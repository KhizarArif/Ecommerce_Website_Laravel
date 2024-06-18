<?php

namespace App\Jobs;

use App\Mail\OrderEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CompleteOrderPlaceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mailData;
    /**
     * Create a new job instance.
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Job started with data: ', $this->mailData);

        Mail::to("khizararif201@gmail.com")->send(new OrderEmail($this->mailData));

        Log::info('Mail sent successfully.');
        // dd("khizar");
        // dd($this->mailData);
        // Mail::to("khizararif201@gmail.com")->send(new OrderEmail($this->mailData));
    }
}
