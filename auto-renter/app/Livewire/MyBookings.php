<?php

namespace App\Livewire;

use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app.header')]
class MyBookings extends Component
{
    use WithPagination;

    public string $tab = 'mine';

    public function mount(): void
    {
        $tab = request()->query('tab');
        if (is_string($tab)) {
            $this->tab = $tab;
        }

        $this->normalizeTab();
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
        $this->normalizeTab();
        $this->resetPage();
    }

    private function normalizeTab(): void
    {
        $this->tab = $this->tab === 'incoming' ? 'incoming' : 'mine';

        if ($this->tab === 'incoming' && (!Auth::user() || Auth::user()->role !== 'owner')) {
            $this->tab = 'mine';
        }
    }

    public function updateStatus(int $reservationId, string $status): void
    {
        if ($this->tab !== 'incoming') {
            return;
        }

        if (!in_array($status, ['approved', 'rejected'], true)) {
            return;
        }

        $reservation = Reservation::with('car')
            ->where('id', $reservationId)
            ->first();

        if (!$reservation || $reservation->car->owner_id !== Auth::id()) {
            abort(403);
        }

        $reservation->update(['status' => $status]);
        $this->dispatch('toast', type: 'success', message: 'Reservation status updated.');
    }

    public function getReservationsProperty()
    {
        if ($this->tab === 'incoming') {
            return Auth::user()
                ->receivedReservations()
                ->with(['car.owner', 'customer'])
                ->latest()
                ->paginate(10);
        }

        return Auth::user()
            ->customerReservations()
            ->with(['car.owner'])
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.my-bookings', [
            'reservations' => $this->reservations,
        ]);
    }
}

