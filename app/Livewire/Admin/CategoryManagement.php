<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class CategoryManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public int|null $editingId = null;
    public $name = '';
    public $description = '';
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
    ];

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->paginate(10);

        return view('livewire.admin.category-management', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $this->reset(['editingId', 'name', 'description']);
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $category = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $category = Category::findOrFail($this->editingId);
            $category->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            $this->dispatch('notify', message: 'Category updated successfully!');
        } else {
            Category::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            $this->dispatch('notify', message: 'Category created successfully!');
        }

        $this->showModal = false;
        $this->resetPage();
    }

    public function delete(int $id)
    {
        Category::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Category deleted successfully!');
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['editingId', 'name', 'description']);
    }
}
