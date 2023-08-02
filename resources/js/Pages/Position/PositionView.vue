<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import FlexButton from "@/Components/FlexButton.vue";
import {onMounted, ref, watch} from "vue";
import {initModals} from "flowbite";
import OrgTabs from "@/Components/Tabs/OrgTabs.vue";
import debounce from "lodash.debounce";
import SearchBar from "@/Components/SearchBar.vue";
import Card from "@/Components/Card.vue";
import TableRow from "@/Components/Table/TableRow.vue";
import TableHead from "@/Components/Table/TableHead.vue";
import TableBody from "@/Components/Table/TableBody.vue";
import Table from "@/Components/Table/Table.vue";
import TableBodyHeader from "@/Components/Table/TableBodyHeader.vue";
import ModifyIcon from "@/Components/Icons/ModifyIcon.vue";
import DescriptionList from "@/Components/DescriptionList/DescriptionList.vue";
import DT from "@/Components/DescriptionList/DT.vue";
import DD from "@/Components/DescriptionList/DD.vue";
import DescriptionListItem from "@/Components/DescriptionList/DescriptionListItem.vue";

onMounted(() => {
    initModals();
});

const props = defineProps({
    position: Object,
    employees: Object,
})

const term = ref('');
const search = debounce(() => {
    router.visit(route('positions.show', {term: term.value, position: props.position.id}),
        {preserveState: true, preserveScroll: true})
}, 500);
watch(term, search);


</script>

<template>
    <Head :title="__('Position Data')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <div>
                        <div class="flex justify-between items-center">
                            <h1 class="card-header !mb-4">{{__('Position Details')}}</h1>
                            <div class="flex gap-4">
                                <FlexButton
                                    :text="__('Modify Position Data')"
                                    :href="route('positions.edit', {id: props.position.id})"
                                >
                                    <ModifyIcon/>
                                </FlexButton>
                            </div>
                        </div>

                        <h2 class="mb-2 ml-1 font-semibold">{{__('Basic Info')}}</h2>
                        <DescriptionList>
                            <DescriptionListItem colored>
                                <DT>{{__('Name')}}</DT>
                                <DD>{{ position.name }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem colored>
                                <DT>{{__('ID')}}</DT>
                                <DD>{{ position.id }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem>
                                <DT>{{__('Description')}}</DT>
                                <DD>{{ position.description ?? __('N/A')}}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem>
                                <DT>{{__('Number of Employees')}}</DT>
                                <DD>{{ position.employees_count }}</DD>
                            </DescriptionListItem>
                        </DescriptionList>
                    </div>
                </Card>

                <Card>
                    <div class="flex justify-between items-center pb-4">
                        <h2 class="mb-2 ml-1 font-semibold">{{__('Position Employees')}}</h2>
                        <SearchBar>
                            <input type="text" id="table-search-users" v-model="term"
                                   class="input-class"
                                   :placeholder="__('Search for a user')">
                        </SearchBar>
                    </div>

                    <Table :links="employees.links" :showingNumber="employees.data.length"
                           :totalNumber="employees.total">
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
                                <TableBodyHeader :href="route('employees.show', {id: employee.id})">{{ employee.id }}
                                </TableBodyHeader>
                                <TableBodyHeader :href="route('employees.show', {id: employee.id})">
                                    {{ employee.name }}
                                </TableBodyHeader>
                                <TableBody :href="route('employees.show', {id: employee.id})">{{ employee.email }}
                                </TableBody>
                                <TableBody :href="route('employees.show', {id: employee.id})">{{ employee.phone }}
                                </TableBody>
                                <TableBody :href="route('employees.show', {id: employee.id})">
                                    {{ employee.national_id }}
                                </TableBody>
                            </TableRow>
                        </template>
                    </Table>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
