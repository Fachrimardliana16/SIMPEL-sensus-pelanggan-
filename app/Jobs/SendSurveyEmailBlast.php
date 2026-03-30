<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Survey;

class SendSurveyEmailBlast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Survey $survey,
        public array $emailList
    ) {}

    public function handle(): void
    {
        foreach ($this->emailList as $email) {
            Mail::raw("Please take our survey: {$this->survey->title}\nLink: " . route('survey.show', $this->survey->slug), function ($message) use ($email) {
                $message->to($email)
                        ->subject("Survey Invitation: {$this->survey->title}");
            });
        }
    }
}
