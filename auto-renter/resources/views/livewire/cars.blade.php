<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Cars') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Manage Cars') }}</flux:subheading>
    <flux:separator variant="subtle" />

    <flux:modal.trigger name="create-car">
        <flux:button class="mt-4">Create car</flux:button>
    </flux:modal.trigger>

    @session('success')
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => { show = false }, 3000)"
        class="fixed top-5 right-5 bg-green-600 text-white text-sm p-4 rounded-lg shadow-lg z-50"
        role="alert">
        <p>{{ $value }}</p>
    </div>
    @endsession

    <livewire:create-car />

    <table class="overflow-hidden w-full bg-white dark:bg-slate-800 shadow-md rounded-lg mt-5">
        <thead class="bg-zinc-200 dark:bg-zinc-900">
            <tr>
                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">Brand</th>
                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">Model</th>
                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">Year</th>
                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">Daily rate</th>
                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">description</th>
                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">status</th>
                <th class="px-4 py-2 text-center text-gray-900 dark:text-gray-200">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y bg-zinc-100 dark:bg-zinc-700">
            @forelse ($cars as $car)
            <tr class="border-t border-gray-300 dark:border-gray-700">
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $car->brand }}</td>
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $car->model }}</td>
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $car->year }}</td>
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $car->daily_rent }}</td>
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $car->description }}</td>
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $car->status }}</td>
                <td class="px-4 py-2 text-center space-x-2">
                    <flux:button wire:click="edit({{ $car->id }})">Edit</flux:button>
                    <flux:button variant="danger" wire:click="delete({{ $car->id }})">Delete</flux:button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                    No cars yet
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $cars->links() }}
    </div>

    <flux:modal name="delete-car" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete car?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this car.</p>
                    <p>The deleted car will be gone forever!</p>
                </flux:text>
            </div>
            <div>
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="deletecar()">Delete car</flux:button>
            </div>
        </div>
    </flux:modal>
</div>