<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Car;
use App\Models\Reservation;
use Livewire\WithPagination;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


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
        $this->selectedCar = Car::with(['owner', 'reservations'])->find($id);
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
            session()->flash('error', 'لم يتم اختيار سيارة.');
            return;
        }

        if (!$this->start_date || !$this->end_date) {
            session()->flash('error', 'يرجى تحديد تاريخ البداية والنهاية.');
            return;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        if ($start->gt($end)) {
            session()->flash('error', 'تاريخ البداية يجب أن يكون قبل تاريخ النهاية.');
            return;
        }

        $overlap = $this->selectedCar->reservations()
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                      });
            })
            ->exists();

        if ($overlap) {
            session()->flash('error', 'السيارة محجوزة في هذه الفترة.');
            return;
        }

        Reservation::create([
            'car_id' => $this->selectedCar->id,
            'start_date' => $start,
            'end_date' => $end,
        ]);

        session()->flash('success', 'تم الحجز بنجاح.');
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



    public function render()
    {
        $cars = Car::with('owner')->latest()->paginate(6);
        return view('livewire.home', ['cars' => $cars]);
    }
}