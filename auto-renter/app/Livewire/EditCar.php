<?php

namespace App\Livewire;

use Livewire\Component;
use Flux\Flux;
use App\Models\Car;
use Livewire\Attributes\On;

class EditCar extends Component
{
    public $image, $brand, $model, $year, $description, $status, $carId;

    #[On('edit-car')]

    public function editCar($id)
    {
        $car = Car::findOrFail($id);
        $this->carId = $id;
        $this->image = $car->image;
        $this->brand = $car->brand;
        $this->model = $car->model;
        $this->year = $car->year;
        $this->description = $car->description;
        $this->status = $car->status;
        Flux::model('edit-car')->show();
    }

    public function update()
    {
        $this->validate([
            'image' => ['required', 'image', 'max:102400'], // 100MB Max
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:1900', 'max:'] . date('Y'),
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:available,unavailable'],
        ]);

        $car = Car::find($this->carId);
        $car->image = $this->image;
        $car->brand = $this->brand;
        $car->model = $this->model;
        $car->year = $this->year;
        $car->description = $this->description;
        $car->status = $this->status;

        session()->flash('success', 'Car updated successfully');
        $this->redirectRoute('cars', navigate: true);
    }
    public function render()
    {
        return view('livewire.edit-car');
    }
}
