<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;

class Bookings extends Component
{
    use WithPagination;

    public function updateStatus(int $reservationId, string $status): void
    {
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

    public function render()
    {
        $reservations = Auth::user()
            ->receivedReservations()
            ->with(['car.owner', 'customer'])
            ->latest()
            ->paginate(10);

        return view('livewire.owner.bookings.bookings', [
            'reservations' => $reservations,
        ]);
    }
}
