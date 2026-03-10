<section class="container mx-auto px-4 py-8">
    <div class="grid gap-6 lg:grid-cols-[18rem_1fr]">
        <!-- Filters -->
        <aside class="hidden lg:block sticky top-24 self-start space-y-6 rounded-2xl border border-zinc-200 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/60">
            <flux:heading size="l" level="1">{{ __('Filters') }}</flux:heading>
            <flux:separator variant="subtle" />

            <div class="space-y-2">
                <label for="brand" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('Brand') }}</label>
                <flux:select size="sm" placeholder="All brands" id="brand" wire:model.live="brand">
                    <flux:select.option value="">{{ __('All brands') }}</flux:select.option>
                    @foreach($brands as $brandOption)
                        <flux:select.option value="{{ $brandOption }}">{{ $brandOption }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div class="space-y-2">
                <label for="sort" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('Sort') }}</label>
                <flux:select size="sm" id="sort" wire:model.live="sort">
                    <flux:select.option value="newest">{{ __('Newest') }}</flux:select.option>
                    <flux:select.option value="price_low">{{ __('Price: Low to High') }}</flux:select.option>
                    <flux:select.option value="price_high">{{ __('Price: High to Low') }}</flux:select.option>
                </flux:select>
            </div>

            <div class="flex items-center justify-between rounded-xl border border-zinc-200 bg-white/60 px-3 py-2 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950/30">
                <div class="flex items-center gap-2 text-zinc-700 dark:text-zinc-200">
                    <flux:icon.check-circle class="h-4 w-4" />
                    <span class="font-medium">{{ __('Only available') }}</span>
                </div>
                <input type="checkbox" class="h-4 w-4 accent-black dark:accent-white" wire:model.live="onlyAvailable">
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('Price (per day)') }}</span>
                    <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-300">
                        ${{ number_format($priceMin, 0) }} — ${{ number_format($priceMax, 0) }}
                    </span>
                </div>

                @php
                    $__total = max(1e-9, ($maxPrice ?? 0) - ($minPrice ?? 0));
                    $__startPct = isset($priceMin, $minPrice) ? (($priceMin - $minPrice) / $__total) * 100 : 0;
                    $__endPct = isset($priceMax, $minPrice) ? (($priceMax - $minPrice) / $__total) * 100 : 100;
                @endphp

                <div class="relative pt-6 pb-4 px-1">
                    <div class="absolute left-0 right-0 top-1/2 h-1 -translate-y-1/2 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                    <div class="absolute top-1/2 h-1 -translate-y-1/2 rounded bg-zinc-900 dark:bg-white"
                         style="left: calc({{ $__startPct }}%); right: calc({{ 100 - $__endPct }}%);"></div>

                    <input
                        type="range"
                        min="{{ $minPrice }}"
                        max="{{ $maxPrice }}"
                        step="5"
                        wire:model.live="priceMin"
                        class="range-thumb peer absolute inset-x-0 -top-1 h-2 w-full appearance-none bg-transparent focus:outline-none"
                    />
                    <input
                        type="range"
                        min="{{ $minPrice }}"
                        max="{{ $maxPrice }}"
                        step="5"
                        wire:model.live="priceMax"
                        class="range-thumb absolute inset-x-0 -top-1 h-2 w-full appearance-none bg-transparent focus:outline-none"
                    />
                </div>

                <div class="flex items-center gap-3">
                    <input type="number" step="5" min="{{ $minPrice }}" max="{{ $maxPrice }}"
                           wire:model.lazy="priceMin"
                           class="w-1/2 rounded-xl border border-zinc-200 bg-white/80 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-zinc-700 dark:bg-zinc-950/30 dark:text-white dark:focus:ring-white/20"
                           placeholder="Min" />
                    <span class="text-zinc-400">—</span>
                    <input type="number" step="5" min="{{ $minPrice }}" max="{{ $maxPrice }}"
                           wire:model.lazy="priceMax"
                           class="w-1/2 rounded-xl border border-zinc-200 bg-white/80 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-zinc-700 dark:bg-zinc-950/30 dark:text-white dark:focus:ring-white/20"
                           placeholder="Max" />
                </div>
            </div>
        </aside>

        <!-- Results -->
        <div class="space-y-6">
            <div>
                <flux:heading size="xl" level="1">{{ __('Search') }}</flux:heading>
                <flux:subheading size="lg" class="mt-1">{{ __('Find a car that fits your needs.') }}</flux:subheading>
            </div>

            <!-- Mobile filters -->
            <details class="lg:hidden rounded-2xl border border-zinc-200 bg-white/70 p-4 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/60">
                <summary class="cursor-pointer list-none">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm font-semibold text-zinc-900 dark:text-white">
                            <flux:icon.search class="h-4 w-4" />
                            <span>{{ __('Filters') }}</span>
                        </div>
                        <flux:icon.chevron-down class="h-4 w-4 text-zinc-500 dark:text-zinc-300" />
                    </div>
                </summary>

                <div class="mt-4 space-y-5">
                    <div class="space-y-2">
                        <label for="brand-mobile" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('Brand') }}</label>
                        <flux:select size="sm" placeholder="All brands" id="brand-mobile" wire:model.live="brand">
                            <flux:select.option value="">{{ __('All brands') }}</flux:select.option>
                            @foreach($brands as $brandOption)
                                <flux:select.option value="{{ $brandOption }}">{{ $brandOption }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    <div class="space-y-2">
                        <label for="sort-mobile" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('Sort') }}</label>
                        <flux:select size="sm" id="sort-mobile" wire:model.live="sort">
                            <flux:select.option value="newest">{{ __('Newest') }}</flux:select.option>
                            <flux:select.option value="price_low">{{ __('Price: Low to High') }}</flux:select.option>
                            <flux:select.option value="price_high">{{ __('Price: High to Low') }}</flux:select.option>
                        </flux:select>
                    </div>

                    <div class="flex items-center justify-between rounded-xl border border-zinc-200 bg-white/60 px-3 py-2 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950/30">
                        <div class="flex items-center gap-2 text-zinc-700 dark:text-zinc-200">
                            <flux:icon.check-circle class="h-4 w-4" />
                            <span class="font-medium">{{ __('Only available') }}</span>
                        </div>
                        <input type="checkbox" class="h-4 w-4 accent-black dark:accent-white" wire:model.live="onlyAvailable">
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('Price (per day)') }}</span>
                            <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-300">
                                ${{ number_format($priceMin, 0) }} — ${{ number_format($priceMax, 0) }}
                            </span>
                        </div>

                        @php
                            $__total = max(1e-9, ($maxPrice ?? 0) - ($minPrice ?? 0));
                            $__startPct = isset($priceMin, $minPrice) ? (($priceMin - $minPrice) / $__total) * 100 : 0;
                            $__endPct = isset($priceMax, $minPrice) ? (($priceMax - $minPrice) / $__total) * 100 : 100;
                        @endphp

                        <div class="relative pt-6 pb-4 px-1">
                            <div class="absolute left-0 right-0 top-1/2 h-1 -translate-y-1/2 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                            <div class="absolute top-1/2 h-1 -translate-y-1/2 rounded bg-zinc-900 dark:bg-white"
                                 style="left: calc({{ $__startPct }}%); right: calc({{ 100 - $__endPct }}%);"></div>

                            <input
                                type="range"
                                min="{{ $minPrice }}"
                                max="{{ $maxPrice }}"
                                step="5"
                                wire:model.live="priceMin"
                                class="range-thumb peer absolute inset-x-0 -top-1 h-2 w-full appearance-none bg-transparent focus:outline-none"
                            />
                            <input
                                type="range"
                                min="{{ $minPrice }}"
                                max="{{ $maxPrice }}"
                                step="5"
                                wire:model.live="priceMax"
                                class="range-thumb absolute inset-x-0 -top-1 h-2 w-full appearance-none bg-transparent focus:outline-none"
                            />
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="number" step="5" min="{{ $minPrice }}" max="{{ $maxPrice }}"
                                   wire:model.lazy="priceMin"
                                   class="w-1/2 rounded-xl border border-zinc-200 bg-white/80 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-zinc-700 dark:bg-zinc-950/30 dark:text-white dark:focus:ring-white/20"
                                   placeholder="Min" />
                            <span class="text-zinc-400">—</span>
                            <input type="number" step="5" min="{{ $minPrice }}" max="{{ $maxPrice }}"
                                   wire:model.lazy="priceMax"
                                   class="w-1/2 rounded-xl border border-zinc-200 bg-white/80 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-zinc-700 dark:bg-zinc-950/30 dark:text-white dark:focus:ring-white/20"
                                   placeholder="Max" />
                        </div>
                    </div>
                </div>
            </details>

            <!-- Search form (shareable URL via GET) -->
            <form
                action="{{ route('search') }}"
                method="GET"
                class="rounded-2xl border border-zinc-200 bg-white/70 p-4 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/60"
                x-data="{
                    type: @js($type),
                    queryText: @js($type !== 'availability' ? $query : ''),
                    queryDate: @js($type === 'availability' ? $query : ''),
                }"
            >
                <div class="grid gap-3 sm:grid-cols-[12rem_1fr]">
                    <div>
                        <label for="search-type" class="sr-only">{{ __('Search type') }}</label>
                        <select
                            id="search-type"
                            name="type"
                            x-model="type"
                            class="h-11 w-full rounded-xl border border-zinc-200 bg-white/80 px-3 text-sm font-semibold text-zinc-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-zinc-700 dark:bg-zinc-950/30 dark:text-white dark:focus:ring-white/20"
                        >
                            <option value="model">{{ __('Brand / Model') }}</option>
                            <option value="owner">{{ __('Owner') }}</option>
                            <option value="availability">{{ __('Available on date') }}</option>
                        </select>
                    </div>

                    <div class="relative">
                        <template x-if="type === 'availability'">
                            <input
                                type="date"
                                name="query"
                                x-model="queryDate"
                                class="h-11 w-full rounded-xl border border-zinc-200 bg-white/80 px-3 pr-12 text-sm text-zinc-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-zinc-700 dark:bg-zinc-950/30 dark:text-white dark:focus:ring-white/20"
                                required
                            />
                        </template>
                        <template x-if="type !== 'availability'">
                            <input
                                type="text"
                                name="query"
                                x-model="queryText"
                                placeholder="{{ __('Search…') }}"
                                class="h-11 w-full rounded-xl border border-zinc-200 bg-white/80 px-3 pr-12 text-sm text-zinc-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-zinc-700 dark:bg-zinc-950/30 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-white/20"
                                required
                            />
                        </template>

                        <!-- Preserve Livewire filters in GET -->
                        <input type="hidden" name="brand" value="{{ $brand }}">
                        <input type="hidden" name="price_min" value="{{ $priceMin }}">
                        <input type="hidden" name="price_max" value="{{ $priceMax }}">
                        <input type="hidden" name="available" value="{{ $onlyAvailable ? 1 : 0 }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">

                        <button
                            type="submit"
                            class="absolute inset-y-0 right-0 my-1 mr-1 inline-flex w-10 items-center justify-center rounded-lg bg-zinc-900 text-white shadow-sm transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-black/10 dark:bg-white dark:text-black dark:hover:bg-zinc-200 dark:focus:ring-white/20"
                            aria-label="Search"
                        >
                            <flux:icon.search class="h-4 w-4" />
                        </button>
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-zinc-500 dark:text-zinc-300">
                    <span class="inline-flex items-center rounded-full border border-zinc-200 bg-white/60 px-3 py-1 shadow-sm dark:border-zinc-700 dark:bg-zinc-950/30">
                        {{ __('Tip: Use “Available on date” to avoid booked cars.') }}
                    </span>
                </div>
            </form>

            <!-- Cards -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($cars as $car)
                    <div class="group rounded-2xl border border-zinc-200 bg-white/70 p-5 shadow-sm backdrop-blur transition hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900/60">
                        <div class="aspect-[16/10] overflow-hidden rounded-xl bg-zinc-100 ring-1 ring-zinc-200 dark:bg-zinc-950/30 dark:ring-zinc-700">
                            <img
                                src="{{ $car->getImageUrl() }}"
                                alt="{{ $car->brand }}"
                                class="h-full w-full object-cover transition group-hover:scale-[1.02]"
                                loading="lazy"
                            >
                        </div>

                        <div class="mt-4 space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="truncate text-lg font-bold text-zinc-900 dark:text-white">
                                        {{ $car->brand }} — {{ $car->model }}
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-zinc-300">
                                        {{ __('Year') }}: {{ $car->year }} • <span class="font-semibold text-zinc-900 dark:text-white">${{ number_format((float) $car->daily_rent, 0) }}</span> / {{ __('day') }}
                                    </div>
                                </div>

                                <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-semibold ring-1
                                    {{ $car->status === 'available' ? 'bg-green-500/15 text-green-700 dark:text-green-300 ring-green-500/20' : 'bg-red-500/15 text-red-700 dark:text-red-300 ring-red-500/20' }}"
                                >
                                    {{ ucfirst($car->status) }}
                                </span>
                            </div>

                            <div class="text-sm text-zinc-600 dark:text-zinc-200">
                                <span class="font-semibold">{{ __('Owner') }}:</span> {{ $car->owner?->name ?? 'Unknown' }}
                            </div>

                            <div class="pt-2">
                                <flux:button type="button" wire:click="showCar({{ $car->id }})" variant="primary" class="w-full">
                                    {{ __('Show details') }}
                                </flux:button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-zinc-300 p-10 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-300">
                        {{ __('No cars found.') }}
                    </div>
                @endforelse
            </div>

            <div>
                {{ $cars->links('vendor.pagination.tailwind') }}
            </div>

            <!-- Details modal -->
            <flux:modal name="car-details" class="min-w-[22rem]">
                @if ($selectedCar)
                    <div class="space-y-3">
                        <img
                            src="{{ $selectedCar->getImageUrl() }}"
                            alt="{{ $selectedCar->brand }}"
                            class="w-full max-h-60 object-cover rounded-xl ring-1 ring-zinc-200 dark:ring-zinc-700"
                        >

                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="truncate text-xl font-bold text-zinc-900 dark:text-white">
                                    {{ $selectedCar->brand }} — {{ $selectedCar->model }}
                                </div>
                                <div class="text-sm text-zinc-500 dark:text-zinc-300">
                                    {{ __('Year') }}: {{ $selectedCar->year }} • ${{ number_format((float) $selectedCar->daily_rent, 2) }} / {{ __('day') }}
                                </div>
                            </div>
                            <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-semibold ring-1
                                {{ $selectedCar->status === 'available' ? 'bg-green-500/15 text-green-700 dark:text-green-300 ring-green-500/20' : 'bg-red-500/15 text-red-700 dark:text-red-300 ring-red-500/20' }}"
                            >
                                {{ ucfirst($selectedCar->status) }}
                            </span>
                        </div>

                        <div class="text-sm text-zinc-700 dark:text-zinc-200">
                            <span class="font-semibold">{{ __('Owner') }}:</span> {{ $selectedCar->owner?->name ?? 'Unknown' }}
                        </div>

                        @if ($selectedCar->status === 'available')
                            <div class="pt-2 space-y-3">
                                <x-date-range-picker
                                    start-model="start_date"
                                    end-model="end_date"
                                    :disabled-dates="$disabledDates"
                                    :min-date="\Carbon\Carbon::now()->toDateString()"
                                />

                                @if ($this->estimatedTotal)
                                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">
                                        {{ __('Estimated total') }}: ${{ $this->estimatedTotal }}
                                    </div>
                                @endif

                                <flux:button
                                    type="button"
                                    wire:click="rent"
                                    variant="primary"
                                    class="w-full bg-green-600 text-white hover:bg-green-700"
                                    :disabled="! $start_date || ! $end_date"
                                >
                                    {{ __('Confirm booking') }}
                                </flux:button>
                            </div>
                        @else
                            <div class="rounded-xl border border-red-500/20 bg-red-500/10 p-3 text-sm text-red-700 dark:text-red-200">
                                {{ __('Car is not available for renting in this time.') }}
                            </div>
                        @endif
                    </div>
                @endif
            </flux:modal>
        </div>
    </div>
</section>
