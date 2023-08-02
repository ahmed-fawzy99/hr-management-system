<script setup>

import {onMounted} from "vue";
import Paginator from "@/Components/Table/Paginator.vue";
import {Link} from "@inertiajs/vue3";
import {initDropdowns} from "flowbite";
import {__} from "@/Composables/useTranslations.js";

onMounted(() => {
    initDropdowns();
});

const props = defineProps({
    data: Object,  // like this { "data": [ { "id": 1, "name": "Super Root", "email.." }, { "id": 2, "name"... ....} }
    head: Array,
    controller: {
        type: String,
        default: 'employees',
    },
    hasLink: Boolean,
    hasActions: Boolean,
    hasPaginator: {
        type: Boolean,
        default: true,
    },
    hasCustomParams: {
        type: Boolean,
        default: false,
    },
    customParamsHeader: {
        type: String,
        default: 'id',
    },
    customParamsIndex: {
        type: Number,
        default: 0,
    },
    undefinedText: {
        type: String,
        default: __('N/A'),
    },

    boolMessage: Array,
})

</script>

<template>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div v-if="data.data.length === 0" class="text-center pb-8 ">
            {{ __('No data was found in the records.')}}
        </div>
        <table v-else class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-900 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3" v-for="header in head" :key="header.id">
                    {{ header }}
                </th>
                <th v-if="hasActions" scope="col" class="px-6 py-3">
                    {{__('Actions')}}
                </th>
            </tr>
            </thead>

            <tbody>
            <tr class=" border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
                v-for="(value) in data.data" :key="value.id">
                <td class="px-6 py-4" v-for="(val, key) in value" :key="key">
                    <div v-if="hasLink">
                        <Link v-if="hasCustomParams" :href="route(controller + '.show', { [customParamsHeader]: value[Object.keys(value)[customParamsIndex]] } )">{{ val ?? undefinedText }}</Link>
                        <Link v-else :href="route(controller + '.show', { id: value.id } )">{{ val ?? undefinedText }}</Link>
                    </div>
                    <p v-else>{{ val ?? undefinedText }} </p>
                </td>
                <td v-if="hasActions" class="px-6 py-4">
                    <Link :href="route(controller + '.edit', { id: value.id } )"
                          class="font-medium text-purple-600 dark:text-purple-500 hover:underline">{{__('Edit')}}
                    </Link>
                </td>
            </tr>

            </tbody>
        </table>

        <nav v-if="hasPaginator && data.data.length !== 0" class="flex items-center justify-between pt-4 px-6 pb-2" aria-label="Table navigation">
            <span v-if="data.data.length !== data.total " class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                {{__('Showing')}} <span class="font-semibold text-gray-900 dark:text-white">{{ data.data.length }}</span> {{__('of')}} <span class="font-semibold text-gray-900 dark:text-white">{{ data.total }}</span> {{__('Records')}}.</span>
            <Paginator :links="data.links"/>
        </nav>
    </div>

</template>

