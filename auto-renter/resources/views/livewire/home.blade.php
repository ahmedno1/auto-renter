<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
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
                <img src="/image/car.png" alt="Car" class="w-full max-w-5xl mx-auto relative z-10 ">
            </div>
        </div>
    </section>

    <!-- the pop out car details -->
    <section>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @forelse ($cars as $car)
            <div class="bg-white dark:bg-slate-800 rounded shadow p-2">
                <img src="{{ $car->image ? asset('storage/' . $car->image) : 'https://placehold.co/300x200?text=No+Image' }}"
                    alt="{{ $car->brand }}"
                    class="w-full h-32 object-cover rounded">
                <div class="mt-2 font-bold text-lg">{{ $car->brand }}</div>
                <div class="text-sm text-gray-600">{{ $car->model }} - {{ $car->year }}</div>
                <flux:button wire:click="showCar({{ $car->id }})" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded">
                    عرض التفاصيل
                </flux:button>
            </div>
            @empty
            <div class="col-span-3 text-center text-gray-500 dark:text-gray-400">
                لا توجد سيارات حالياً.
            </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $cars->links() }}
        </div>

        <!-- Modal -->
        <flux:modal name="car-details">
            @if ($selectedCar)
            <div class="p-4 space-y-2">
                <img src="{{ $selectedCar->image ? asset('storage/' . $selectedCar->image) : 'https://placehold.co/600x300?text=No+Image' }}"
                    alt="{{ $selectedCar->brand }}"
                    class="w-full h-40 object-cover rounded mb-2">

                <div class="font-bold text-xl">{{ $selectedCar->brand }} - {{ $selectedCar->model }}</div>
                <div class="text-sm text-gray-600">السنة: {{ $selectedCar->year }}</div>
                <div class="text-sm text-gray-600">السعر اليومي: ${{ number_format($selectedCar->daily_rent, 2) }}</div>
                <div class="text-sm text-gray-600">الوصف: {{ $selectedCar->description }}</div>
                <div class="text-sm text-gray-600">الحالة: {{ $selectedCar->status }}</div>

                <flux:button class="mt-4 px-4 py-2 bg-green-600 text-white rounded">
                    استئجار
                </flux:button>
            </div>
            @endif
        </flux:modal>
    </section>

</div>