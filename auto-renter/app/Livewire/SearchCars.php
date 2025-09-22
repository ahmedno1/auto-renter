<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Car;
use App\Models\Reservation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app.header')]
class SearchCars extends Component
{
    use WithPagination;

    public $type;
    public $query;
    public $brand;
    public $minPrice;
    public $maxPrice;
    public $priceMin;
    public $priceMax;

    public $selectedCar = null;
    public $start_date;
    public $end_date;
    public array $disabledDates = [];

    public function mount()
    {
        $this->type = request()->query('type', 'model');
        $this->query = request()->query('query', '');
        $this->brand = request()->query('brand', '');

        // Initialize price bounds from database
        $min = Car::min('daily_rent');
        $max = Car::max('daily_rent');

        $this->minPrice = $min !== null ? (float) $min : 0;
        $this->maxPrice = $max !== null ? (float) $max +1 : 0;

        // Initialize current range to full span
        $this->priceMin = $this->minPrice;
        $this->priceMax = $this->maxPrice;

        // Override with request values if present (preserve filters on reload)
        $rqMin = request()->query('price_min');
        $rqMax = request()->query('price_max');

        if ($rqMin !== null && $rqMin !== '') {
            $val = (float) $rqMin;
            $this->priceMin = max($this->minPrice, min($val, $this->maxPrice));
        }
        if ($rqMax !== null && $rqMax !== '') {
            $val = (float) $rqMax;
            $this->priceMax = min($this->maxPrice, max($val, $this->minPrice));
        }
    }

    public function updatingBrand()
    {
        $this->resetPage();
    }

    public function updatingPriceMin()
    {
        $this->resetPage();
    }

    public function updatingPriceMax()
    {
        $this->resetPage();
    }

    public function updatedPriceMin($value)
    {
        // Ensure lower bound never exceeds upper bound
        if ($value > $this->priceMax) {
            $this->priceMin = $this->priceMax;
        }
        // Clamp to absolute bounds
        if ($this->priceMin < $this->minPrice) {
            $this->priceMin = $this->minPrice;
        }
    }

    public function updatedPriceMax($value)
    {
        // Ensure upper bound never goes below lower bound
        if ($value < $this->priceMin) {
            $this->priceMax = $this->priceMin;
        }
        // Clamp to absolute bounds
        if ($this->priceMax > $this->maxPrice) {
            $this->priceMax = $this->maxPrice;
        }
    }

    public function render()
    {
        $cars = Car::with('owner');

        if ($this->query) {
            switch ($this->type) {
                case 'owner':
                    $cars->whereHas('owner', function ($q) {
                        $q->where('name', 'like', '%' . $this->query . '%');
                    });
                    break;
                case 'availability':
                    $date = Carbon::parse($this->query)->toDateString();
                    $cars->whereDoesntHave('reservations', function ($q) use ($date) {
                        $q->where('start_date', '<=', $date)
                          ->where('end_date', '>=', $date)
                          ->whereIn('status', ['pending', 'approved']);
                    });
                    break;
                case 'model':
                default:
                    $cars->where('brand', 'like', '%' . $this->query . '%');
                    break;
            }
        }

        if ($this->brand) {
            $cars->where('brand', $this->brand);
        }

        // Apply price range filter when bounds are available
        if ($this->minPrice !== null && $this->maxPrice !== null) {
            $cars->whereBetween('daily_rent', [
                $this->priceMin ?? $this->minPrice,
                $this->priceMax ?? $this->maxPrice,
            ]);
        }

        $brands = Car::select('brand')->distinct()->orderBy('brand')->pluck('brand');

        return view('livewire.search-cars', [
            'cars' => $cars->paginate(6),
            'brands' => $brands,
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
        ]);
    }

    public function showCar($id)
    {
        $previousCarId = $this->selectedCar?->id;

        $this->selectedCar = Car::with([
            'owner',
            'reservations' => fn ($q) => $q->whereIn('status', ['pending', 'approved'])
        ])->find($id);

        $this->disabledDates = [];

        if ($this->selectedCar) {
            if ($previousCarId !== $this->selectedCar->id) {
                $this->reset(['start_date', 'end_date']);
            }

            foreach ($this->selectedCar->reservations as $reservation) {
                $period = CarbonPeriod::create($reservation->start_date, $reservation->end_date);
                foreach ($period as $date) {
                    $this->disabledDates[] = $date->toDateString();
                }
            }

            $this->disabledDates = array_values(array_unique($this->disabledDates));
            sort($this->disabledDates);
        }

        $this->dispatch('car-details-opened',
            disabledDates: $this->disabledDates,
            start: $this->start_date,
            end: $this->end_date,
        );

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
}

