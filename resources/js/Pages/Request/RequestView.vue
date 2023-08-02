<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import {ref} from "vue";
import ReqTabs from "@/Components/Tabs/ReqTabs.vue";
import {useToast} from "vue-toastification";
import GenericButton from "@/Components/GenericButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Swal from "sweetalert2";
import Card from "@/Components/Card.vue";
import CheckIcon from "@/Components/Icons/CheckIcon.vue";
import XIcon from "@/Components/Icons/XIcon.vue";
import DescriptionList from "@/Components/DescriptionList/DescriptionList.vue";
import DT from "@/Components/DescriptionList/DT.vue";
import DD from "@/Components/DescriptionList/DD.vue";
import DescriptionListItem from "@/Components/DescriptionList/DescriptionListItem.vue";
import {__} from "@/Composables/useTranslations.js";
import {request_types} from "@/Composables/useRequestTypes.js";


const props = defineProps({
    request: Object,
})
const message = ref("");
const form = useForm({
    status: '',
    admin_response: '',
});

const submit = () => {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            cancelButton: 'mx-4 text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900',
            confirmButton: 'text-white bg-purple-700 hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: __('Are you sure You want to :sth this request?', {sth: message.value}),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: __('Confirm'),
        cancelButtonText: __('Cancel'),
        inputLabel: message.value === __('reject') ? __('(Optional) Provide a reason for rejecting this request:') : '',
        input: message.value === __('reject') ? 'textarea' : '',
        inputPlaceholder: message.value === __('reject') ?
            __('We can\'t accept this leave this week as Mark and Jose will be off and we will be understaffed.') : '',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.admin_response = message.value === __('reject') ? result.value : '';
            form.put(route('requests.update', {id: props.request.id}), {
                preserveScroll: true,
                onError: () => {
                    useToast().error(__('Error Updating Request Status'));
                },
                onSuccess: () => {
                    useToast().success(__('Request Status Updated Successfully'));
                }
            });
        }
    })
};


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
        cancelButtonText: __('No, Cancel!'),
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('requests.destroy', {id: props.request.id}), {
                preserveScroll: true,
                onError: () => {
                    useToast().error(__('Error Deleting Request'));
                },
                onSuccess: () => {
                    Swal.fire(__('Request Removed!'), '', 'success')
                }
            });
        }
    })
};

</script>

<template>
    <Head :title="__('Request Data')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <ReqTabs />
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h1 class="card-header mb-2">{{__('Request #:id Data', {id: request.id})}}</h1>
                            <div v-if="$page.props.auth.user.roles.includes('admin')" class="flex inline-flex gap-4">
                                <form @submit.prevent="submit"  class="flex gap-4">
                                    <GenericButton v-if="request.status !== 'Approved'" :text="__('Approve Request')"
                                                   @click="form.status = 1; message=__('approve');"
                                                   :class="{ 'opacity-25': form.processing }"
                                                   :disabled="form.processing">
                                        <CheckIcon/>
                                    </GenericButton>
                                    <GenericButton v-if="request.status !== 'Rejected'" :text="__('Reject Request')"
                                                   @click="form.status = 2; message= __('reject');"
                                                   :class="{ 'opacity-25': form.processing }"
                                                   :disabled="form.processing">
                                        <XIcon/>
                                    </GenericButton>
                                </form>
                            </div>
                        </div>

                        <h2 class="mb-2 ml-1 font-semibold">{{__('Request Info')}}</h2>

                        <DescriptionList>
                            <DescriptionListItem colored>
                                <DT>{{__('ID')}}</DT>
                                <DD>{{ request.id }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem colored>
                                <DT>{{__('Created By')}}</DT>
                                <DD>{{ request.employee.name }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem>
                                <DT>{{__('Request Type')}}</DT>
                                <DD>{{ request_types[request.type] }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem>
                                <DT>{{__('Request Message')}}</DT>
                                <DD>{{ request.message }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem colored>
                                <DT>{{__('From Date')}}</DT>
                                <DD>{{ request.start_date }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem colored>
                                <DT>{{__('To Date')}}</DT>
                                <DD>{{ request.end_date ?? __('N/A') }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem>
                                <DT>{{__('Submission Date')}}</DT>
                                <DD>{{ (new Date(props.request.created_at)).toISOString().split("T")[0] }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem>
                                <DT>{{__('Status')}}</DT>
                                <DD>{{
                                        request.status === "Pending" ? __('Pending') + ' ⏳':
                                        request.status === "Approved" ? __('Approved') + ' ✅' :
                                        __('Rejected') + ' 🚫'
                                    }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem colored>
                                <DT>{{__('Admin Response')}}</DT>
                                <DD>{{ request.admin_response ?? '-' }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem colored>
                                <DT></DT><DD></DD>
                            </DescriptionListItem>
                        </DescriptionList>

                        <form v-if="$page.props.auth.user.roles.includes('admin')" @submit.prevent="destroy" class="flex justify-end">
                            <PrimaryButton class="bg-red-600 hover:bg-red-700 ml-4 mt-4 focus:bg-red-500 active:bg-red-900" >
                                {{__('Delete Request')}}
                            </PrimaryButton>
                        </form>
                    </div>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
