<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Car;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;

class CreateCar extends Component
{
    use WithFileUploads;
    public $image;
    public $brand;
    public $model;
    public $year;
    public $daily_rent;
    public $description;
    public $status = 'available';

    protected function rules()
    {
        return [
            'image'       => 'required|image|max:102400', // 100MB Max
            'brand'       => 'required|string|max:255',
            'model'       => 'required|string|max:255',
            'year'        => 'required|integer|min:1900|max:' . date('Y'),
            'daily_rent'  => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status'      => 'required|in:available,unavailable',
        ];
    }

    public function updatedImage()
    {
        if (!in_array($this->image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            session()->flash('error', 'الملف يجب ان يكون صورة  بصيغة JPG أو PNG أو GIF أو WEBP.');
            $this->reset('image');
            return;
        }
    }

    public function save()
    {
        $this->validate();
        $path = $this->image->store('cars', 'public');

        Car::create([
            'owner_id'   => Auth::id(),
            'image'       => $path,
            'brand'       => $this->brand,
            'model'       => $this->model,
            'year'        => $this->year,
            'daily_rent'  => $this->daily_rent,
            'description' => $this->description,
            'status'      => $this->status,
        ]);

        $this->reset();

        Flux::modal('create-car')->close();

        session()->flash('success', 'Car added successfully.');
        
        $this->dispatch('save')->to(\App\Livewire\Cars::class);
    }

    public function render()
    {
        return view('livewire.owner.car.create-car');
    }
}
