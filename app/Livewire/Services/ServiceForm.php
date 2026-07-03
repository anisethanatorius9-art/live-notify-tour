<?php

namespace App\Livewire\Services;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Service;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ServiceForm extends Component
{
    use WithFileUploads;
    public ?Service $service = null;
    public string $name = '';
    public string $description = '';
    public string $price = '';
    public ?int $location_id = null;
    public string $category = '';
    public string $customCategory = '';
    public string $image_url = '';
    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile|null */
    public $image = null;
    public string $status = 'active';

    /** @var \Illuminate\Database\Eloquent\Collection|array */
    public $locations = [];
    /** @var \Illuminate\Database\Eloquent\Collection|array */
    public $categories = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:2000',
        'price' => 'required|numeric|min:0',
        'location_id' => 'required|exists:locations,id',
        'category' => 'required_without:customCategory|string|max:255',
        'customCategory' => 'nullable|string|max:255',
        'image_url' => 'nullable|url|max:1000',
        'image' => 'nullable|image|max:10240',
        'status' => 'required|in:active,inactive',
    ];

    public function mount(?Service $service = null)
    {
        $this->locations = Location::pluck('name', 'id')->toArray();
        $this->categories = Category::pluck('name', 'name')->toArray();

        if ($service) {
            $this->service = $service;
            $this->name = $service->name;
            $this->description = $service->description;
            $this->price = $service->price;
            $this->location_id = $service->location_id;
            $this->category = $service->category;
            $this->image_url = $service->image_url;
            $this->status = $service->status;
        }
    }

    public function saveService()
    {
        $this->validate();

        $category = $this->customCategory ?: $this->category;

        if (! $category) {
            $this->addError('category', __('Please choose or enter a category.'));
            return;
        }

        // If an image file was uploaded, store it and set `image_url`
        if ($this->image) {
            // Attempt to resize uploaded image using GD and store optimized version
            try {
                $tmpPath = $this->image->getRealPath();
                $imgContents = file_get_contents($tmpPath);
                $src = @imagecreatefromstring($imgContents);

                if ($src !== false) {
                    $maxWidth = 1200;
                    $width = imagesx($src);
                    $height = imagesy($src);
                    $ratio = $width > $maxWidth ? ($maxWidth / $width) : 1;
                    $newW = (int) max(1, floor($width * $ratio));
                    $newH = (int) max(1, floor($height * $ratio));

                    $dst = imagecreatetruecolor($newW, $newH);
                    // Preserve transparency for PNG
                    $ext = strtolower(pathinfo($this->image->getClientOriginalName(), PATHINFO_EXTENSION) ?: 'jpg');
                    if ($ext === 'png') {
                        imagealphablending($dst, false);
                        imagesavealpha($dst, true);
                    }

                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);

                    ob_start();
                    if ($ext === 'png') {
                        imagepng($dst, null, 8);
                    } else {
                        imagejpeg($dst, null, 85);
                    }
                    $contents = ob_get_clean();

                    imagedestroy($src);
                    imagedestroy($dst);

                    $filename = 'services/' . uniqid() . '.' . ($ext === 'png' ? 'png' : 'jpg');
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $contents);
                    $this->image_url = \Illuminate\Support\Facades\Storage::url($filename);
                } else {
                    // fallback to store original
                    $path = $this->image->store('services', 'public');
                    $this->image_url = \Illuminate\Support\Facades\Storage::url($path);
                }
            } catch (\Throwable $e) {
                // On any failure, store original file
                try {
                    $path = $this->image->store('services', 'public');
                    $this->image_url = \Illuminate\Support\Facades\Storage::url($path);
                } catch (\Throwable $_) {
                    // Ignore store errors
                }
            }
        }

        if ($this->service) {
            if ($this->service->provider_id !== Auth::id() && Auth::user()->role !== 'admin') {
                abort(403);
            }

            $this->service->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'location_id' => $this->location_id,
                'category' => $category,
                'image_url' => $this->image_url,
                'status' => $this->status,
            ]);

            session()->flash('success', __('Service updated successfully.'));
        } else {
            Service::create([
                'provider_id' => Auth::id(),
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'location_id' => $this->location_id,
                'category' => $category,
                'image_url' => $this->image_url,
                'status' => $this->status,
            ]);

            session()->flash('success', __('Service created successfully.'));
        }

        return redirect()->route('services.index');
    }

    public function render()
    {
        return view('livewire.services.form');
    }
}
