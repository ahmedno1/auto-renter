<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Car;
use Carbon\Carbon;

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

    public function mount()
    {
        $this->type = request()->query('type', 'model');
        $this->query = request()->query('query', '');
        $this->brand = request()->query('brand', '');

        // Initialize price bounds from database
        $min = Car::min('daily_rent');
        $max = Car::max('daily_rent');

        $this->minPrice = $min !== null ? (float) $min : 0;
        $this->maxPrice = $max !== null ? (float) $max : 0;

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
}
