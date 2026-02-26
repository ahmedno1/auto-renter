<div class="p-6 space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <flux:heading size="xl" level="1">
                {{ $tab === 'incoming' ? __('Incoming bookings') : __('My bookings') }}
            </flux:heading>
            <flux:subheading size="lg" class="mt-1">
                {{ $tab === 'incoming' ? __('Approve or reject booking requests for your cars.') : __('Track your booking requests and their status.') }}
            </flux:subheading>
        </div>

        <div class="inline-flex w-full sm:w-auto rounded-full border border-zinc-200 bg-white/70 p-1 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/60">
            <button
                type="button"
                wire:click="setTab('mine')"
                class="flex-1 sm:flex-none rounded-full px-4 py-2 text-sm font-semibold transition
                    {{ $tab === 'mine' ? 'bg-zinc-900 text-white dark:bg-white dark:text-black' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}"
            >
                {{ __('My bookings') }}
            </button>

            @if ($isOwner)
                <button
                    type="button"
                    wire:click="setTab('incoming')"
                    class="flex-1 sm:flex-none rounded-full px-4 py-2 text-sm font-semibold transition
                        {{ $tab === 'incoming' ? 'bg-zinc-900 text-white dark:bg-white dark:text-black' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}"
                >
                    {{ __('Incoming') }}
                </button>
            @endif
        </div>
    </div>

    <div class="hidden md:block overflow-x-auto rounded-2xl border border-zinc-200 bg-white/70 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/60">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50/70 dark:bg-zinc-950/40">
                <tr class="text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-300">
                    <th class="px-4 py-3">{{ __('Car') }}</th>
                    @if ($tab === 'incoming')
                        <th class="px-4 py-3">{{ __('Customer') }}</th>
                    @else
                        <th class="px-4 py-3">{{ __('Owner') }}</th>
                    @endif
                    <th class="px-4 py-3">{{ __('Period') }}</th>
                    <th class="px-4 py-3">{{ __('Total') }}</th>
                    <th class="px-4 py-3">{{ __('Status') }}</th>
                    @if ($tab === 'incoming')
                        <th class="px-4 py-3">{{ __('Actions') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse ($reservations as $res)
                    @php
                        $statusClasses = match ($res->status) {
                            'approved' => 'bg-green-500/15 text-green-700 dark:text-green-300 ring-green-500/20',
                            'rejected' => 'bg-red-500/15 text-red-700 dark:text-red-300 ring-red-500/20',
                            default => 'bg-amber-500/15 text-amber-700 dark:text-amber-300 ring-amber-500/20',
                        };
                    @endphp

                    <tr class="hover:bg-zinc-50/70 dark:hover:bg-zinc-950/30">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img
                                    src="{{ $res->car->getImageUrl() }}"
                                    alt="{{ $res->car->brand }} {{ $res->car->model }}"
                                    class="h-12 w-16 rounded-lg object-cover ring-1 ring-zinc-200 dark:ring-zinc-700"
                                    loading="lazy"
                                />
                                <div class="leading-tight">
                                    <div class="font-semibold text-zinc-900 dark:text-white">
                                        {{ $res->car->brand }} {{ $res->car->model }}
                                    </div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-300">
                                        {{ __('Year') }}: {{ $res->car->year }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        @if ($tab === 'incoming')
                            <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                <div class="font-medium">{{ $res->customer?->name ?? '—' }}</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-300">{{ $res->customer?->phone ?? '' }}</div>
                            </td>
                        @else
                            <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                                <div class="font-medium">{{ $res->car->owner?->name ?? '—' }}</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-300">{{ $res->car->owner?->phone ?? '' }}</div>
                            </td>
                        @endif

                        <td class="px-4 py-3 text-sm text-zinc-700 dark:text-zinc-200">
                            {{ $res->start_date->toDateString() }} <span class="text-zinc-400">→</span> {{ $res->end_date->toDateString() }}
                        </td>

                        <td class="px-4 py-3 text-sm font-semibold text-zinc-900 dark:text-white">
                            ${{ number_format((float) $res->total_price, 2) }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $statusClasses }}">
                                {{ ucfirst($res->status) }}
                            </span>
                        </td>

                        @if ($tab === 'incoming')
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <flux:button
                                        size="xs"
                                        variant="primary"
                                        color="green"
                                        wire:click="updateStatus({{ $res->id }}, 'approved')"
                                        :disabled="$res->status === 'approved'"
                                    >
                                        {{ __('Approve') }}
                                    </flux:button>
                                    <flux:button
                                        size="xs"
                                        variant="danger"
                                        wire:click="updateStatus({{ $res->id }}, 'rejected')"
                                        :disabled="$res->status === 'rejected'"
                                    >
                                        {{ __('Reject') }}
                                    </flux:button>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $tab === 'incoming' ? 6 : 5 }}" class="px-4 py-10 text-center text-sm text-zinc-500 dark:text-zinc-300">
                            {{ $tab === 'incoming' ? __('No booking requests yet.') : __('You have no bookings yet.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="md:hidden space-y-3">
        @forelse ($reservations as $res)
            @php
                $statusClasses = match ($res->status) {
                    'approved' => 'bg-green-500/15 text-green-700 dark:text-green-300 ring-green-500/20',
                    'rejected' => 'bg-red-500/15 text-red-700 dark:text-red-300 ring-red-500/20',
                    default => 'bg-amber-500/15 text-amber-700 dark:text-amber-300 ring-amber-500/20',
                };
            @endphp

            <div class="rounded-2xl border border-zinc-200 bg-white/70 p-4 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/60">
                <div class="flex items-start gap-3">
                    <img
                        src="{{ $res->car->getImageUrl() }}"
                        alt="{{ $res->car->brand }} {{ $res->car->model }}"
                        class="h-14 w-20 rounded-xl object-cover ring-1 ring-zinc-200 dark:ring-zinc-700"
                        loading="lazy"
                    />
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <div class="truncate font-semibold text-zinc-900 dark:text-white">
                                    {{ $res->car->brand }} {{ $res->car->model }}
                                </div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-300">{{ __('Year') }}: {{ $res->car->year }}</div>
                            </div>
                            <span class="shrink-0 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $statusClasses }}">
                                {{ ucfirst($res->status) }}
                            </span>
                        </div>

                        <div class="mt-2 text-sm text-zinc-700 dark:text-zinc-200">
                            {{ $res->start_date->toDateString() }} <span class="text-zinc-400">→</span> {{ $res->end_date->toDateString() }}
                        </div>

                        <div class="mt-1 text-sm font-semibold text-zinc-900 dark:text-white">
                            ${{ number_format((float) $res->total_price, 2) }}
                        </div>

                        <div class="mt-3 text-xs text-zinc-500 dark:text-zinc-300">
                            @if ($tab === 'incoming')
                                <span class="font-semibold text-zinc-700 dark:text-zinc-200">{{ __('Customer') }}:</span>
                                {{ $res->customer?->name ?? '—' }}
                                @if ($res->customer?->phone)
                                    • {{ $res->customer->phone }}
                                @endif
                            @else
                                <span class="font-semibold text-zinc-700 dark:text-zinc-200">{{ __('Owner') }}:</span>
                                {{ $res->car->owner?->name ?? '—' }}
                                @if ($res->car->owner?->phone)
                                    • {{ $res->car->owner->phone }}
                                @endif
                            @endif
                        </div>

                        @if ($tab === 'incoming')
                            <div class="mt-3 flex gap-2">
                                <flux:button
                                    size="sm"
                                    variant="primary"
                                    color="green"
                                    class="flex-1"
                                    wire:click="updateStatus({{ $res->id }}, 'approved')"
                                    :disabled="$res->status === 'approved'"
                                >
                                    {{ __('Approve') }}
                                </flux:button>
                                <flux:button
                                    size="sm"
                                    variant="danger"
                                    class="flex-1"
                                    wire:click="updateStatus({{ $res->id }}, 'rejected')"
                                    :disabled="$res->status === 'rejected'"
                                >
                                    {{ __('Reject') }}
                                </flux:button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-300">
                {{ $tab === 'incoming' ? __('No booking requests yet.') : __('You have no bookings yet.') }}
            </div>
        @endforelse
    </div>

    <div>
        {{ $reservations->links('vendor.pagination.tailwind') }}
    </div>
</div>
