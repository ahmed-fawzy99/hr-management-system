<script setup>

const props = defineProps({
    data: Object,
    isSlot: {
        type: Boolean,
        default: false,
    },
    colNum: Number,
});

</script>

<template>
    <div class="overflow-hidden sm:rounded-lg">
        <dl>
            <div  :class="'grid grid-cols-' + props.colNum">
                <div v-for="(value, key, index) in data" :key="key"
                     :class="{
                    'px-4 py-3.5': true,
                    ['col-span-' + value[1]]: value[1],
                    'bg-gray-50 dark:bg-gray-900': Math.floor(index / colNum) % 2 === 0,
                    'bg-white dark:bg-gray-800': Math.floor(index / colNum) % 2 !== 0,
                }">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400" v-html="key"></dt>
                    <dd class="mt-1 text-sm" v-html="value[0] ?? __('N/A')"></dd>
                </div>
            </div>
        </dl>
    </div>
</template>
