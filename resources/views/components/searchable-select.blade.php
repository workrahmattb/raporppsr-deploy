@props(['id', 'model', 'options', 'placeholder', 'searchPlaceholder'])

<div
    x-data="{
        open: false,
        search: '',
        value: $wire.entangle(@js($model)),
        options: @js($options),
        get selectedLabel() {
            return this.options.find(option => String(option.value) === String(this.value))?.label ?? @js($placeholder);
        },
        get filteredOptions() {
            const query = this.search.trim().toLocaleLowerCase();
            return this.options.filter(option => option.label.toLocaleLowerCase().includes(query));
        },
        choose(option) {
            this.value = option.value;
            this.open = false;
            this.search = '';
            this.$refs.trigger.focus();
        }
    }"
    @click.outside="open = false"
    @keydown.escape.stop.prevent="open = false; $refs.trigger.focus()"
    class="relative"
>
    <button
        type="button"
        id="{{ $id }}"
        x-ref="trigger"
        @click="open = !open; if (open) { search = ''; $nextTick(() => $refs.search.focus()); }"
        :aria-expanded="open"
        aria-controls="{{ $id }}-options"
        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-left focus:ring-2 focus:ring-blue-500 focus:border-transparent @error($model) border-red-500 @enderror"
    >
        <span x-text="selectedLabel"></span>
    </button>

    <div x-show="open" style="display: none;" id="{{ $id }}-options" class="absolute z-50 mt-1 w-full rounded-lg border border-gray-300 bg-white shadow-lg">
        <div class="p-3 border-b border-gray-200">
            <input
                x-ref="search"
                x-model="search"
                type="search"
                placeholder="{{ $searchPlaceholder }}"
                aria-label="{{ $searchPlaceholder }}"
                autocomplete="off"
                @keydown.enter.prevent="if (filteredOptions.length) { choose(filteredOptions[0]); }"
                @keydown.arrow-down.prevent="$refs.results.querySelector('button')?.focus()"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
            >
        </div>
        <div x-ref="results" class="max-h-60 overflow-y-auto">
            <template x-for="option in filteredOptions" :key="option.value">
                <button
                    type="button"
                    @click="choose(option)"
                    :aria-pressed="String(value) === String(option.value)"
                    class="block w-full px-4 py-3 text-left text-sm text-gray-900 hover:bg-blue-50 focus:bg-blue-50 focus:outline-none"
                    x-text="option.label"
                ></button>
            </template>
            <p x-show="filteredOptions.length === 0" class="px-4 py-3 text-sm text-gray-500">Tidak ada hasil yang cocok.</p>
        </div>
    </div>
</div>
