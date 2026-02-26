<div>
    <flux:modal name="create-car" class="md:w-900">
        <div class="space-y-6">
            <form class="space-y-6" wire:submit.prevent="save">
                <div>
                    <flux:heading size="lg">Add new car</flux:heading>
                    <flux:text class="mt-2">Add a car</flux:text>
                </div>

                <flux:input
                    type="file"
                    wire:model="image"
                    placeholder="Upload car image" />
                @if ($image)
                <img src="{{ $image->temporaryUrl() }}" width="100">
                @endif

                <flux:input
                    label="brand"
                    wire:model="brand"
                    placeholder="Enter brand" />

                <flux:input
                    label="model"
                    wire:model="model"
                    placeholder="Enter model" />

                <flux:input
                    label="year"
                    wire:model="year"
                    placeholder="Enter year" />

                <flux:input
                    label="daily_rent"
                    wire:model="daily_rent"
                    placeholder="Enter daily_rent" />

                <flux:textarea
                    label="Description"
                    wire:model="description"
                    placeholder="Enter task description" />

                <flux:radio.group wire:model="status" :label="('Status')" variant="segmented">
                    <flux:radio label="Available" value="available" />
                    <flux:radio label="Unavailable" value="unavailable" />
                </flux:radio.group>

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary" >Save</flux:button>
                </div>
            </form>
        </div>

    </flux:modal>
</div>
