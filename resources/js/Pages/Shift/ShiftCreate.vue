<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import OrgTabs from "@/Components/Tabs/OrgTabs.vue";
import {useToast} from "vue-toastification";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import Card from "@/Components/Card.vue";
import {inject} from "vue";
import {__} from "@/Composables/useTranslations.js";

const shiftForm = useForm({
    name: '',
    start_time: '',
    end_time: '',
    shift_payment_multiplier: '',
    description: '',
});

const submitShift = () => {
    shiftForm.post(route('shifts.store'), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Creating Shift'));
        },
        onSuccess: () => {
            useToast().success(__('Shift Created Successfully'));
            document.getElementById('closeShiftModal').click();
            shiftForm.reset();
        }
    });
};

</script>
<template>
    <Head :title="__('Shift Create')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-6">{{__('Add A Shift')}}</h1>
                    <form @submit.prevent="submitShift">
                        <div>
                            <InputLabel for="shift_name" :value="__('Shift Name')"/>
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
                                id="start_time"
                                v-model="shiftForm.start_time"
                                class="mt-1 block w-full"
                                :class="{'border border-red-500': shiftForm.errors.start_time}"
                                :placeholder="__('Select a Time...')"
                                time-picker
                                :is-24="false"
                                :dark="inject('isDark').value"
                                required
                            ></VueDatePicker>
                            <InputError class="mt-2" :message="shiftForm.errors.start_time"/>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="end_time" :value="__('Shift End Time')"/>
                            <VueDatePicker
                                id="end_time"
                                v-model="shiftForm.end_time"
                                class="mt-1 block w-full"
                                :class="{'border border-red-500': shiftForm.errors.end_time}"
                                :placeholder="__('Select a Time...')"
                                time-picker
                                :is-24="false"
                                :dark="inject('isDark').value"
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
                            <PrimaryButton class="ltr:ml-4 rtl:mr-4" :class="{ 'opacity-25': shiftForm.processing }"
                                           :disabled="shiftForm.processing">
                                {{__('Add Shift')}}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
