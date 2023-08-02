<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import FlexButton from "@/Components/FlexButton.vue";
import GenericDescriptionList from "@/Components/DescriptionList/GenericDescriptionList.vue";
import {computed, onMounted, ref, watch} from "vue";
import {initModals} from "flowbite";
import OrgTabs from "@/Components/Tabs/OrgTabs.vue";
import debounce from "lodash.debounce";
import Card from "@/Components/Card.vue";
import TableRow from "@/Components/Table/TableRow.vue";
import TableHead from "@/Components/Table/TableHead.vue";
import TableBody from "@/Components/Table/TableBody.vue";
import TableBodyHeader from "@/Components/Table/TableBodyHeader.vue";
import Table from "@/Components/Table/Table.vue";
import SearchBar from "@/Components/SearchBar.vue";
import ModifyIcon from "@/Components/Icons/ModifyIcon.vue";
import {__} from "@/Composables/useTranslations.js";

onMounted(() => {
    initModals();
});

const props = defineProps({
    shift: Object,
    employees: Object,
    searchPar: String,
})


const basicInfo = computed(() => {
    return {
        // [DATA TO PRINT, col-span ]
        [__('Name')]: [props.shift.name],
        [__('ID')]: [props.shift.id],
        [__('Start Time')]: [props.shift.start_time],
        [__('End Time')]: [props.shift.end_time],
        [__('Shift Payment Multiplier')]: [props.shift.shift_payment_multiplier],
        [__('Number of Employees')]: [props.shift.employees_count],
        [__('Description')]: [props.shift.description],
    };
});

const term = ref(props.searchPar);
const search = debounce(() => {
    router.visit(route('shifts.show', {term: term.value, shift: props.shift.id}),
            {preserveState: true, preserveScroll: true})
}, 500);
watch(term, search);

</script>

<template>
    <Head :title="__('Shift Data')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h1 class="card-header">{{__('Shift Details')}}</h1>
                            <div class="flex gap-4">
                                <FlexButton
                                    :text="__('Modify Shift Data')"
                                    :href="route('shifts.edit', {id: props.shift.id})">
                                    <ModifyIcon/>
                                </FlexButton>
                            </div>
                        </div>
                        <h2 class="mb-2 ml-1 font-semibold">{{__('Basic Info')}}</h2>
                        <GenericDescriptionList :data="basicInfo" :colNum="2"/>
                    </div>
                </Card>
                <Card>
                    <div class="flex justify-between items-center pb-4">
                        <h2 class="card-subheader">{{__('Shift Employees')}}</h2>
                        <SearchBar>
                            <input type="text" id="table-search-users" v-model="term"
                                   class="input-class"
                                   :placeholder="__('Search for a user')">
                        </SearchBar>
                    </div>

                    <Table :links="employees.links" :showingNumber="employees.data.length" :totalNumber="employees.total">
                        <template #Head>
                            <TableHead>{{__('ID')}}</TableHead>
                            <TableHead>{{__('Name')}}</TableHead>
                            <TableHead>{{__('Email')}}</TableHead>
                            <TableHead>{{__('Phone')}}</TableHead>
                            <TableHead>{{__('National ID')}}</TableHead>
                        </template>

                        <!--Iterate Here-->
                        <template #Body>
                            <TableRow v-for="employee in employees.data" :key="employee.id">
                                <TableBodyHeader :href="route('employees.show', {id: employee.id})">{{employee.id}}</TableBodyHeader>
                                <TableBodyHeader :href="route('employees.show', {id: employee.id})" >{{employee.name}}</TableBodyHeader>
                                <TableBody :href="route('employees.show', {id: employee.id})">{{employee.email}}</TableBody>
                                <TableBody :href="route('employees.show', {id: employee.id})">{{employee.phone}}</TableBody>
                                <TableBody :href="route('employees.show', {id: employee.id})">{{employee.national_id}}</TableBody>
                            </TableRow>
                        </template>
                    </Table>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
