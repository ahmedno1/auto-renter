<div>
    <flux:modal name="create-car" class="md:w-900">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add new car</flux:heading>
                <flux:text class="mt-2">Add a car</flux:text>
            </div>

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

            <flux:input
                label="status"
                wire:model="status"
                placeholder="choose status" />

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="save">Save</flux:button>
            </div>
        </div>
    </flux:modal>
</div>