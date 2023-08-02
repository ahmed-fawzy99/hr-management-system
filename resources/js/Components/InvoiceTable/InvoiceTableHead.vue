<script setup>
defineProps({
    rounded_l: {
        type: Boolean,
        default: false,
    },
    rounded_r: {
        type: Boolean,
        default: false,
    },
})
</script>
<template>
    <th scope="col" class="px-6 py-3" :class="{'rounded-l-lg': rounded_l, 'rounded-r-lg': rounded_r}">
        <slot />
    </th>
</template>
