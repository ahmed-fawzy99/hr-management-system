<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import OrgTabs from "@/Components/Tabs/OrgTabs.vue";
import {useToast} from "vue-toastification";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Swal from "sweetalert2";
import VueDatePicker from "@vuepic/vue-datepicker";
import '@vuepic/vue-datepicker/dist/main.css'
import Card from "@/Components/Card.vue";
import {inject} from "vue";
import {__} from "@/Composables/useTranslations.js";

const props = defineProps({
    shift: Object,
    name: String,
})

const [start_hours, start_minutes, start_seconds] = props.shift.start_time.split(":").map(Number);
const [end_hours, end_minutes, end_seconds] = props.shift.end_time.split(":").map(Number);

const shiftForm = useForm({
    name: props.name,
    start_time: { hours: start_hours, minutes: start_minutes, seconds: start_seconds },
    end_time: { hours: end_hours, minutes: end_minutes, seconds: end_seconds },
    shift_payment_multiplier: props.shift.shift_payment_multiplier,
    description: props.shift.description,
});

const submitShift = () => {
    shiftForm.put(route('shifts.update', {id: props.shift.id}), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Editing Shift'));
        },
        onSuccess: () => {
            useToast().success(__('Shift Editing Successfully'));
            shiftForm.reset();
        }
    });
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
        title: __('This shift has :n employee. Are you sure?', {n: props.shift.employees_count}),
        text: __('You won\'t be able to revert this!'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: __('Yes, Delete!'),
        cancelButtonText:  __('No, Cancel!'),
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            shiftForm.delete(route('shifts.destroy', {id: props.shift.id}), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire(__('Branch Deleted!'), '', 'success')
                },
                onError: () => {
                    Swal.fire(__('Error!'), usePage().props.errors.only_shift, 'error')
                },
            });
        }
    })
};

</script>
<template>
    <Head :title="__('Branch Edit')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-6">{{__('Edit A Branch')}}</h1>
                    <form @submit.prevent="submitShift">
                        <div>
                            <InputLabel for="position" :value="__('Shift Name')"/>
                            <TextInput
                                id="shift_name"
                                type="text"
                                class="mt-1 block w-full"
                                :class="{'border border-red-500': shiftForm.errors.name}"
                                v-model="shiftForm.name"
                                required
                                autocomplete="off"
                                :placeholder="__('Day Shift')"
                            />
                            <InputError class="mt-2" :message="shiftForm.errors.name"/>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="start_time" :value="__('Shift Start Time')"/>
                            <VueDatePicker
                                v-model="shiftForm.start_time"
                                class="mt-1 block w-full"
                                :class="{'border border-red-500': shiftForm.errors.start_time}"
                                :placeholder="__('Select a Time...')"
                                time-picker
                                :dark="inject('isDark').value"
                                :is-24="false"
                                required
                            ></VueDatePicker>
                            <InputError class="mt-2" :message="shiftForm.errors.start_time"/>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="end_time" :value="__('Shift End Time')"/>
                            <VueDatePicker
                                v-model="shiftForm.end_time"
                                class="mt-1 block w-full"
                                :class="{'border border-red-500': shiftForm.errors.end_time}"
                                :placeholder="__('Select a Time...')"
                                time-picker
                                :dark="inject('isDark').value"
                                :is-24="false"
                                required
                            ></VueDatePicker>
                            <InputError class="mt-2" :message="shiftForm.errors.end_time"/>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="shift_payment_multiplier" :value="__('Multiplier')"/>
                            <TextInput
                                id="shift_payment_multiplier"
                                type="number"
                                class="mt-1 block w-full"
                                :class="{'border border-red-500': shiftForm.errors.shift_payment_multiplier}"
                                v-model="shiftForm.shift_payment_multiplier"
                                autocomplete="off"
                                :placeholder="'1 (' + __('default') + ')'"
                                min="0"
                                step="0.0001"
                            />
                            <InputError class="mt-2" :message="shiftForm.errors.shift_payment_multiplier"/>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="description" :value="__('Description')"/>
                            <TextInput
                                id="description"
                                type="text"
                                class="mt-1 block w-full"
                                :class="{'border border-red-500': shiftForm.errors.description}"
                                v-model="shiftForm.description"
                                autocomplete="off"
                                :placeholder="__('Normal day shift, small amount of customers expected during this shift.')"
                            />
                            <InputError class="mt-2" :message="shiftForm.errors.description"/>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <form @submit.prevent="destroy" class=" inline">
                                <PrimaryButton class="bg-red-600 hover:bg-red-700 ml-4">
                                    {{__('Delete Shift')}}
                                </PrimaryButton>
                            </form>
                            <PrimaryButton class="ltr:ml-4 rtl:mr-4" :class="{ 'opacity-25': shiftForm.processing }"
                                           :disabled="shiftForm.processing">
                                {{__('Edit Shift')}}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
