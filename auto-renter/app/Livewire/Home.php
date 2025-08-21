<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Car;
use Livewire\WithPagination;

#[Layout('components.layouts.app.header')]
class Home extends Component
{
    use WithPagination;

    public $selectedCar = null;

    public function showCar($id)
    {
        $this->selectedCar = Car::with('owner')->find($id);
        \Flux\Flux::modal('car-details')->show();
    }

    public function render()
    {
        $cars = Car::with('owner')->latest()->paginate(6);
        return view('livewire.home', ['cars' => $cars]);
    }
}
