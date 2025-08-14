<div>
    <flux:modal name="create-car" class="md:w-900">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add new car</flux:heading>
                <flux:text class="mt-2">Add a car</flux:text>
            </div>


                <flux:input
                    label="Address"
                    wire:model="address"
                    placeholder="Enter location address" />

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary" class="bg-violet-400 dark:bg-violet-700 dark:text-white" wire:click="save">Save</flux:button>
                </div>
        </div>
    </flux:modal>
</div>