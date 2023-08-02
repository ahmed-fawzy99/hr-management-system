<script setup>
import Paginator from "@/Components/Table/Paginator.vue";

defineProps({
    links: Object,
    showingNumber: {
        type: Number,
        default: 0,
    },
    totalNumber: {
        type: Number,
        default: 0,
    },
    enablePaginator: {
        type: Boolean,
        default: true,
    },
})
</script>

<template>
    <div v-if="totalNumber" class="relative overflow-x-auto shadow-md sm:rounded-lg  pt-1">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <!-- HEADER -->
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-900 dark:text-gray-400  ">
                <tr>
                    <slot name="Head"></slot>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody>
                <slot name="Body"></slot>
            </tbody>

        </table>

        <!-- PAGINATOR -->
        <nav v-if="enablePaginator && totalNumber !== 0" class="flex items-center justify-between pt-4 pb-2 mx-6" aria-label="Table navigation">
            <span v-if="showingNumber !== totalNumber " class="text-sm font-normal text-gray-500 dark:text-gray-400">
                {{__('Showing')}} <span class="font-semibold text-gray-900 dark:text-white">{{ showingNumber }}</span> {{__('of')}} <span class="font-semibold text-gray-900 dark:text-white">{{ totalNumber }}</span> {{__('Records')}}.</span>
            <Paginator dir="ltr" :links="links"/>
        </nav>
    </div>

    <!-- EMPTY TABLE TEXT -->
    <div v-else class="flex items-center justify-center p-6 mt-4">
        <div class="text-center pb-8 text-gray-500">
            {{ __('No data was found in the records.')}}
        </div>
    </div>

</template>

