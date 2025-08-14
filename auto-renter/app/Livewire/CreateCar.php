<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Car;
use Flux\Flux;

class CreateCar extends Component
{
    public $image;
    public $brand;
    public $model;
    public $year;
    public $daily_rent;
    public $description;
    public $status;

    protected function rules()
    {
        return [
            'image' => 'required|image|max:102400', // 100MB Max
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'daily_rent' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:available,unavailable',
        ];
    }

    public function save()
    {
        $this->validate();

        Car::create([
            'image' => $this->path,
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'daily_rent' => $this->daily_rent,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        Flux::modal('create-car')->close();

        session()->flash('success', 'Car added successfully.');

        $this->redirectRoute('cars', navigate: true);
    }

    public function render()
    {
        return view('livewire.owner.car.create-car');
    }
}
