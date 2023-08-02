<script setup>
import {onMounted} from "vue";
import {initTooltips} from "flowbite";

defineProps({
    direction: {
        type: String,
        default: 'bottom',
    },
});

const tooltipID = Math.random().toString(36).substr(2, 5);

onMounted(() => {
    initTooltips();
});
</script>

<template>
    <span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="gray"
             class="inline w-5 h-5 mx-1 mb-0.5 cursor-help"
             :data-tooltip-target="tooltipID"
             :data-tooltip-placement="direction">
            <path fill-rule="evenodd"
                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.94 6.94a.75.75 0 11-1.061-1.061 3 3 0 112.871 5.026v.345a.75.75 0 01-1.5 0v-.5c0-.72.57-1.172 1.081-1.287A1.5 1.5 0 108.94 6.94zM10 15a1 1 0 100-2 1 1 0 000 2z"
                  clip-rule="evenodd"/>
        </svg>
    </span>
    <div :id="tooltipID" role="tooltip"
         class="absolute z-50 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900
         rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-200 dark:text-gray-800">
        <slot />
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
</template>

