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

    // keep the existing image path separate from a newly uploaded file
    public $carId;
    public $image;        // existing path string
    public $newImage;     // Livewire\TemporaryUploadedFile when user selects a new one
    public $brand;
    public $model;
    public $year;
    public $description;
    public $status;

    #[On('edit-car')]
    public function editCar($id): void
    {
        $car = Car::findOrFail($id);

        $this->carId      = $car->id;
        $this->image      = $car->image;       // existing path
        $this->newImage   = null;              // reset file input
        $this->brand      = $car->brand;
        $this->model      = $car->model;
        $this->year       = $car->year;
        $this->description= $car->description;
        $this->status     = $car->status;

        // ✅ correct method name
        Flux::modal('edit-car')->show();
    }

    public function update()
    {
        $this->validate([
            // new image is optional during edit
            'newImage'    => ['nullable', 'image', 'max:102400'],
            'brand'       => ['required', 'string', 'max:255'],
            'model'       => ['required', 'string', 'max:255'],
            'year'        => ['required', 'integer', 'min:1900', 'max:' . date('Y')], // ✅ fixed
            'description' => ['nullable', 'string', 'max:1000'],
            'status'      => ['required', 'in:available,unavailable'],
        ]);

        $car = Car::findOrFail($this->carId);

        // if the user picked a new image, store it and replace the path
        if ($this->newImage) {
            $path = $this->newImage->store('cars', 'public');
            $car->image = $path;
            $this->image = $path; // keep UI in sync
        }

        $car->brand       = $this->brand;
        $car->model       = $this->model;
        $car->year        = $this->year;
        $car->description = $this->description;
        $car->status      = $this->status;
        $car->save();

        session()->flash('success', 'Car updated successfully.');

        // close the right modal
        Flux::modal('edit-car')->close();

        // EITHER: navigate (SPA)
        return $this->redirectRoute('cars', navigate: true);

        // OR (alternative): stay here and refresh the list instantly
        // $this->dispatch('car-updated');
        // return;
    }

    public function render()
    {
        return view('livewire.owner.car.edit-car');
    }
}
