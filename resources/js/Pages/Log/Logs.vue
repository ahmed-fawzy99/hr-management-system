<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import EmployeeTabs from "@/Components/Tabs/EmployeeTabs.vue";
import SearchBar from "@/Components/SearchBar.vue";
import FlexButton from "@/Components/FlexButton.vue";
import Table from "@/Components/Table/Table.vue";
import TableHead from "@/Components/Table/TableHead.vue";
import TableBody from "@/Components/Table/TableBody.vue";
import TableBodyHeader from "@/Components/Table/TableBodyHeader.vue";
import TableBodyAction from "@/Components/Table/TableBodyAction.vue";
import TableRow from "@/Components/Table/TableRow.vue";
import Card from "@/Components/Card.vue";
import DataTable from "@/Components/DataTable.vue";
import OrgTabs from "@/Components/Tabs/OrgTabs.vue";
import Notice from "@/Components/Notice.vue";

const props = defineProps({
    logs: Object,
});


</script>

<template>
    <Head :title="__('Logs')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs />
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{__('Activity Log')}}</h1>
                    <Notice :bold="__('Notice:')" :text="__('This Log is automatically cleared every week.')"/>
                    <DataTable
                        :head='[__("ID"), __("Log Name"), __("Description"), __("Subject Type"), __("Subject ID"),
                        __("Causer Type"), __("Causer ID"), __("Properties"), __("Created At"), __("Updated At"),
                        __("Event Type"), __("batch_uuid")]'
                        :data="logs"
                    />
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
