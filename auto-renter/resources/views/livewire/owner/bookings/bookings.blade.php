<div class="p-6">
    <flux:heading size="xl" level="1">{{ __('Bookings') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Manage the reservations') }}</flux:subheading>
    <flux:separator variant="subtle" />
    <h1 class="text-2xl font-bold mb-4"></h1>

    @if (session('success'))
        <div class="mb-4 text-green-700">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-black border">
            <thead>
                <tr class="bg-gray-100 dark:bg-zinc-900">
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Car</th>
                    <th class="px-4 py-2 text-left">Customer</th>
                    <th class="px-4 py-2 text-left">Period</th>
                    <th class="px-4 py-2 text-left">Total</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $res)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $res->id }}</td>
                        <td class="px-4 py-2">
                            {{ $res->car->brand }} {{ $res->car->model }} ({{ $res->car->year }})
                        </td>
                        <td class="px-4 py-2">{{ $res->customer?->name ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $res->start_date->toDateString() }} → {{ $res->end_date->toDateString() }}</td>
                        <td class="px-4 py-2">${{ number_format($res->total_price, 2) }}</td>
                        <td class="px-4 py-2 capitalize">{{ $res->status }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <flux:button size="xs" variant="primary" color="green" wire:click="updateStatus({{ $res->id }}, 'approved')" :disabled="$res->status === 'approved'">Approve</flux:button>
                            <flux:button size="xs" variant="danger" wire:click="updateStatus({{ $res->id }}, 'rejected')" :disabled="$res->status === 'rejected'">Reject</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">No bookings yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $reservations->links('vendor.pagination.tailwind') }}
    </div>
</div>
