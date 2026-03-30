<div class="min-h-screen bg-gray-50 text-gray-900 py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        @if($isFinished)
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h2 class="text-3xl font-light text-gray-900 mb-2">Thank You!</h2>
                <p class="text-gray-500">Your response has been recorded successfully.</p>
            </div>
        @else
            <div class="mb-8 border-b border-gray-100 pb-6">
                <h1 class="text-3xl font-light tracking-tight">{{ $survey->title }}</h1>
                @if($survey->description)
                    <p class="mt-2 text-gray-500">{{ $survey->description }}</p>
                @endif
            </div>

            @php
                $sections = is_array($survey->schema) ? $survey->schema : [];
                $currentSection = $sections[$currentSectionIndex] ?? null;
            @endphp

            @if($currentSection)
                <div class="mb-8">
                    <h2 class="text-2xl font-medium mb-6 text-gray-800">{{ $currentSection['section_name'] }}</h2>
                    
                    @if(isset($currentSection['section_type']) && $currentSection['section_type'] === 'intro')
                        <div class="mb-6 bg-blue-50 text-blue-800 p-4 rounded-lg border border-blue-100">
                            <label class="flex items-center space-x-3 cursor-pointer p-2">
                                <input type="checkbox" wire:model.live="consentGiven" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span>I agree to the terms and explicitly consent to this survey processing my data in accordance with PDP 2022.</span>
                            </label>
                        </div>
                    @endif

                    <div class="space-y-8">
                        @foreach($currentSection['questions'] ?? [] as $qIndex => $question)
                            <div class="bg-gray-50/50 p-6 rounded-lg border border-gray-100">
                                <label class="block text-lg font-medium text-gray-900 mb-4">
                                    {{ $question['question_text'] ?? '' }}
                                    @if(isset($question['is_required']) && $question['is_required']) <span class="text-red-500">*</span> @endif
                                </label>

                                @if(isset($question['type']) && $question['type'] === 'text')
                                    <textarea wire:model="answers.{{ $question['question_text'] }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm" rows="3" placeholder="Enter your answer..."></textarea>
                                @elseif(isset($question['type']) && in_array($question['type'], ['single_choice', 'likert', 'nps']))
                                    <div class="space-y-3">
                                        @foreach($question['options'] ?? [] as $opt)
                                            @php $optValue = $opt['option_value'] ?? $opt['option_text'] ?? ''; @endphp
                                            <label class="flex items-center space-x-3 p-3 bg-white border border-gray-200 rounded-md hover:border-black cursor-pointer transition-colors shadow-sm">
                                                <input type="radio" wire:model="answers.{{ $question['question_text'] }}" value="{{ $optValue }}" class="w-4 h-4 text-black border-gray-300 focus:ring-black">
                                                <span class="text-gray-700">{{ $opt['option_text'] ?? '' }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif(isset($question['type']) && $question['type'] === 'multiple_choice')
                                    <div class="space-y-3">
                                        @foreach($question['options'] ?? [] as $opt)
                                            @php $optValue = $opt['option_value'] ?? $opt['option_text'] ?? ''; @endphp
                                            <label class="flex items-center space-x-3 p-3 bg-white border border-gray-200 rounded-md hover:border-black cursor-pointer transition-colors shadow-sm">
                                                <input type="checkbox" wire:model="answers.{{ $question['question_text'] }}.{{ Str::slug($optValue) }}" value="{{ $optValue }}" class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black">
                                                <span class="text-gray-700">{{ $opt['option_text'] ?? '' }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-between items-center pt-6 border-t border-gray-100">
                    <div>
                        @if($currentSectionIndex > 0)
                            <button wire:click="previousSection" type="button" class="px-6 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors">
                                Previous Section
                            </button>
                        @endif
                    </div>
                    
                    <div>
                        @if($currentSectionIndex < count($sections) - 1)
                            <button wire:click="nextSection" type="button" class="px-6 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors">
                                Continue
                            </button>
                        @else
                            <button wire:click="submit" @if(isset($currentSection['section_type']) && $currentSection['section_type'] === 'intro' && !$consentGiven) disabled @endif type="button" class="px-6 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-black hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors">
                                Submit Survey
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
