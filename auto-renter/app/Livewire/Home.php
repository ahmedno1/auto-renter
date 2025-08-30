<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Car;
use App\Models\Reservation;
use Livewire\WithPagination;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;


#[Layout('components.layouts.app.header')]
class Home extends Component
{
    use WithPagination;

    public $selectedCar = null;
    public $start_date;
    public $end_date;
    public array $disabledDates = [];


    public function showCar($id)
    {
        $this->selectedCar = Car::with([
            'owner',
            'reservations' => fn ($q) => $q->whereIn('status', ['pending', 'approved'])
        ])->find($id);
        $this->disabledDates = [];

        if ($this->selectedCar) {
            foreach ($this->selectedCar->reservations as $reservation) {
                $period = CarbonPeriod::create($reservation->start_date, $reservation->end_date);

                foreach ($period as $date) {
                    $this->disabledDates[] = $date->toDateString();
                }
            }

            $this->disabledDates = array_values(array_unique($this->disabledDates));
        }

        \Flux\Flux::modal('car-details')->show();
    }

    public function rent()
    {
        if (!$this->selectedCar) {
            session()->flash('error', 'Please select a car to book.');
            return;
        }

        if (Auth::id() === $this->selectedCar->owner_id) {
            session()->flash('error', 'You cannot book your own car.');
            return;
        }

        if (!$this->start_date || !$this->end_date) {
            session()->flash('error', 'Please choose both start and end dates.');
            return;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        if ($start->gt($end)) {
            session()->flash('error', 'Start date must be before end date.');
            return;
        }

        if (!$this->selectedCar->isAvailableBetween($start, $end)) {
            session()->flash('error', 'This car is not available for the selected dates.');
            return;
        }

        $days = $start->diffInDays($end) + 1; // inclusive
        $total = $days * (float) $this->selectedCar->daily_rent;

        Reservation::create([
            'car_id' => $this->selectedCar->id,
            'customer_id' => Auth::id(),
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'total_price' => $total,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Booking submitted. Waiting for owner approval.');
        $this->reset(['start_date', 'end_date']);
        \Flux\Flux::modal('car-details')->close();
    }

    public function updatedStartDate()
    {
        if ($this->end_date) {
            $end = Carbon::parse($this->end_date);
            $start = Carbon::parse($this->start_date);

            if ($end->lt($start) || in_array($this->end_date, $this->disabledDates)) {
                $this->end_date = null;
            }
        }
    }

    public function updatedEndDate()
    {
        if ($this->end_date && $this->start_date) {
            $end = Carbon::parse($this->end_date);
            $start = Carbon::parse($this->start_date);

            if ($end->lt($start) || in_array($this->end_date, $this->disabledDates)) {
                $this->end_date = null;
            }
        }
    }

    public function nextAvailableDate()
    {
        if (!$this->start_date) {
            return null;
        }

        $start = Carbon::parse($this->start_date);
        $dates = $this->disabledDates;
        sort($dates);

        foreach ($dates as $date) {
            $d = Carbon::parse($date);

            if ($d->gt($start)) {
                return $d->subDay()->toDateString();
            }
        }

        return null;
    }

    public function getEstimatedTotalProperty(): ?string
    {
        if (!$this->selectedCar || !$this->start_date || !$this->end_date) {
            return null;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        if ($end->lt($start)) {
            return null;
        }

        $days = $start->diffInDays($end) + 1;
        $total = $days * (float) $this->selectedCar->daily_rent;
        return number_format($total, 2);
    }

    public function render()
    {
        $cars = Car::with('owner')->latest()->paginate(6);
        return view('livewire.home', ['cars' => $cars]);
    }
}