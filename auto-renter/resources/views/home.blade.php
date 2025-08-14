<x-layouts.app :title="__('Cars')">
    <table class="overflow-hidden w-full bg-white dark:bg-slate-800 shadow-md rounded-lg mt-5">
        <thead class="bg-zinc-200 dark:bg-zinc-900">
            <tr>
                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">Image</th>
                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">Brand</th>
                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">Model</th>
                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">Year</th>                
                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">Daily rate</th>
                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">status</th>
                <th class="px-4 py-2 text-center text-gray-900 dark:text-gray-200">Action</th>
            </tr>
        </thead>
        <tbody class="bg-zinc-100 dark:bg-zinc-700">
            <tr class="border-t border-gray-300 dark:border-gray-700">
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100"></td>
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100"></td>
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100"></td>
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100"></td>
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100"></td>
                <td class="px-4 py-2 text-gray-900 dark:text-gray-100"></td>
                <td class="px-4 py-2 text-center space-x-2">
                    <flux:button variant="primary">Edit</flux:button>
                    <flux:button variant="danger">Delete</flux:button>
                </td>
            </tr>
            <tr>
                <td colspan="7" class="px-4 py-2 text-center text-gray-500 dark:text-gray-100">
                    No Cars yet
                </td>
            </tr>
        </tbody>
    </table>
</x-layouts.app>
