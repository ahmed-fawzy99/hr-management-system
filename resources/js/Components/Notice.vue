
<script setup>
import {computed, defineProps} from 'vue';

const { type, br, bold, text } = defineProps({
    type: {
        type: String,
        default: 'info',
    },
    br: {
        type: Boolean,
        default: false,
    },
    bold: String,
    text: String,
});

const alertClasses = computed(() => {
    let baseClass = "flex items-center p-4 mb-4 text-sm rounded-lg";
    switch (type) {
        case "danger":
            return baseClass + " text-red-800 border border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800";
        case "success":
            return baseClass + " text-green-800 border border-green-300 bg-green-50 dark:text-green-400 dark:bg-gray-800 dark:border-green-800";
        case "warning":
            return baseClass + " text-yellow-800 border border-yellow-300 bg-yellow-50 dark:text-yellow-300 dark:bg-gray-800 dark:border-yellow-800";
        case "dark":
            return baseClass + " text-gray-800 border border-gray-300 bg-gray-50 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-600";
        default:
            return baseClass + " text-blue-800 border border-blue-300 bg-blue-50 dark:text-blue-400 dark:bg-gray-800 dark:border-blue-800";
    }
});

const iconColor = 'currentColor';
</script>

<template>
    <div class="my-4">
        <div :class="alertClasses" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 ltr:mr-3 rtl:ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                 :fill="iconColor" viewBox="0 0 20 20">
                <path v-if="type === 'danger'" d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                <path v-else d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium">{{ bold + ' '}}</span><br v-if="br" />
                <span v-html="text"></span>
            </div>
        </div>
    </div>
</template>

