<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import DataTable from "@/Components/DataTable.vue";
import FlexButton from "@/Components/FlexButton.vue";
import OrgTabs from "@/Components/Tabs/OrgTabs.vue";
import Card from "@/Components/Card.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";
import {__} from "@/Composables/useTranslations.js";
import {request_types} from "@/Composables/useRequestTypes.js";
import {request_status_types} from "@/Composables/useRequestStatusTypes.js";
import TableHead from "@/Components/Table/TableHead.vue";
import TableBodyHeader from "@/Components/Table/TableBodyHeader.vue";
import TableRow from "@/Components/Table/TableRow.vue";
import TableBody from "@/Components/Table/TableBody.vue";
import Table from "@/Components/Table/Table.vue";

defineProps({
    metrics: Object,
});

</script>
<template>
    <Head :title="__('Metrics')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <div class="flex justify-between items-center mb-8">
                        <h1 class="card-header ">{{ __('Current Metrics') }}</h1>
                        <FlexButton :href="route('metrics.create')" :text="__('Add A Metric')">
                            <PlusIcon/>
                        </FlexButton>
                    </div>

                    <Table :links="metrics.links" :showingNumber="metrics.data.length" :totalNumber="metrics.total">
                        <template #Head>
                            <TableHead>{{__('ID')}}</TableHead>
                            <TableHead>{{__('criterion')}}</TableHead>
                            <TableHead>{{__('Weight')}}</TableHead>
                            <TableHead>{{__('step')}}</TableHead>
                        </template>

                        <!--Iterate Here-->
                        <template #Body>
                            <TableRow v-for="metric in metrics.data" :key="metric.id">
                                <TableBodyHeader :href="route('metrics.show', {id: metric.id})"> {{metric.id}} </TableBodyHeader>
                                <TableBody :href="route('metrics.show', {id: metric.id})"> {{metric.criteria}} </TableBody>
                                <TableBody :href="route('metrics.show', {id: metric.id})"> {{metric.weight}} </TableBody>
                                <TableBody :href="route('metrics.show', {id: metric.id})"> {{metric.step*100 + '%'}} </TableBody>
                            </TableRow>
                        </template>
                    </Table>

                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
