<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import DataTable from "@/Components/DataTable.vue";
import FlexButton from "@/Components/FlexButton.vue";
import AttendanceTabs from "@/Components/Tabs/AttendanceTabs.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import '@vuepic/vue-datepicker/dist/main.css'
import {inject, ref, watch} from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Card from "@/Components/Card.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";

const props = defineProps({
    attendanceList: Object,
    dateParam: String,
});

const date = ref(new Date(props.dateParam));
if (props.dateParam === '') {
    date.value = '';
}
const search = (() => {
    if (date.value === null) {
        router.visit(route('attendances.index', {term: null}), {preserveState: true, preserveScroll: true})

    } else {
        router.visit(route('attendances.index', {term: date.value.toISOString().split('T')[0]}), {
            preserveState: true,
            preserveScroll: true
        })
    }
});
watch(date, search);

</script>

<template>
    <Head :title="__('Attendance')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <AttendanceTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{ __('Attendance List') }}</h1>
                    <div class="flex justify-between items-center pb-4 gap-4">
                        <FlexButton :href="route('attendances.create')" :text="__('Take/Edit Attendance')">
                            <PlusIcon/>
                        </FlexButton>
                        <div>
                            <InputLabel for="date" :value="__('Filter by Date:')"/>
                            <VueDatePicker
                                id="date"
                                v-model="date"
                                class="py-1 block w-full"
                                :placeholder="__('Select a Date..')"
                                :enable-time-picker="false"
                                :max-date="new Date()"
                                :dark="inject('isDark').value"
                                required
                            ></VueDatePicker>
                            <InputError v-if="Object.keys($page.props.errors).length" class="mt-2"
                                        :message="$page.props.errors"/>
                        </div>
                    </div>
                    <DataTable
                        :controller="'attendance'"
                        :head='[__("Date"), __("Total Attendance"), __("Attended On Time"), __("Attended Late"), __("Absented")]'
                        :data="attendanceList"
                        :hasActions="false"
                        :hasLink="true"
                        :hasCustomParams="true"
                        :customParamsHeader="'date'"
                        :customParamsIndex="0"
                    ></DataTable>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
