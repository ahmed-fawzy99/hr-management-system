<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import DataTable from "@/Components/DataTable.vue";
import FlexButton from "@/Components/FlexButton.vue";
import OrgTabs from "@/Components/Tabs/OrgTabs.vue";
import {ref, watch} from "vue";
import debounce from "lodash.debounce";
import Card from "@/Components/Card.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";

const term = ref('');
const search = debounce(() => {
    router.visit(route('shifts.index', {term: term.value}),
        {preserveState: true, preserveScroll: true})
}, 500);
watch(term, search);

defineProps({
    shifts: Object,
});

</script>
<template>
    <Head :title="__('Shifts')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <div class="flex justify-between items-center mb-8">
                        <h1 class="card-header">{{__('Current Shifts')}}</h1>
                        <FlexButton :href="route('shifts.create')" :text="__('Add A Shift')">
                            <PlusIcon/>
                        </FlexButton>
                    </div>

                    <DataTable
                        :controller="'shifts'"
                        :head='[__("ID"), __("Name"), __("Start Time"), __("End Time"), __("Payment Multiplier"),
                                __("Description"), __("Employees")]'
                        :data="shifts"
                        :hasActions="true"
                        :hasLink="true"
                    ></DataTable>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
