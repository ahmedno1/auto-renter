@props([
    'startModel',
    'endModel',
    'disabledDates' => [],
    'minDate' => null,
    'syncEvent' => 'car-details-opened',
    'startLabel' => 'From',
    'endLabel' => 'To',
])

<div
    x-data="dateRangePicker({
        start: @entangle($startModel).live,
        end: @entangle($endModel).live,
        disabledDates: @js($disabledDates),
        minDate: {{ $minDate ? '\'' . $minDate . '\'' : 'null' }},
        syncEvent: {{ $syncEvent ? '\'' . $syncEvent . '\'' : 'null' }},
    })"
    class="space-y-5"
>
    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 dark:text-gray-300">
        <template x-for="legend in legendItems" :key="legend.label">
            <div class="flex items-center gap-2 rounded-full border border-gray-200/80 bg-white/70 px-3 py-1 shadow-sm backdrop-blur dark:border-neutral-700/70 dark:bg-neutral-950/40">
                <span class="inline-flex h-2.5 w-2.5 rounded-full" :class="legend.class"></span>
                <span x-text="legend.label"></span>
            </div>
        </template>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <section class="rounded-2xl border border-gray-200/80 bg-white/80 p-4 shadow-sm backdrop-blur dark:border-neutral-700/70 dark:bg-neutral-950/50">
            <header class="mb-3 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $startLabel }}</h3>
                <div class="flex items-center gap-2">
                    <button type="button" class="rounded-full p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-800 focus:outline-none focus:ring dark:text-gray-300 dark:hover:bg-neutral-800" @click="shiftMonth('start', -1)">
                        <flux:icon.chevron-left class="h-4 w-4" />
                    </button>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-100" x-text="formatMonthLabel(viewStart)"></span>
                    <button type="button" class="rounded-full p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-800 focus:outline-none focus:ring dark:text-gray-300 dark:hover:bg-neutral-800" @click="shiftMonth('start', 1)">
                        <flux:icon.chevron-right class="h-4 w-4" />
                    </button>
                </div>
            </header>

            <div class="grid grid-cols-7 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <template x-for="day in dayNames" :key="'start-d-' + day">
                    <span class="h-8 w-full leading-8" x-text="day"></span>
                </template>
            </div>

            <template x-for="(week, wIndex) in startCalendar" :key="'start-week-' + wIndex">
                <div class="mt-1 grid grid-cols-7 gap-1.5">
                    <template x-for="(day, dIndex) in week" :key="day ? day.formatted : 'start-empty-' + wIndex + '-' + dIndex">
                        <div class="flex justify-center">
                            <button
                                type="button"
                                :class="dayClasses(day, 'start')"
                                @click="selectStart(day)"
                                @mouseenter="if (day && start && !end) hoverDate = day.formatted"
                                @mouseleave="hoverDate = null"
                                :disabled="!day || !isSelectable(day.formatted)"
                                :aria-label="day ? formatDayAriaLabel(day.date) : ''"
                                x-text="day ? day.day : ''"
                            ></button>
                        </div>
                    </template>
                </div>
            </template>
        </section>

        <section class="rounded-2xl border border-gray-200/80 bg-white/80 p-4 shadow-sm backdrop-blur dark:border-neutral-700/70 dark:bg-neutral-950/50">
            <header class="mb-3 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $endLabel }}</h3>
                <div class="flex items-center gap-2">
                    <button type="button" class="rounded-full p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-800 focus:outline-none focus:ring dark:text-gray-300 dark:hover:bg-neutral-800" @click="shiftMonth('end', -1)">
                        <flux:icon.chevron-left class="h-4 w-4" />
                    </button>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-100" x-text="formatMonthLabel(viewEnd)"></span>
                    <button type="button" class="rounded-full p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-800 focus:outline-none focus:ring dark:text-gray-300 dark:hover:bg-neutral-800" @click="shiftMonth('end', 1)">
                        <flux:icon.chevron-right class="h-4 w-4" />
                    </button>
                </div>
            </header>

            <div class="grid grid-cols-7 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                <template x-for="day in dayNames" :key="'end-d-' + day">
                    <span class="h-8 w-full leading-8" x-text="day"></span>
                </template>
            </div>

            <template x-for="(week, wIndex) in endCalendar" :key="'end-week-' + wIndex">
                <div class="mt-1 grid grid-cols-7 gap-1.5">
                    <template x-for="(day, dIndex) in week" :key="day ? day.formatted : 'end-empty-' + wIndex + '-' + dIndex">
                        <div class="flex justify-center">
                            <button
                                type="button"
                                :class="dayClasses(day, 'end')"
                                @click="selectEnd(day)"
                                @mouseenter="if (day && start && !end) hoverDate = day.formatted"
                                @mouseleave="hoverDate = null"
                                :disabled="!day || !start || compareDates(day.formatted, start) < 0 || !isSelectable(day.formatted)"
                                :aria-label="day ? formatDayAriaLabel(day.date) : ''"
                                x-text="day ? day.day : ''"
                            ></button>
                        </div>
                    </template>
                </div>
            </template>
        </section>
    </div>

    <template x-if="rangeError">
        <div class="text-sm font-medium text-red-600" x-text="rangeError"></div>
    </template>

    <div class="flex flex-col gap-3 text-sm text-gray-600 dark:text-gray-200 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap items-center gap-2">
            <span class="font-semibold">Chosen range:</span>
            <span class="inline-flex items-center rounded-full border border-gray-200/80 bg-white/70 px-3 py-1 text-xs font-semibold text-gray-700 shadow-sm backdrop-blur dark:border-neutral-700/70 dark:bg-neutral-950/40 dark:text-gray-100" x-text="start ?? '—'"></span>
            <span class="text-gray-400">→</span>
            <span class="inline-flex items-center rounded-full border border-gray-200/80 bg-white/70 px-3 py-1 text-xs font-semibold text-gray-700 shadow-sm backdrop-blur dark:border-neutral-700/70 dark:bg-neutral-950/40 dark:text-gray-100" x-text="end ?? '—'"></span>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" class="text-xs font-semibold uppercase tracking-wide text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200" @click="clearSelection()">
                Clear
            </button>
        </div>
    </div>
</div>
