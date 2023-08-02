<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import GenericDescriptionList from "@/Components/DescriptionList/GenericDescriptionList.vue";
import {computed} from "vue";
import {useToast} from "vue-toastification";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CalendarTabs from "@/Components/Tabs/CalendarTabs.vue";
import Swal from "sweetalert2";
import FlexButton from "@/Components/FlexButton.vue";
import Card from "@/Components/Card.vue";
import ModifyIcon from "@/Components/Icons/ModifyIcon.vue";
import {__} from "@/Composables/useTranslations.js";

const props = defineProps({
    calendarItem: Object,
})

//
const calItemInfo = computed(() => {
    return {
        [__('ID')]: [props.calendarItem.id],
        [__('Type')]: [props.calendarItem.type],
        [__('Title')]: [props.calendarItem.title],
        '': ' ',
        [__('From Date')]: [props.calendarItem.start_date],
        [__('To Date')]: [props.calendarItem.end_date ?? (__('N/A'))],
    };
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
            useForm({}).delete(route('calendars.destroy', {id: props.calendarItem.id}), {
                preserveScroll: true,
                onError: () => {
                    useToast().error(__('Error Deleting Item'));
                },
                onSuccess: () => {
                    Swal.fire(__('Item Deleted!'), '', 'success')
                }
            });
        }
    })
};

</script>

<template>
    <Head :title="__('Calendar Item Data')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <CalendarTabs/>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h1 class="card-header">{{__('Calendar Item Details')}}</h1>
                            <div class="flex inline-flex gap-4">
                                <FlexButton
                                    :text="__('Modify Item Data')"
                                    :href="route('calendars.edit', {id: calendarItem.id})"
                                >
                                    <ModifyIcon/>
                                </FlexButton>
                            </div>
                        </div>

                        <h2 class="mb-2 ml-1 font-semibold">{{ __('Item Info') }}</h2>

                        <GenericDescriptionList :data="calItemInfo" :colNum="2"/>

                        <form @submit.prevent="destroy" class="flex justify-end inline">
                            <PrimaryButton
                                class="bg-red-600 hover:bg-red-700 ml-4 mt-4 focus:bg-red-500 active:bg-red-900">
                                {{ __('Delete Item') }}
                            </PrimaryButton>
                        </form>
                    </div>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
