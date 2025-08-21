<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- Hero section -->
    <section class="relative bg-black text-white rounded-b-[100px] sm:rounded-b-[200px] md:rounded-b-[50%]">
        <div class="container mx-auto px-6 lg:px-16 py-16 flex flex-col-reverse lg:flex-row items-center gap-12">
            <div class="flex-1 text-center lg:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                    Rent the Best Car <br> Around the World
                </h1>
                <p class="mt-4 text-lg text-gray-300">
                    We provide the best car options and expert services to ensure the greatest customer experience.
                </p>
                <div class="mt-6 flex flex-wrap justify-center lg:justify-start gap-4">
                    <a href="#cars" class="border bg-white hover:bg-black hover:text-white hover:border-gray-400 text-black px-6 py-3 rounded-full shadow-lg transition">
                        Rent a Car
                    </a>
                    <a href="#learn-more" class="border border-gray-400 hover:border-white px-6 py-3 rounded-full transition">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="flex-1 relative">
                <img src="/image/car.png" alt="Car" class="w-full max-w-5xl mx-auto relative">
            </div>
        </div>
    </section>

    <!-- About us section-->
    <section class="py-12">
        <div class="text-center mb-15">
            <h2 class="text-2xl md:text-7xl font-bold">About us</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto px-6">
        
        <!-- Card 1 -->
        <div class="bg-white shadow rounded-lg p-8 text-center">
            <div class="flex justify-center mb-4">
            <div class="bg-purple-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="h-8 w-8 text-purple-600" 
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M5 13l4 4L19 7" />
                </svg>
            </div>
            </div>
            <h3 class="text-lg font-bold mb-2">Fast & Easy Booking</h3>
            <p class="text-gray-500 text-sm">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
        </div>
      
      <!-- Card 2 (highlighted) -->
      <div class="bg-black text-white shadow-lg rounded-lg p-8 text-center">
        <div class="flex justify-center mb-4">
          <div class="bg-orange-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-8 w-8 text-orange-500" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7z" />
            </svg>
          </div>
        </div>
        <h3 class="text-lg font-bold mb-2">Many Pickup Location</h3>
        <p class="text-gray-300 text-sm">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        </p>
      </div>
      
      <!-- Card 3 -->
      <div class="bg-white shadow rounded-lg p-8 text-center">
        <div class="flex justify-center mb-4">
          <div class="bg-green-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-8 w-8 text-green-600" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z" />
            </svg>
          </div>
        </div>
        <h3 class="text-lg font-bold mb-2">Satisfied Customers</h3>
        <p class="text-gray-500 text-sm">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        </p>
      </div>

    </div>
  </section>


    <section style="margin-top: 40px; padding: 30px;">
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

                <flux:button class="mt-4 px-4 py-2 bg-green-600 text-white rounded">
                    Rent now!!
                </flux:button>
            </div>
            @endif
        </flux:modal>
    </section>

</div>