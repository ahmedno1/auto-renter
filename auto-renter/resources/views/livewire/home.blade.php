<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- Hero section -->
    <section class="relative bg-black text-white rounded-b-[100px] sm:rounded-b-[200px] md:rounded-b-[50%]">
        <div class="container mx-auto px-6 lg:px-16 py-16 flex flex-col-reverse lg:flex-row items-center gap-12">
            <div class="flex-1 text-center lg:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                    Rent the <span class="text-[#d24122]"> Best Car </span><br> Around the World
                </h1>
                <p class="mt-4 text-lg text-gray-300">
                    We provide the best car options and expert services to ensure the greatest customer experience.
                </p>
                <div class="mt-6 flex flex-wrap justify-center lg:justify-start gap-4">
                    <a href="#cars" class=" bg-white hover:bg-[#d24122] hover:text-white text-black px-6 py-3 rounded-full shadow-lg transition">
                        Rent a Car
                    </a>
                    <a href="#learn-more" class="border border-gray-400 hover:border-white px-6 py-3 rounded-full transition">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="flex-1 relative">
                <img src="/image/redCar.png" alt="Red car" class="w-full max-w-5xl mx-auto relative">
            </div>
        </div>
    </section>

    <!-- About us section-->
    <section class="py-12 mb-15">

        <h2 class="text-2xl md:text-7xl font-bold text-center mb-15">Why Auto Renter</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto px-6">

            <!-- Card 1 -->
            <div class="bg-black text-white shadow rounded-lg p-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="bg-gray-100 p-3 rounded-full">
                        <flux:icon.key-square class="text-[#d24122]" />
                    </div>
                </div>
                <h3 class="text-lg font-bold mb-2">for the Owner</h3>
                <p class="text-gray-500 text-sm">
                    Auto Renter provides a platform for car owners to rent their cars and also helps in the rental management process by organizing appointments, etc.
                </p>
            </div>

            <!-- Card 2 (highlighted) -->
            <div class="bg-gray-100 text-black shadow-lg rounded-lg p-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="bg-black p-3 rounded-full">
                        <flux:icon.lightbulb class="text-yellow-200" />
                    </div>
                </div>
                <h3 class="text-lg font-bold mb-2">our goal</h3>
                <p class="text-black text-sm">
                    The Auto Renter app is designed to make the car rental process easier for both customers and car owners.
                </p>
            </div>

            <!-- Card 3 -->
            <div class="bg-black text-white shadow rounded-lg p-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="bg-gray-100 p-3 rounded-full">
                        <flux:icon.person-standing class="text-[#d24122]" />
                    </div>
                </div>
                <h3 class="text-lg font-bold mb-2">customers</h3>
                <p class="text-gray-500 text-sm">
                    The Auto Rental app provides a platform for customers to rent cars and makes it easier for them to search for a suitable car or find it at the right time.
                </p>
            </div>

        </div>
    </section>

    <!-- Cars section-->

    <section class="mb-15">

        <h2 class="text-2xl md:text-7xl font-bold text-center mb-15">Cars</h2>

        <form class="max-w-2xl mx-auto m-15">
            <div class="flex">
                <label for="search-dropdown" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Your Email</label>
                <button id="dropdown-button" data-dropdown-toggle="dropdown" class="shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 rounded-s-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-black dark:hover:bg-white dark:hover:text-black dark:focus:ring-gray-700 dark:text-white" type="button">Sorted by</button>
                <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-black">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
                        <li>
                            <button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mockups</button>
                        </li>
                        <li>
                            <button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Templates</button>
                        </li>
                        <li>
                            <button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Design</button>
                        </li>
                        <li>
                            <button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logos</button>
                        </li>
                    </ul>
                </div>
                <div class="relative w-full">
                    <input type="search" id="search-dropdown" class="block p-3 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 dark:bg-black dark:border-black  dark:placeholder-gray-200 dark:text-white" placeholder="Search Mockups, Logos, Design Templates..." required />
                    <button type="submit" class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-black rounded-e-lg hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-white">
                        <flux:icon.search class="dark:text-black" />
                        <span class="sr-only">Search</span>
                    </button>
                </div>
            </div>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @forelse ($cars as $car)
            <div class="bg-white dark:bg-slate-800 rounded shadow p-2">
                <img src="{{ $car->image ? asset('storage/' . $car->image) : 'https://placehold.co/300x200?text=No+Image' }}"
                    alt="{{ $car->brand }}"
                    class="w-full object-cover rounded">
                <div class="mt-2 font-bold text-lg">{{ $car->brand }}</div>
                <div class="text-sm">{{ $car->model }} - {{ $car->year }}</div>
                <div class="text-sm"><b>Owner: </b>{{ $car->owner->name }}</div>
                <flux:button wire:click="showCar({{ $car->id }})" class="mt-2 px-3 py-1 bg-gray-600 text-white rounded">
                    Show details
                </flux:button>
            </div>
            @empty
            <div class="col-span-3 text-center text-gray-500 dark:text-gray-400">
                no cars yet.
            </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $cars->links() }}
        </div>

        <!-- the pop out car details -->
        <flux:modal name="car-details">
            @if ($selectedCar)
            <div class="p-4 space-y-2">
                <img src="{{ $selectedCar->image ? asset('storage/' . $selectedCar->image) : 'https://placehold.co/600x300?text=No+Image' }}"
                    alt="{{ $selectedCar->brand }}"
                    class="w-full object-cover rounded mb-2">

                <div class="font-bold text-xl">{{ $selectedCar->brand }} - {{ $selectedCar->model }}</div>
                <div class="text-sm">year: {{ $selectedCar->year }}</div>
                <div class="text-sm">daily rent: ${{ number_format($selectedCar->daily_rent, 2) }}</div>
                <div class="text-sm">description: {{ $selectedCar->description }}</div>
                <div class="text-sm">status: {{ $selectedCar->status }}</div>
                <div class="text-sm">owner: {{ $selectedCar->owner->name }}</div>

                @if ($selectedCar->status === 'available')
                <div class="space-y-2 mt-4">
                    <label>from date:</label>
                    <flux:input type="date" wire:modal="start_date" />

                    <label>to date:</label>
                    <flux:input type="date" wire:modal="end_date" />

                    <flux:button wire:click="rent" class="bg-green-600 text-white px-4 py-2 rounded">
                        Confirm renting
                    </flux:button>
                </div>
                @else
                <div class="text-red-600 text-sm mt-4">
                    Car is not available for renting in this time.<br>
                    Rent time for the car:
                    <ul class="mt-2 list-disc list-inside text-gray-700">
                        @foreach($selectedCar->reservations as $r)
                        <li>{{ $r->start_date }} to {{ $r->end_date }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endif
        </flux:modal>
    </section>

</div>