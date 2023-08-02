<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import FlexButton from "@/Components/FlexButton.vue";
import ReqTabs from "@/Components/Tabs/ReqTabs.vue";
import Card from "@/Components/Card.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";
import TableBody from "@/Components/Table/TableBody.vue";
import TableRow from "@/Components/Table/TableRow.vue";
import TableBodyHeader from "@/Components/Table/TableBodyHeader.vue";
import Table from "@/Components/Table/Table.vue";
import TableHead from "@/Components/Table/TableHead.vue";
import {__} from "@/Composables/useTranslations.js";
import {request_types} from "@/Composables/useRequestTypes.js";
import {request_status_types} from "@/Composables/useRequestStatusTypes.js";

defineProps({
    requests: Object,
})

</script>
<template>
    <Head :title="__('Requests')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <ReqTabs />
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <div class="flex justify-between items-center pb-4">
                        <h1 class="card-header !mb-4">{{__('Current Requests')}}</h1>
                        <FlexButton v-if="!$page.props.auth.user.roles.includes('admin')" :href="route('requests.create')"
                                    :text="__('Initiate A Request')">
                            <PlusIcon/>
                        </FlexButton>
                    </div>
                    <Table :links="requests.links" :showingNumber="requests.data.length" :totalNumber="requests.total">
                        <template #Head>
                            <TableHead>{{__('ID')}}</TableHead>
                            <TableHead>{{__('Created By')}}</TableHead>
                            <TableHead>{{__('Type')}}</TableHead>
                            <TableHead>{{__('Start Date')}}</TableHead>
                            <TableHead>{{__('End Date')}}</TableHead>
                            <TableHead>{{__('Status')}}</TableHead>
                        </template>

                        <!--Iterate Here-->
                        <template #Body>
                            <TableRow v-for="request in requests.data" :key="request.id">
                                <TableBodyHeader :href="route('requests.show', {id: request.id})">{{request.id}}</TableBodyHeader>
                                <TableBodyHeader :href="route('requests.show', {id: request.id})" >{{request.employee_name}}</TableBodyHeader>
                                <TableBody :href="route('requests.show', {id: request.id})">{{request_types[request.type]}}</TableBody>
                                <TableBody :href="route('requests.show', {id: request.id})">{{request.start_date}}</TableBody>
                                <TableBody :href="route('requests.show', {id: request.id})">{{request.end_date ?? __('N/A')}}</TableBody>
                                <TableBody :href="route('requests.show', {id: request.id})">
                                    {{  request.status === "Pending" ? request_status_types['pending'] + ' ⏳' :
                                        request.status === "Approved" ? request_status_types['approved'] + ' ✅' :
                                            request_status_types['rejected'] + ' 🚫'
                                    }}
                                    <span class="text-red-500 text-xs font-bold"
                                        v-if="!$page.props.auth.user.roles.includes('admin') && request.status !== 'Pending' && !request.is_seen">
                                        <sup>**</sup>
                                    </span>
                                </TableBody>
                            </TableRow>
                        </template>
                    </Table>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
