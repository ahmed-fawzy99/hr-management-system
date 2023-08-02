<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import FlexButton from "@/Components/FlexButton.vue";
import {onMounted, ref, watch} from "vue";
import {initModals} from "flowbite";
import OrgTabs from "@/Components/Tabs/OrgTabs.vue";
import SearchBar from "@/Components/SearchBar.vue";
import debounce from "lodash.debounce";
import Card from "@/Components/Card.vue";
import TableRow from "@/Components/Table/TableRow.vue";
import TableHead from "@/Components/Table/TableHead.vue";
import TableBody from "@/Components/Table/TableBody.vue";
import Table from "@/Components/Table/Table.vue";
import TableBodyHeader from "@/Components/Table/TableBodyHeader.vue";
import DT from "@/Components/DescriptionList/DT.vue";
import DescriptionList from "@/Components/DescriptionList/DescriptionList.vue";
import DescriptionListItem from "@/Components/DescriptionList/DescriptionListItem.vue";
import DD from "@/Components/DescriptionList/DD.vue";
import ModifyIcon from "@/Components/Icons/ModifyIcon.vue";

onMounted(() => {
    initModals();
});

const props = defineProps({
    branch: Object,
    manager: Object,
    employees: Object,
});

const term = ref('');
const search = debounce(() => {
    router.visit(route('branches.show', {term: term.value, branch: props.branch}),
        {preserveState: true, preserveScroll: true})
}, 500);
watch(term, search);

</script>

<template>
    <Head :title="__('Branch Data')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h1 class="card-header">{{ __('Branch Details') }}</h1>
                            <div class="flex inline-flex gap-4">
                                <FlexButton
                                    :text="__('Modify Branch Data')"
                                    :href="route('branches.edit', {id: props.branch.id})">
                                    <ModifyIcon/>
                                </FlexButton>
                            </div>
                        </div>
                        <h2 class="card-subheader">{{__('Basic Info')}}</h2>

                        <DescriptionList class="!pb-0">

                            <DescriptionListItem colored>
                                <DT>{{__('Name')}}</DT>
                                <DD>{{ props.branch.name }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem colored>
                                <DT>{{__('ID')}}</DT>
                                <DD>{{ props.branch.id }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem>
                                <DT>{{__('Phone')}}</DT>
                                <DD>{{ props.branch.phone }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem>
                                <DT>{{__('Email')}}</DT>
                                <DD>{{ props.branch.email }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem colored>
                                <DT>{{__('Manager')}}</DT>
                                <Link v-if="props.manager" :href="route('employees.show', {id: props.manager.id})">
                                    <DD>{{ props.manager.name }}</DD>
                                </Link>
                                <DD v-else>{{__('No Manager')}}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem colored>
                                <DT>{{__('Number of Employees')}}</DT>
                                <DD>{{ props.branch.employees_count }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem>
                                <DT>{{__('Address')}}</DT>
                                <DD>{{ props.branch.address }}</DD>
                            </DescriptionListItem>

                        </DescriptionList>
                    </div>
                </Card>

                <Card>
                    <div class="flex justify-between items-center pb-4">
                        <h2 class="card-subheader">{{__('Branch Employees')}}</h2>
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
