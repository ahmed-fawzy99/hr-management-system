<script setup>
defineProps({
    text: String,
})
</script>

<!--There is a room to extend this with more icons and colors. Right now, it's an alert-->
<template>
  <div class="fixed bottom-0 left-0 z-50 flex justify-between w-full p-4 border-b
  border-yellow-300 bg-yellow-200  dark:border-yellow-600">
    <div class="flex items-center mx-auto">
      <p class="flex items-center text-sm font-normal text-gray-500 dark:text-gray-400">
            <span
                class="inline-flex p-1 mr-3 rounded-full bg-yellow-300 w-6 h-6 items-center justify-center">
                <svg class="w-6 h-6 text-orange-950 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                  </svg>
            </span>
        <span class="font-medium text-orange-950">asdasdsadasd asd asd asd as d{{ text }}</span>
      </p>
    </div>
  </div>
</template>

