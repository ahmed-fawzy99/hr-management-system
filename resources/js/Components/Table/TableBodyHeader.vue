<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    href: String,
})

</script>
<template>
    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
        <Link v-if="href" :href="href">
            <slot />
        </Link>
        <slot v-else />
    </th>
</template>
