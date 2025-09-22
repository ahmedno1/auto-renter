const DAY_NAMES = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];

const pad = (value) => value.toString().padStart(2, '0');
const toIso = (date) => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
const parseIso = (value) => (value ? new Date(`${value}T00:00:00`) : null);
const startOfMonth = (date) => new Date(date.getFullYear(), date.getMonth(), 1);
const addMonths = (date, amount) => new Date(date.getFullYear(), date.getMonth() + amount, 1);

document.addEventListener('alpine:init', () => {
    if (!window.Alpine) {
        return;
    }

    window.Alpine.data('dateRangePicker', (options = {}) => ({
        start: options.start ?? null,
        end: options.end ?? null,
        disabledDates: options.disabledDates ?? [],
        disabledSet: new Set(options.disabledDates ?? []),
        minDate: options.minDate ? parseIso(options.minDate) : null,
        dayNames: DAY_NAMES,
        viewStart: options.start ? startOfMonth(parseIso(options.start)) : startOfMonth(new Date()),
        viewEnd: options.end ? startOfMonth(parseIso(options.end)) : startOfMonth(addMonths(new Date(), 1)),
        hoverDate: null,
        rangeError: null,
        legendItems: [
            { label: 'Booked', class: 'bg-red-500' },
            { label: 'Selected', class: 'bg-gray-900' },
            { label: 'In range', class: 'bg-green-200 border border-green-500' },
        ],

        init() {
            this.syncViews();

            this.$watch('start', (value) => {
                if (!value) {
                    return;
                }

                if (!this.isSelectable(value)) {
                    this.start = null;
                    return;
                }

                const startDate = parseIso(value);
                this.viewStart = startOfMonth(startDate);

                if (this.end && this.compareDates(this.end, value) < 0) {
                    this.end = null;
                }

                if (this.end && this.hasConflict(value, this.end)) {
                    this.end = null;
                    this.rangeError = 'Selected range contains booked dates.';
                }
            });

            this.$watch('end', (value) => {
                if (!value) {
                    return;
                }

                if (!this.start) {
                    if (this.isSelectable(value)) {
                        this.start = value;
                    }
                    return;
                }

                if (this.compareDates(value, this.start) < 0) {
                    this.end = null;
                    return;
                }

                if (!this.isSelectable(value) || this.hasConflict(this.start, value)) {
                    this.rangeError = 'Selected range contains booked dates.';
                    this.end = null;
                    return;
                }

                this.rangeError = null;
                this.viewEnd = startOfMonth(parseIso(value));
            });

            if (options.syncEvent) {
                window.addEventListener(options.syncEvent, (event) => this.applySync(event.detail ?? {}));
            }
        },

        applySync(detail) {
            if (Array.isArray(detail.disabledDates)) {
                this.setDisabledDates(detail.disabledDates);
            }

            if (detail.start !== undefined) {
                this.start = detail.start || null;
            }

            if (detail.end !== undefined) {
                this.end = detail.end || null;
            }

            this.syncViews();
            this.rangeError = null;
        },

        syncViews() {
            this.viewStart = this.start ? startOfMonth(parseIso(this.start)) : startOfMonth(new Date());

            if (this.end) {
                this.viewEnd = startOfMonth(parseIso(this.end));
            } else if (this.start) {
                this.viewEnd = startOfMonth(parseIso(this.start));
            } else {
                this.viewEnd = startOfMonth(addMonths(new Date(), 1));
            }
        },

        shiftMonth(target, step) {
            if (target === 'start') {
                this.viewStart = startOfMonth(addMonths(this.viewStart, step));
                return;
            }

            this.viewEnd = startOfMonth(addMonths(this.viewEnd, step));
        },

        setDisabledDates(list) {
            this.disabledDates = list;
            this.disabledSet = new Set(list);

            if (this.start && !this.isSelectable(this.start)) {
                this.start = null;
            }

            if (this.end && (!this.start || !this.isSelectable(this.end) || this.hasConflict(this.start, this.end))) {
                this.end = null;
            }
        },

        selectStart(day) {
            if (!day || !day.formatted) {
                return;
            }

            const value = day.formatted;

            if (!this.isSelectable(value)) {
                return;
            }

            this.start = value;

            if (this.end && this.compareDates(this.end, value) < 0) {
                this.end = null;
            }

            if (this.end && this.hasConflict(value, this.end)) {
                this.end = null;
            }

            this.rangeError = null;
        },

        selectEnd(day) {
            if (!day || !day.formatted || !this.start) {
                return;
            }

            const value = day.formatted;

            if (this.compareDates(value, this.start) < 0 || !this.isSelectable(value)) {
                return;
            }

            if (this.hasConflict(this.start, value)) {
                this.rangeError = 'Selected range contains booked dates.';
                return;
            }

            this.end = value;
            this.rangeError = null;
        },

        clearSelection() {
            this.start = null;
            this.end = null;
            this.rangeError = null;
        },

        buildCalendar(view) {
            const first = startOfMonth(view);
            const daysInMonth = new Date(first.getFullYear(), first.getMonth() + 1, 0).getDate();
            const startWeekday = first.getDay();
            const days = [];

            for (let i = 0; i < startWeekday; i += 1) {
                days.push(null);
            }

            for (let day = 1; day <= daysInMonth; day += 1) {
                const date = new Date(first.getFullYear(), first.getMonth(), day);
                days.push({
                    day,
                    date,
                    formatted: toIso(date),
                });
            }

            while (days.length % 7 !== 0) {
                days.push(null);
            }

            const weeks = [];

            for (let i = 0; i < days.length; i += 7) {
                weeks.push(days.slice(i, i + 7));
            }

            return weeks;
        },

        get startCalendar() {
            return this.buildCalendar(this.viewStart);
        },

        get endCalendar() {
            return this.buildCalendar(this.viewEnd);
        },

        dayState(value) {
            if (!value) {
                return 'empty';
            }

            if (this.isDisabled(value)) {
                return 'booked';
            }

            if (this.minDate && this.compareDates(value, toIso(this.minDate)) < 0) {
                return 'past';
            }

            return 'free';
        },

        isSelectable(value) {
            if (!value) {
                return false;
            }

            if (this.isDisabled(value)) {
                return false;
            }

            if (this.minDate && this.compareDates(value, toIso(this.minDate)) < 0) {
                return false;
            }

            return true;
        },

        isDisabled(value) {
            return this.disabledSet.has(value);
        },

        isStart(value) {
            return this.start === value;
        },

        isEnd(value) {
            return this.end === value;
        },

        isInRange(value) {
            if (!this.start || !this.end) {
                return false;
            }

            return this.compareDates(value, this.start) > 0 && this.compareDates(value, this.end) < 0;
        },

        compareDates(a, b) {
            if (!a || !b) {
                return 0;
            }

            if (a === b) {
                return 0;
            }

            return a < b ? -1 : 1;
        },

        hasConflict(start, end) {
            if (!start || !end) {
                return false;
            }

            let current = parseIso(start);
            const finish = parseIso(end);

            if (!current || !finish) {
                return false;
            }

            while (current <= finish) {
                if (this.disabledSet.has(toIso(current))) {
                    return true;
                }

                current.setDate(current.getDate() + 1);
            }

            return false;
        },

        formatMonthLabel(date) {
            return date.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });
        },

        dayClasses(day, type) {
            if (!day) {
                return 'h-9';
            }

            const value = day.formatted;
            const state = this.dayState(value);
            const base = 'h-9 flex items-center justify-center rounded-full text-sm';

            if (state === 'booked') {
                return `${base} bg-red-500 text-white cursor-not-allowed`;
            }

            if (state === 'past') {
                return `${base} bg-gray-200 text-gray-400 cursor-not-allowed`;
            }

            if ((type === 'start' && this.isStart(value)) || (type === 'end' && this.isEnd(value))) {
                return `${base} bg-gray-900 text-white font-semibold`;
            }

            if (this.isInRange(value)) {
                return `${base} bg-green-200 text-green-900`;
            }

            return `${base} hover:bg-gray-900 hover:text-white cursor-pointer`;
        },
    }));
});