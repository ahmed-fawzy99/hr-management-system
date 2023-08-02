<script setup>
defineProps({
    sortable: {
        type: Boolean,
        default: false
    },
})
</script>
<template>
    <th scope="col" class="px-6 py-3" >
        <span :class="{'cursor-pointer hover:underline': sortable}">
            <slot />
        </span>
    </th>
</template>
