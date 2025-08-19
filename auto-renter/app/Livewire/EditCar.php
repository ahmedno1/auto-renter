<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Flux\Flux;
use App\Models\Car;

class EditCar extends Component
{
    use WithFileUploads;

    public $carId;
    public $image;
    public $newImage;
    public $brand;
    public $model;
    public $year;
    public $description;
    public $status;

    #[On('edit-car')]
    public function editCar($id): void
    {
        $car = Car::findOrFail($id);

        $this->carId       = $car->id;
        $this->image       = $car->image;
        $this->newImage    = null;
        $this->brand       = $car->brand;
        $this->model       = $car->model;
        $this->year        = $car->year;
        $this->description = $car->description;
        $this->status      = $car->status;

        // ✅ correct method name
        Flux::modal('edit-car')->show();
    }

    public function update()
    {
        $this->validate([
            'newImage'    => ['nullable', 'image', 'max:102400'], // 100MB Max
            'brand'       => ['required', 'string', 'max:255'],
            'model'       => ['required', 'string', 'max:255'],
            'year'        => ['required', 'unsignedSmallInteger', 'min:1900', 'max:' . date('Y')],
            'daily_rent'  => ['required', 'decimal', 'max:255'],
            'description' => ['nullable', 'text', 'max:1000'],
            'status'      => ['required', 'in:available,unavailable'],
        ]);

        $car = Car::findOrFail($this->carId);

        // if the user picked a new image, store it and replace the path
        if ($this->newImage) {
            $path = $this->newImage->store('cars', 'public');
            $car->image = $path;
            $this->image = $path;
        }

        $car->brand       = $this->brand;
        $car->model       = $this->model;
        $car->year        = $this->year;
        $car->description = $this->description;
        $car->status      = $this->status;
        $car->save();

        session()->flash('success', 'Car updated successfully.');

        Flux::modal('edit-car')->close();

        $this->redirectRoute('cars', navigate: true);
    }

    public function render()
    {
        return view('livewire.owner.car.edit-car');
    }
}
