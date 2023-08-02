<script setup>
defineProps({

})
</script>

<template>
    <div class="overflow-hidden sm:rounded-lg">
        <dl>
            <div  :class="'grid grid-cols-2'">
                <slot/>
            </div>
        </dl>
    </div>
</template>

<style scoped>

</style>
