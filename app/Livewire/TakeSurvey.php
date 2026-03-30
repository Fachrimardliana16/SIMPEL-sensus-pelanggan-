<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Survey;
use App\Models\SurveyResponse;

class TakeSurvey extends Component
{
    public Survey $survey;
    public array $answers = [];
    public int $currentSectionIndex = 0;
    public bool $consentGiven = false;
    public bool $isFinished = false;

    public function mount($slug)
    {
        $this->survey = Survey::where('slug', $slug)->where('status', 'published')->firstOrFail();
    }

    public function nextSection()
    {
        $this->currentSectionIndex++;
    }

    public function previousSection()
    {
        $this->currentSectionIndex--;
    }

    public function submit()
    {
        SurveyResponse::create([
            'survey_id' => $this->survey->id,
            'session_id' => session()->getId(),
            'ip_address' => \Illuminate\Support\Facades\Request::ip(),
            'user_agent' => \Illuminate\Support\Facades\Request::userAgent(),
            'geolocation' => null, // Placeholder for Geolocation logic
            'status' => 'completed',
            'started_at' => now(),
            'completed_at' => now(),
            'consent_given' => $this->consentGiven,
            'answers' => $this->answers,
        ]);

        $this->isFinished = true;
    }

    public function render()
    {
        return view('livewire.take-survey')
            ->layout('components.layouts.app');
    }
}
