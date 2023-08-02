<script setup>

defineProps({
    for_id: String,
    labelText: String,
    chooseText: String,
});

</script>

<template>
    <label :for="for_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ labelText }}</label>
    <select :id="for_id" class="fancy-selector">
        <option selected value="">{{ chooseText }}</option>
        <slot/>
    </select>

</template>

