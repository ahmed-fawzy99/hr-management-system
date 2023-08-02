<script setup>

defineProps({
    fancyP: {
        type: Boolean,
        default: true,
    },
    vl: {
        type: Boolean,
        default: false,
    },
})
</script>

<template>
  <div class="mt-4 bg-white  shadow-sm rounded-lg dark:bg-gray-800">
    <div :class="{'fancy-p': fancyP, 'h-full flex flex-col items-center justify-center': vl}">
        <slot />
    </div>
  </div>
</template>
