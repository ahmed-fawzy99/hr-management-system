<script setup>
defineProps({
    colored: {
        type: Boolean,
        required: false,
    },
})
</script>

<template>
    <div class="px-4 py-3.5 col-span-1" :class="{'bg-gray-50 dark:bg-gray-900': colored}">
        <slot/>
    </div>
</template>
