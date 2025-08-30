<!-- Filters section-->
<section class="flex gap-6">
    <aside class="w-1/4 space-y-4 m-5">
        <h2 class="text-xl font-bold mb-4">Filters</h2>
        <div>
            <label for="brand" class="block mb-2">Brand</label>
            <select id="brand" wire:model="brand" class="w-full p-2 border-2 rounded border-accent bg-gray-50 dark:bg-black dark:text-white">
                <option value="">All</option>
                @foreach($brands as $brandOption)
                    <option value="{{ $brandOption }}">{{ $brandOption }}</option>
                @endforeach
            </select>
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
    <div class="w-3/4">
        <h2 class="text-2xl font-bold m-5">Search Results</h2>

        <!-- Add Alpine if you don't already have it somewhere in your layout -->
<script src="//unpkg.com/alpinejs" defer></script>

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

  <div class="flex w-full">
    <!-- TYPE SELECT -->
    <select
      id="search-type"
      name="type"
      x-model="type"
      class="shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium
            text-gray-900 bg-gray-100 rounded-l-lg border-2 border-accent border-r-0 hover:bg-gray-200 dark:bg-black dark:hover:bg-white
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
          class="block p-3 w-full z-20 text-sm text-gray-900 bg-gray-50 border-2 border-black
                  rounded-r-lg hover:bg-gray-200
                dark:bg-black dark:border-white dark:text-white dark:hover:bg-white  dark:hover:text-black"
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
                rounded-r-lg before-2 focus:ring-4 focus:outline-none focus:ring-gray-300 hover:bg-gray-300 hover:text-accent
              dark:bg-white dark:text-black  dark:hover:bg-black"
        aria-label="Search"
      >
        <flux:icon.search />
      </button>
    </div>
  </div>
</form>


        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-15 m-10">
            @forelse ($cars as $car)
            <div class="bg-gray-100 dark:bg-black rounded-4xl p-10 text-center shadow-xl/30 border-4 border-accent">
                <h1 class="font-bold text-3xl">{{ $car->brand }} - {{ $car->model }}</h1>
                <h2 class="font-bold text-2xl">{{ $car->year }}</h2>
                <img src="{{ $car->image ? asset('storage/' . $car->image) : 'https://placehold.co/300x200?text=No+Image' }}" alt="{{ $car->brand }}" class="w-full m-5 object-contain h-70">
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
            {{ $cars->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</section>
