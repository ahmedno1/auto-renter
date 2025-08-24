<div class="flex gap-6">
    <aside class="w-1/4 space-y-4">
        <h2 class="text-xl font-bold mb-4">Filters</h2>
        <div>
            <label for="brand" class="block mb-2">Brand</label>
            <select id="brand" wire:model="brand" class="w-full p-2 border rounded bg-gray-50 dark:bg-black dark:text-white">
                <option value="">All</option>
                @foreach($brands as $brandOption)
                    <option value="{{ $brandOption }}">{{ $brandOption }}</option>
                @endforeach
            </select>
        </div>
    </aside>
    <div class="w-3/4">
        <h2 class="text-2xl font-bold mb-4">Search Results</h2>
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
