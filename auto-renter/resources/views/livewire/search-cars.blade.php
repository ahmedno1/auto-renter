<div class="flex gap-6">

    <!-- Filters sidebar -->
    <aside class="w-72 shrink-0 space-y-6 p-5 border border-gray-200 rounded-xl bg-white/70 backdrop-blur-sm shadow-sm dark:bg-black dark:border-gray-800">
        <h2 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-gray-100">Search Filters</h2>

        <!-- Brand select -->
        <div class="space-y-2">
            <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <flux:icon.car class="h-4 w-4" />
                </span>
                <select
                    id="brand"
                    wire:model.live="brand"
                    class="w-full appearance-none pl-9 pr-9 py-3 rounded-lg border border-gray-300 bg-white/90 backdrop-blur-sm shadow-sm transition-colors
                           focus:outline-none focus:ring-2 focus:ring-black/20 focus:border-black/30
                           dark:bg-neutral-900 dark:text-gray-100 dark:border-neutral-700 dark:focus:ring-white/20"
                >
                    <option value="">All brands</option>
                    @foreach($brands as $brandOption)
                        <option value="{{ $brandOption }}">{{ $brandOption }}</option>
                    @endforeach
                </select>
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                    <flux:icon.chevrons-up-down class="h-4 w-4" />
                </span>
            </div>
        </div>

        <!-- Price range -->
        <div class="space-y-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price Range (per day)</label>
            <div class="flex items-center justify-between text-sm">
                <span class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-2 py-1 text-gray-800 dark:bg-neutral-800 dark:text-gray-200">
                    Min: <strong>{{ number_format($priceMin, 2) }} $</strong>
                </span>
                <span class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-2 py-1 text-gray-800 dark:bg-neutral-800 dark:text-gray-200">
                    Max: <strong>{{ number_format($priceMax, 2) }} $</strong>
                </span>
            </div>
            @php
                // Compute percentages for a simple highlighted track bar
                $__total = max(1e-9, ($maxPrice ?? 0) - ($minPrice ?? 0));
                $__startPct = isset($priceMin, $minPrice) ? (($priceMin - $minPrice) / $__total) * 100 : 0;
                $__endPct = isset($priceMax, $minPrice) ? (($priceMax - $minPrice) / $__total) * 100 : 100;
            @endphp

            <div class="relative pt-6 pb-4 px-1">
                <!-- Background track -->
                <div class="absolute left-0 right-0 top-1/2 h-1 -translate-y-1/2 rounded bg-gray-200 dark:bg-neutral-700"></div>
                <!-- Selected range highlight -->
                <div class="absolute top-1/2 h-1 -translate-y-1/2 rounded bg-black dark:bg-white"
                     style="left: calc({{ $__startPct }}%); right: calc({{ 100 - $__endPct }}%);"></div>

                <!-- Lower bound slider -->
                <input
                    type="range"
                    min="{{ $minPrice }}"
                    max="{{ $maxPrice }}"
                    step="5"
                    wire:model.live="priceMin"
                    class="range-thumb peer absolute inset-x-0 -top-1 h-2 w-full appearance-none bg-transparent focus:outline-none"
                />

                <!-- Upper bound slider -->
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
                       class="w-1/2 rounded-lg border border-gray-300 bg-white/80 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-black/20 dark:bg-neutral-900 dark:text-gray-100 dark:border-neutral-700 dark:focus:ring-white/20"
                       placeholder="Min" />
                <span class="text-gray-400">—</span>
                <input type="number" step="5" min="{{ $minPrice }}" max="{{ $maxPrice }}"
                       wire:model.lazy="priceMax"
                       class="w-1/2 rounded-lg border border-gray-300 bg-white/80 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-black/20 dark:bg-neutral-900 dark:text-gray-100 dark:border-neutral-700 dark:focus:ring-white/20"
                       placeholder="Max" />
            </div>
        </div>

        <style>
            /* Minimal thumb styling for range inputs */
            .range-thumb::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; height: 16px; width: 16px; border-radius: 9999px; background: #111827; border: 2px solid white; box-shadow: 0 1px 2px rgba(0,0,0,.2); }
            .range-thumb::-moz-range-thumb { height: 16px; width: 16px; border-radius: 9999px; background: #111827; border: 2px solid white; box-shadow: 0 1px 2px rgba(0,0,0,.2); }
            .range-thumb::-webkit-slider-runnable-track { background: transparent; height: 4px; }
            .range-thumb::-moz-range-track { background: transparent; height: 4px; }
            .dark .range-thumb::-webkit-slider-thumb { background: #ffffff; border-color: #111827; }
            .dark .range-thumb::-moz-range-thumb { background: #ffffff; border-color: #111827; }
        </style>
    </aside>

    <div class="flex-1">
        <h2 class="text-2xl font-bold mb-4">Search Results</h2>
        
        <form
        action="{{ route('search') }}"
        method="GET"
        class="max-w-2xl mx-auto mt-6"
        x-data="{
            type: '{{ request('type', 'model') }}',
            queryText: '{{ request('type') !== 'availability' ? e(request('query', '')) : '' }}',
            queryDate: '{{ request('type') === 'availability' ? e(request('query', '')) : '' }}'
        }"
        >
        <label for="search-type" class="sr-only">Search type</label>
        <!-- Preserve selected filters in GET -->
        <input type="hidden" name="brand" value="{{ $brand }}">
        <input type="hidden" name="price_min" value="{{ $priceMin }}">
        <input type="hidden" name="price_max" value="{{ $priceMax }}">

        <div class="flex w-full">
            <!-- TYPE SELECT -->
            <select
            id="search-type"
            name="type"
            x-model="type"
            class="shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium
                    text-gray-900 bg-gray-100 rounded-l-lg hover:bg-gray-200 focus:ring-4
                    focus:outline-none focus:ring-gray-100 dark:bg-black dark:hover:bg-white
                    dark:hover:text-black dark:focus:ring-gray-700 dark:text-white"
            aria-label="Choose search type"
            >
            <option value="model">Car brand</option>
            <option value="owner">Car owner</option>
            <option value="availability">Availability date</option>
            </select>

            <!-- INPUT WRAPPER -->
            <div class="relative w-full">
            <!-- TEXT SEARCH (model/owner) -->
            <template x-if="type !== 'availability'">
                <input
                type="search"
                name="query"
                x-model="queryText"
                class="block p-3 w-full z-20 text-sm text-gray-900 bg-gray-50 border border-gray-300
                        rounded-r-lg focus:ring-blue-500 dark:bg-black dark:border-black
                        dark:placeholder-gray-200 dark:text-white"
                :placeholder="type === 'model' ? 'Search by car brand…' : 'Search by owner name…'"
                />
            </template>

            <!-- DATE PICKER (availability) -->
            <template x-if="type === 'availability'">
                <input
                type="date"
                name="query"
                x-model="queryDate"
                class="block p-3 w-full z-20 text-sm text-gray-900 bg-gray-50 border border-gray-300
                        rounded-r-lg focus:ring-blue-500 dark:bg-black dark:border-black
                        dark:placeholder-gray-200 dark:text-white"
                />
            </template>

            <!-- SUBMIT BUTTON -->
            <button
                type="submit"
                class="absolute top-0 right-0 p-2.5 text-sm font-medium h-full text-white bg-black
                    rounded-r-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300
                    dark:bg-white dark:text-black"
                aria-label="Search"
            >
                <flux:icon.search class="dark:text-black" />
            </button>
            </div>
        </div>
        </form>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-15 m-10">
            @forelse ($cars as $car)
            <div class="bg-gray-100 dark:bg-black rounded-4xl p-10 text-center shadow-xl/30">
                <h1 class="font-bold text-3xl">{{ $car->brand }} - {{ $car->model }}</h1>
                <h2 class="font-bold text-2xl">{{ $car->year }}</h2>
                <img src="{{ $car->image ? asset('storage/' . $car->image) : 'https://placehold.co/300x200?text=No+Image' }}" alt="{{ $car->brand }}" class="w-full m-5 object-contain h-100">
                <h3 class="font-bold text-2xl">{{ $car->daily_rent }} $/Day</h3>
                <h3 class="font-bold text-2xl"><b>Owner: </b>{{ $car->owner->name }}</h3>
            </div>
            @empty
            <div class="col-span-3 text-center text-gray-500 dark:text-gray-400">
                no cars found.
            </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $cars->links() }}
        </div>

      </div>
</div>
