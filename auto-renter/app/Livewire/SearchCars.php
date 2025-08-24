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

    public function mount()
    {
        $this->type = request()->query('type', 'model');
        $this->query = request()->query('query', '');
        $this->brand = request()->query('brand', '');
    }

    public function updatingBrand()
    {
        $this->resetPage();
    }

    public function render()
    {
        $cars = Car::with('owner');

        if ($this->query) {
            switch ($this->type) {
                case 'availability':
                    try {
                        $date = Carbon::parse($this->query);
                        $cars->whereDoesntHave('reservations', function ($q) use ($date) {
                            $q->where('start_date', '<=', $date)
                              ->where('end_date', '>=', $date);
                        });
                    } catch (\Exception $e) {
                        // ignore invalid date
                    }
                    break;
                case 'owner':
                    $cars->whereHas('owner', function ($q) {
                        $q->where('name', 'like', '%' . $this->query . '%');
                    });
                    break;
                case 'model':
                default:
                    $cars->where('model', 'like', '%' . $this->query . '%');
                    break;
            }
        }

        if ($this->brand) {
            $cars->where('brand', $this->brand);
        }

        $brands = Car::select('brand')->distinct()->orderBy('brand')->pluck('brand');

        return view('livewire.search-cars', [
            'cars' => $cars->paginate(6),
            'brands' => $brands,
        ]);
    }
}
