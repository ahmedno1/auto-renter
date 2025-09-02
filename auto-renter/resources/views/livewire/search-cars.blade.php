{{-- resources/views/livewire/search-cars.blade.php --}}

<section class="flex gap-6">

  <!-- Filters sidebar -->
  <aside class="w-72 shrink-0 space-y-6 p-5 border-4 border-black rounded-4xl bg-gray-100 backdrop-blur-sm shadow-xl/30 dark:bg-black dark:border-white">
    <flux:heading size="l" level="1">{{ __('Search Filters') }}</flux:heading>
    <flux:separator variant="subtle" />

    <!-- Brand select -->
    <div class="space-y-2">
      <div class="flex items-center gap-2">
        <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
        <flux:icon.car class="h-4 w-4" />
      </div>

      <flux:select
        size="sm"
        placeholder="Choose the brand"
        id="brand"
        wire:model.live="brand"
      >
        <flux:select.option value="">All brands</flux:select.option>
        @foreach($brands as $brandOption)
          <flux:select.option value="{{ $brandOption }}">{{ $brandOption }}</flux:select.option>
        @endforeach
      </flux:select>
    </div>

    <!-- Price range -->
    <div class="space-y-3">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price Range (per day)</label>

      <div class="flex items-center justify-between text-sm">
        <span class="inline-flex items-center gap-1 rounded-md bg-black px-2 py-1 text-white dark:bg-white dark:text-black">
          Min: <strong>{{ number_format($priceMin, 2) }} $</strong>
        </span>
        <span class="inline-flex items-center gap-1 rounded-md bg-black px-2 py-1 text-white dark:bg-white dark:text-black">
          Max: <strong>{{ number_format($priceMax, 2) }} $</strong>
        </span>
      </div>

      @php
        $__total = max(1e-9, ($maxPrice ?? 0) - ($minPrice ?? 0));
        $__startPct = isset($priceMin, $minPrice) ? (($priceMin - $minPrice) / $__total) * 100 : 0;
        $__endPct = isset($priceMax, $minPrice) ? (($priceMax - $minPrice) / $__total) * 100 : 100;
      @endphp

      <div class="relative pt-6 pb-4 px-1">
        <!-- Background track -->
        <div class="absolute left-0 right-0 top-1/2 h-1 -translate-y-1/2 rounded bg-black dark:bg-white"></div>

        <!-- Selected range highlight -->
        <div class="absolute top-1/2 h-1 -translate-y-1/2 rounded bg-black dark:bg-black"
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

  <!-- Results + Search -->
  <div class="flex-1">
    <flux:heading size="xl" level="1">{{ __('Search') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('What are you looking for?') }}</flux:subheading>
    <flux:separator variant="subtle" />

    @once
      <!-- Alpine (only once per page) -->
      <script src="//unpkg.com/alpinejs" defer></script>
    @endonce

    <!-- Search form -->
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
                 text-gray-900 bg-gray-100 rounded-l-lg border-2 border-accent border-r-0 hover:bg-gray-200
                 dark:bg-black dark:hover:bg-white dark:hover:text-black dark:focus:ring-gray-700 dark:text-white"
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
              class="block p-3 w-full z-20 text-sm text-gray-900 bg-gray-50 border-2 border-black
                     rounded-r-lg hover:bg-gray-200 dark:bg-black dark:border-white dark:text-white
                     dark:hover:bg-white dark:hover:text-black"
              :placeholder="type === 'model' ? 'Search by car brand…' : 'Search by owner name…'"
              required
            />
          </template>

          <!-- DATE PICKER (availability) -->
          <template x-if="type === 'availability'">
            <input
              type="date"
              name="query"
              x-model="queryDate"
              class="block p-3 w-full z-20 text-sm text-gray-900 bg-gray-50 border border-black
                     rounded-r-lg placeholder-black focus:ring-blue-500 dark:bg-black dark:border-black
                     dark:placeholder-gray-200 dark:text-white"
              required
            />
          </template>

          <!-- SUBMIT BUTTON -->
          <button
            type="submit"
            class="absolute top-0 right-0 p-2.5 text-sm font-medium h-full text-white bg-black
                   rounded-r-lg focus:ring-4 focus:outline-none focus:ring-gray-300 hover:bg-gray-300 hover:text-accent
                   dark:bg-white dark:text-black dark:hover:bg-black"
            aria-label="Search"
          >
            <flux:icon.search />
          </button>
        </div>
      </div>
    </form>

    <!-- Results grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 m-10">
      @forelse ($cars as $car)
        <div class="bg-gray-100 dark:bg-black rounded-4xl p-6 text-center shadow-xl/30 border-4 border-accent">
          <h1 class="font-bold text-2xl">{{ $car->brand }} - {{ $car->model }}</h1>
          <h2 class="font-semibold text-xl">{{ $car->year }}</h2>
          <img
            src="{{ $car->image ? asset('storage/' . $car->image) : 'https://placehold.co/300x200?text=No+Image' }}"
            alt="{{ $car->brand }}"
            class="w-full my-4 object-contain h-56"
          >
          <h3 class="font-bold text-xl">{{ $car->daily_rent }} $/Day</h3>
          <h3 class="font-semibold text-lg"><b>Owner:</b> {{ $car->owner->name }}</h3>
        </div>
      @empty
        <div class="col-span-3 text-center text-gray-500 dark:text-gray-400">
          No cars found.
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
      {{ $cars->links('vendor.pagination.tailwind') }}
    </div>
  </div>
</section>
