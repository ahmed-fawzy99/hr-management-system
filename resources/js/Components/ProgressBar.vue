<script setup>
const props = defineProps({
    percentage: Number,
    text: String,
    noText: {
        type: Boolean,
        default: false
    },
    color: {
        type: String,
        default: 'bg-purple-500'
    },
    textColor: {
        type: String,
        default: 'text-purple-100'
    }
})
const colorClass = props.color + ' ' + props.textColor + (props.percentage === 0 ? ' !p-0 !text-black' : '');
</script>

<template>
    <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 overflow-hidden">
      <div :class=" colorClass"  class="flex items-center justify-center h-full text-xs font-medium text-center p-0.5 leading-none rounded-full" :style="'width:'+ (percentage > 100 ? 100 : percentage) + '%'">
          {{noText ? '&#x200B;' : (text ?? (percentage === 0 ? '' : percentage.toFixed(0)+'%')) }}
      </div>
  </div>
</template>

<style scoped>

</style>
