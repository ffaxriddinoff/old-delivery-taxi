<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Services\MessageService;


class OrderMessageJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone, $text) {
        $this->phone = $phone;
        $this->text = $text;
        $this->sms = new MessageService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $this->sms->sendMessage($this->phone, $this->text);
    }
}
