<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import EmployeeTabs from "@/Components/Tabs/EmployeeTabs.vue";

import {ref, watch} from "vue";
import debounce from "lodash.debounce";
import Card from "@/Components/Card.vue";
import SearchIcon from "@/Components/Icons/SearchIcon.vue";

const term = ref('');

const search = debounce(() => {
    router.visit(route('employees.find', {term: term.value}),
        {preserveState: true, preserveScroll: true})
}, 500);

watch(term, search);

defineProps({
    employees: Object,
});
</script>
<template>
    <Head :title="__('Find an Employee')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <EmployeeTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{__('Find an Employee')}}</h1>
                    <label for="default-search"
                           class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">{{__('Search')}}
                    </label>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <SearchIcon/>
                        </div>
                        <input type="search" id="default-search" v-model="term"
                               class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg
                               bg-gray-50 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600
                               dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                               :placeholder="__('Search for an Employee')" required>
                    </div>
                    <div v-if="term.length > 0">
                        <ul v-if="employees.length > 0" class="py-2 text-sm text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-900 shadow-md rounded-lg">
                            <li v-for="employee in employees" class="block px-4 py-2 hover:bg-purple-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                <Link :href="route('employees.show', { id: employee.id } )">
                                    {{ employee.id}} - {{employee.name }} - {{ employee.national_id }} - {{ employee.phone }}
                                </Link>
                            </li>
                        </ul>
                        <p v-else class="py-2 text-sm font-bold text-red-600  dark:text-gray-200 bg-gray-50 dark:bg-gray-800 shadow-md rounded-lg px-4">{{__('No results')}}</p>
                    </div>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
