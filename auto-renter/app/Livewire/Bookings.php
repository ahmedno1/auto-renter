<?php

namespace App\Livewire;

use Livewire\Component;

class Bookings extends Component
{
    public $carId;
    public $start_date;
    public $end_date;

    public function rent(){
        $car = Car::findOrFail($this->carId);

        if(!$car->isAvailableBetween($this->start_date, $this->end_date))
        {
            session()->flash('error', 'Car is rented in this time.');
            return;
        }

        Reservation::create([
            'car_id' => $car->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        session()->flash('success', 'renting os done)
    }

    public function render()
    {
        return view('livewire.bookings.bookings');
    }
}
