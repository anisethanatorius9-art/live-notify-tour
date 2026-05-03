<?php

namespace App\Livewire\Categories;

use App\Models\Service;
use Livewire\Component;

class TouristIndex extends Component
{
    public function getCategories()
    {
        return Service::distinct()
            ->pluck('category')
            ->filter()
            ->values()
            ->map(fn($category) => [
                'name' => $category,
                'count' => Service::where('category', $category)->count(),
                'icon' => $this->getCategoryIcon($category),
            ]);
    }

    private function getCategoryIcon($category)
    {
        return match($category) {
            'Tour' => '🧳',
            'Activity' => '⛹️',
            'Accommodation' => '🏨',
            'Transport' => '🚗',
            'Dining' => '🍽️',
            'Entertainment' => '🎭',
            default => '✨'
        };
    }

    public function render()
    {
        return view('livewire.categories.tourist-index', [
            'categories' => $this->getCategories(),
        ]);
    }
}
