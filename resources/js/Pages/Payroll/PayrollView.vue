<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import FlexButton from "@/Components/FlexButton.vue";
import {onMounted} from "vue";
import {initModals} from "flowbite";
import PayrollTabs from "@/Components/Tabs/PayrollTabs.vue";
import DescriptionList from "@/Components/DescriptionList/DescriptionList.vue";
import DescriptionListItem from "@/Components/DescriptionList/DescriptionListItem.vue";
import DT from "@/Components/DescriptionList/DT.vue";
import DD from "@/Components/DescriptionList/DD.vue";
import ToolTip from "@/Components/ToolTip.vue";
import Notice from "@/Components/Notice.vue";
import GenericButton from "@/Components/GenericButton.vue";
import {useToast} from "vue-toastification";
import Swal from "sweetalert2";
import Card from "@/Components/Card.vue";
import ModifyIcon from "@/Components/Icons/ModifyIcon.vue";
import XIcon from "@/Components/Icons/XIcon.vue";
import CheckIcon from "@/Components/Icons/CheckIcon.vue";
import LeftChevronIcon from "@/Components/Icons/LeftChevronIcon.vue";
import RightChevronIcon from "@/Components/Icons/RightChevronIcon.vue";
import {__} from "@/Composables/useTranslations.js";

onMounted(() => {
    initModals();
});

const isLtr = document.dir === 'ltr';

const props = defineProps({
    payroll: Object,
})
const currency = props.payroll.currency;

const form = useForm({status: '',});
const submit = () => {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            cancelButton: 'mx-4 text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900',
            confirmButton: 'text-white bg-purple-700 hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: __('Are you sure You want to change the status of this Payroll?'),
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: __('Confirm'),
        cancelButtonText:  __('Cancel'),
        input: form.status ? 'checkbox' : null,
        inputValue: 1,
        inputPlaceholder:
            __('Send an Email to the employee with this payroll update.'),
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route('payrolls.updateStatus', {
                id: props.payroll.id,
                sendEmail: form.status ? result.value : 0
            }), {
                preserveScroll: true,
                onError: () => {
                    useToast().error(__('Error Updating Status'));
                },
                onSuccess: () => {
                    useToast().success(__('Status Updated Successfully'));
                }
            });
        }
    })
};

onMounted(() => {
    if (usePage().props.errors.end_of_payrolls) {
        Swal.fire({
            icon: 'success',
            title: usePage().props.errors.end_of_payrolls,
        })
    }
});

</script>

<template>
    <Head :title="__('Payroll Data')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <PayrollTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h1 class=" text-2xl">{{ __('Payroll Details') }}</h1>
                            <div v-if="$page.props.auth.user.roles.includes('admin')" class="flex inline-flex gap-4">
                                <FlexButton v-if="!payroll.status" :href="route('payrolls.edit', {id: payroll.id})"
                                            :text="!payroll.is_reviewed ? __('Review Payroll') : __('Review Again')" >
                                    <ModifyIcon/>
                                </FlexButton>
                                <form @submit.prevent="submit">
                                    <GenericButton v-if="payroll.is_reviewed && !payroll.status"
                                                   :text="__('Approve Payroll')" @click="form.status = true"
                                                   :class="{ 'opacity-25': form.processing }"
                                                   :disabled="form.processing"
                                    >
                                        <CheckIcon/>
                                    </GenericButton>
                                    <GenericButton v-else-if="payroll.is_reviewed && payroll.status"
                                                   :text="__('Mark as Pending')" @click="form.status = false"
                                                   :class="{ 'opacity-25': form.processing }"
                                                   :disabled="form.processing"
                                    >
                                        <XIcon/>
                                    </GenericButton>
                                </form>
                            </div>
                        </div>

                        <div v-if="$page.props.auth.user.roles.includes('admin')">
                            <Notice v-if="!payroll.is_reviewed" type="warning"
                                    :bold="__('This Payroll Has not been reviewed Yet.')" :br="true"
                                    :text="__('You are viewing auto-generated payroll data, which is not final and cannot be approved. Please Review the payroll first, then the option to approve it will appear on this page.')"
                            />
                            <Notice v-else-if="payroll.is_reviewed && !payroll.status" :bold="__('This Payroll Has been Reviewed.')"
                                    :text="__('You can approve it now.')"
                            />
                            <Notice v-else-if="payroll.is_reviewed && payroll.status" :bold="__('Processed Payroll.')"
                                    :text="__('This payroll has been reviewed and paid successfully.')" type="success"
                            />
                        </div>

                        <DescriptionList>
                            <DescriptionListItem colored>
                                <DT>{{__('Payroll ID')}}</DT>
                                <DD>{{ payroll.id }}</DD>
                            </DescriptionListItem>
                            <DescriptionListItem colored>
                                <DT>{{__('Issued for Employee')}}</DT>
                                <Link :href="route('employees.show', {id: payroll.employee.id})">
                                    <DD>{{ payroll.employee.name }}</DD>
                                </Link>
                            </DescriptionListItem>
                            <DescriptionListItem>
                                <DT>{{__('Base Salary')}}</DT>
                                <DD>{{ currency + ' ' + parseInt(payroll.base).toLocaleString() }}</DD>
                            </DescriptionListItem>
                            <DescriptionListItem>
                                <DT>{{__('Total Deductions')}}</DT>
                                <DD>{{ currency + ' ' + parseInt(payroll.total_deductions).toLocaleString() }}</DD>
                            </DescriptionListItem>
                            <DescriptionListItem colored>
                                <DT>{{__('Total Additions')}}</DT>
                                <DD>{{ currency + ' ' + parseInt(payroll.total_additions).toLocaleString() }}</DD>
                            </DescriptionListItem>
                            <DescriptionListItem colored>
                                <DT class="inline">{{__('Performance Multiplier')}}</DT>
                                <ToolTip>
                                    {{__('Based on employee\'s performance metrics filled,')}}<br>
                                    {{__('the multiplier value increases or decreases affecting the entire payroll\'s total amount.')}}
                                </ToolTip>
                                <DD>{{ (payroll.performance_multiplier * 100).toFixed(2) }}%</DD>
                            </DescriptionListItem>
                            <DescriptionListItem>
                                <DT>{{__('Total Payable')}}</DT>
                                <DD>{{ currency + ' ' + parseInt(payroll.total_payable).toLocaleString() }}</DD>
                            </DescriptionListItem>
                            <DescriptionListItem>
                                <DT>{{__('Due Date')}}</DT>
                                <DD>{{ payroll.due_date }}</DD>
                            </DescriptionListItem>
                            <DescriptionListItem colored>
                                <DT>{{__('Reviewed?')}}</DT>
                                <DD>{{ payroll.is_reviewed ? __('Reviewed') : __('Not Reviewed') }}</DD>
                            </DescriptionListItem>
                            <DescriptionListItem colored>
                                <DT>{{__('Status')}}</DT>
                                <DD>{{ payroll.status ? __('Paid') : __('Pending') }}</DD>
                            </DescriptionListItem>
                        </DescriptionList>

                        <div v-if="$page.props.auth.user.roles.includes('admin')" class="flex justify-between">
                            <FlexButton class="mb-4" :href="route('payrolls.show', {id: payroll.id-1})"
                                        :text="__('Previous Payroll')">
                                <LeftChevronIcon v-if="isLtr"/>
                                <RightChevronIcon v-else/>
                            </FlexButton>
                            <FlexButton class="mb-4" :href="route('payrolls.show', {id: payroll.id+1})"
                                        :text="__('Next Payroll')" IconAfter>
                                <RightChevronIcon v-if="isLtr"/>
                                <LeftChevronIcon v-else/>
                            </FlexButton>
                        </div>
                    </div>
                </Card>

            </div>

        </div>
    </AuthenticatedLayout>
</template>
