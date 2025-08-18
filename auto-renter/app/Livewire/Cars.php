<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;
use App\Models\Car;

class Cars extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function toggleStatus(int $id): void
    {
        $car = Car::findOrFail($id);
        $car->status = $car->status === 'available' ? 'unavailable' : 'available';
        $car->save();
        $this->dispatch('toast', 'Status updated');
    }

    public $carId;

    public function render()
    {
        $cars = Car::orderByDesc('created_at')->paginate(5);
        return view('livewire.owner.car.cars', [
            'cars' => $cars
        ]);
    }

    public function edit($id)
    {
        $this->dispatch('edit-car', $id);
    }

    public function delete($id)
    {
        $this->carId = $id;
        Flux::modal('delete-car')->show();
    }

    public function deleteCar()
    {
        Car::find($this->carId)->delete();
        Flux::modal('delete-car')->close();
        session()->flash('success', 'Car deleted successfully.');
    }

    public function carsCount()
    {
        return Car::count();
    }
}
