<script setup>
defineProps({
    href: String,
    text: String,
})
</script>
<template>
    <button
        class=" text-gray-900 bg-white hover:bg-gray-50 border border-gray-200 focus:ring-2 focus:outline-none focus:ring-purple-500 font-medium rounded-lg text-sm px-5 p-2 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700"
    >
        <slot />
        <p> ‏‏‎ {{ text }}</p>

    </button>
</template>
