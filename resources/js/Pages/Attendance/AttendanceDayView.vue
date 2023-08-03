<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import FlexButton from "@/Components/FlexButton.vue";
import AttendanceTabs from "@/Components/Tabs/AttendanceTabs.vue";
import {useToast} from "vue-toastification";
import Swal from "sweetalert2";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Card from "@/Components/Card.vue";
import TrashIcon from "@/Components/Icons/TrashIcon.vue";
import {__} from "@/Composables/useTranslations.js";
import Table from "@/Components/Table/Table.vue";
import TableBodyHeader from "@/Components/Table/TableBodyHeader.vue";
import TableBody from "@/Components/Table/TableBody.vue";
import TableHead from "@/Components/Table/TableHead.vue";
import TableRow from "@/Components/Table/TableRow.vue";
import {attendance_types} from "@/Composables/useAttendanceTypes.js";

const props = defineProps({
    attendanceList: Object,
    day: String,

});

const destroy = () => {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'mx-4 text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900',
            cancelButton: 'text-white bg-purple-700 hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: __('Are you sure?'),
        text: __('You won\'t be able to revert this!'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: __('Yes, Delete!'),
        cancelButtonText:  __('No, Cancel!'),
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            useForm({}).delete(route('attendance.destroy', {date: props.day}), {
                preserveScroll: true,
                onError: () => {
                    useToast().error(__('Error Deleting Attendance'));
                },
                onSuccess: () => {
                    Swal.fire(__('Attendance Removed!'), '', 'success')
                }
            });
        }
    })
};


</script>

<template>
    <Head :title="__('Attendance View')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <AttendanceTabs />
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-6">{{__('Attendance List for :day', {day: day})}}</h1>
                    <div class="flex justify-between items-center pb-4 gap-4">
                        <div class="flex justify-center items-center inline">
                            <FlexButton :href="route('attendances.create')" :text="__('Retake/Update Attendance')">
                                <TrashIcon/>
                            </FlexButton>
                        </div>
                        <form @submit.prevent="destroy" class="flex justify-center items-center ">
                            <PrimaryButton class="bg-red-600 hover:bg-red-700 focus:bg-red-500 active:bg-red-900" >
                                <TrashIcon/>
                                {{__('Delete Day Attendance')}}
                            </PrimaryButton>
                        </form>
                    </div>

                    <Table :links="attendanceList.links" :showingNumber="attendanceList.data.length"
                           :totalNumber="attendanceList.total">
                        <template #Head>
                            <TableHead>{{__('ID')}}</TableHead>
                            <TableHead>{{__('Employee')}}</TableHead>
                            <TableHead>{{__('Status')}}</TableHead>
                            <TableHead>{{__('Sign In Time')}}</TableHead>
                            <TableHead>{{__('Sign Off Time')}}</TableHead>
                            <TableHead>{{__('Notes')}}</TableHead>
                        </template>

                        <!--Iterate Here-->
                        <template #Body>
                            <TableRow v-for="attendance in attendanceList.data" :key="attendance.id">
                                <TableBodyHeader>{{ attendance.id }}</TableBodyHeader>
                                <TableBodyHeader >{{ attendance.employee_name }}</TableBodyHeader>
                                <TableBody>{{ attendance_types[attendance.status] }}</TableBody>
                                <TableBody>{{ attendance.sign_in_time }}</TableBody>
                                <TableBody>{{ attendance.sign_off_time ?? __('Haven\'t Sign Off Yet') }}</TableBody>
                                <TableBody>{{ attendance.notes }}</TableBody>
                            </TableRow>
                        </template>
                    </Table>
                </Card>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
