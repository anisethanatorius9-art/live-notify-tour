<?php

namespace App\Livewire;

use Livewire\Component;

class HelpPage extends Component
{
    public string $search = '';
    public ?string $selectedCategory = null;

    protected array $helpCategories = [
        ['name' => 'Account Help', 'description' => 'Login, registration, password reset', 'icon' => 'user'],
        ['name' => 'Tourism Guide', 'description' => 'Locations, bookings, tours', 'icon' => 'map-pin'],
        ['name' => 'Payments', 'description' => 'Mobile money & transactions', 'icon' => 'credit-card'],
        ['name' => 'Notifications', 'description' => 'Alerts and updates', 'icon' => 'bell'],
        ['name' => 'System Help', 'description' => 'Settings, errors, troubleshooting', 'icon' => 'cog-6-tooth'],
    ];

    protected array $faqs = [
        [
            'question' => 'I forgot my password. What should I do?',
            'answer' => 'Click "Forgot Password" on the login page and follow the steps to reset it.',
            'category' => 'Account Help',
        ],
        [
            'question' => 'My payment failed. What can I do?',
            'answer' => 'Ensure you have enough balance and a stable network connection.',
            'category' => 'Payments',
        ],
        [
            'question' => 'I am not receiving notifications.',
            'answer' => 'Check your notification settings and make sure they are enabled.',
            'category' => 'Notifications',
        ],
        [
            'question' => 'How do I update my profile?',
            'answer' => 'Go to Settings, then Profile, and save your changes.',
            'category' => 'System Help',
        ],
    ];

    public function selectCategory(string $category): void
    {
        $this->selectedCategory = $this->selectedCategory === $category ? null : $category;
    }

    public function getFilteredFaqsProperty()
    {
        return collect($this->faqs)
            ->when($this->search, function ($items) {
                return $items->filter(function ($faq) {
                    return str_contains(strtolower($faq['question']), strtolower($this->search))
                        || str_contains(strtolower($faq['answer']), strtolower($this->search));
                });
            })
            ->when($this->selectedCategory, function ($items) {
                return $items->where('category', $this->selectedCategory);
            })
            ->values();
    }

    public function getCategoriesProperty()
    {
        return collect($this->helpCategories);
    }

    public function render()
    {
        return view('livewire.help-page');
    }
}
