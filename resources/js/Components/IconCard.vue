<script setup>
import {Link} from "@inertiajs/vue3";
import RightArrowIcon from "@/Components/Icons/RightArrowIcon.vue";
import LeftArrowIcon from "@/Components/Icons/LeftArrowIcon.vue";

defineProps({
    ctaText: {
        type: String,
        default: 'Learn More',
    },
    heading: String,
    text: String,
    href: String,
})

const isLTR = document.dir === 'ltr';

</script>

<template>

    <div class="relative flex px-6 flex-col rounded-xl bg-gradient-to-r from-gray-50 to-purple-50 bg-clip-border text-gray-700 shadow-md
                dark:bg-gradient-to-t dark:from-gray-900 dark:to-gray-900 dark:text-gray-300 dark:shadow-lg ">
        <div class="pt-6 pb-2 flex flex-col items-center">

            <!--SVG-->
            <slot />
            <h5 class=" block font-sans text-xl font-semibold leading-snug tracking-normal text-blue-gray-900 antialiased">
                {{ heading ?? 'Heading' }}
            </h5>
            <p v-if="text" class="text-center block font-sans text-base font-light leading-relaxed text-inherit antialiased">
                {{text}}
            </p>
        </div>

        <div class="pb-6 pt-0 flex justify-center">
            <Link
                class="!font-medium !text-blue-gray-900 !transition-colors hover:!text-purple-500"
                :href="href ?? '#'"
            >
                <button
                    class="flex select-none items-center gap-2 rounded-lg py-2 px-4 text-center align-middle font-sans text-xs font-bold uppercase text-purple-500 transition-all hover:bg-purple-500/10 active:bg-purple-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                    type="button"
                    data-ripple-dark="true"
                >

                    {{ ctaText }}
                    <RightArrowIcon v-if="isLTR"/>
                    <LeftArrowIcon v-if="!isLTR"/>
                </button>
            </Link>
        </div>
    </div>

</template>

