<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import EmployeeTabs from "@/Components/Tabs/EmployeeTabs.vue";
import SearchBar from "@/Components/SearchBar.vue";
import {ref, watch} from "vue";
import debounce from "lodash.debounce";
import Table from "@/Components/Table/Table.vue";
import TableHead from "@/Components/Table/TableHead.vue";
import TableBody from "@/Components/Table/TableBody.vue";
import TableBodyHeader from "@/Components/Table/TableBodyHeader.vue";
import TableRow from "@/Components/Table/TableRow.vue";
import Card from "@/Components/Card.vue";

const term = ref('');
const sort = ref('id');
const sort_dir = ref(true);
const search = debounce(() => {
    router.visit(route('employees.archived', {term: term.value, sort: sort.value, sort_dir: sort_dir.value}),
        {preserveState: true, preserveScroll: true})
}, 400);
watch(term, search);
watch(sort, search);
watch(sort_dir, search);

const props = defineProps({
    employees: Object,
});


</script>

<template>
    <Head :title="__('Archived Employees')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <EmployeeTabs />
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <div class="flex justify-between items-center gap-4 pb-4">
                        <h1 class="card-header !mb-4">{{__('Archived Employees')}}</h1>
                        <SearchBar>
                            <input type="text" id="table-search-users" v-model="term"
                                   class="input-class"
                                   :placeholder="__('Search for an archived Employee')">
                        </SearchBar>
                    </div>

                    <Table :links="employees.links" :showingNumber="employees.data.length" :totalNumber="employees.total">
                        <template #Head>
                            <TableHead @click="sort='id'; sort_dir = !sort_dir;" sortable>{{__('ID')}} ↕</TableHead>
                            <TableHead @click="sort='name'; sort_dir = !sort_dir;" sortable>{{__('Name')}} ↕</TableHead>
                            <TableHead>{{__('Email')}}</TableHead>
                            <TableHead>{{__('Phone')}}</TableHead>
                            <TableHead>{{__('National ID')}}</TableHead>
                            <TableHead>{{__('Hired On')}}</TableHead>
                            <TableHead>{{__('Released On')}}</TableHead>
                        </template>

                        <!--Iterate Here-->
                        <template #Body>
                            <TableRow v-for="employee in employees.data" :key="employee.id">
                                <TableBodyHeader >{{employee.id}}</TableBodyHeader>
                                <TableBodyHeader  >{{employee.name}}</TableBodyHeader>
                                <TableBody >{{employee.email}}</TableBody>
                                <TableBody >{{employee.phone}}</TableBody>
                                <TableBody >{{employee.national_id}}</TableBody>
                                <TableBody >{{employee.hired_on}}</TableBody>
                                <TableBody >{{employee.released_on}}</TableBody>
                            </TableRow>
                        </template>
                    </Table>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
