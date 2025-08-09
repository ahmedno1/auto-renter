<x-layouts.app :title="__('Cars')">
    <table class="table-auto w-full bg-white dark:bg-slate-800 shadow-md rounded-md mt-5">
        <thead class="bg-violet-200 dark:bg-zinc-900">
            <tr>
                <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Name</th>
                <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Type</th>
                <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Color</th>
                <th class="px-4 py-2 text-center text-gray-700 dark:text-gray-200">Action</th>
            </tr>
        </thead>
        <tbody class="bg-violet-200 dark:bg-zinc-700">
            <tr class="border-t border-gray-300 dark:border-gray-700">
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100"></td>
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100"></td>
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100"></td>
                <td class="px-4 py-2 text-center space-x-2">
                    <flux:button variant="primary">Edit</flux:button>
                    <flux:button variant="danger">Delete</flux:button>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                    No Cars yet
                </td>
            </tr>
        </tbody>
    </table>
</x-layouts.app>
