<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <option :value="value"> <slot /> </option>
</template>
