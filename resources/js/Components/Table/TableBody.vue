<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    href: String,
})
</script>
<template>
    <td class="px-6 py-3" >
        <Link v-if="href" :href="href">
            <slot />
        </Link>
        <slot v-else />
    </td>
</template>
