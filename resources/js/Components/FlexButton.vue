<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    text: String,
    href: String,
    IconAfter: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
  <div>
    <Link type="button"
            :href="href"
            class="text-gray-900 bg-white hover:bg-gray-50 border border-gray-200 focus:ring-2 focus:outline-none focus:ring-purple-500 font-medium rounded-lg text-sm px-5 p-2 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 ">
        <span v-if="IconAfter" class="ltr:mr-2 rtl:ml-2">{{ text }}</span>
        <slot />
        <span v-if="!IconAfter"  class="ltr:ml-2 rtl:mr-2">{{ text }}</span>
    </Link>
  </div>
</template>
