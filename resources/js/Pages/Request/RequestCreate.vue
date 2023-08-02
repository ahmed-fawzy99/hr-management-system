<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import {useToast} from "vue-toastification";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ReqTabs from "@/Components/Tabs/ReqTabs.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import '@vuepic/vue-datepicker/dist/main.css'
import dayjs from "dayjs";
import Card from "@/Components/Card.vue";
import {inject, watch} from "vue";
import {__} from "@/Composables/useTranslations.js";

defineProps({
    types: Array,
});

const form = useForm({
    type: '',
    date: '',
    message: '',
});

watch(() => form.type, (value) => {
    if (value === 'leave')
        form.date = '';
});

const submitForm = () => {
    Object.keys(form.date).forEach(function (key) {
        if (form.date[key] && !/^\d{4}-\d{2}-\d{2}$/.test(form.date[key])){
            form.date[key] = dayjs(form.date[key]).format('YYYY-MM-DD');
        }
    });
    console.log(form.date);
    form.post(route('requests.store'), {
        preserveScroll: true,
        onError: () => {
            if (usePage().props.errors.past_leave){
                useToast().error(usePage().props.errors.past_leave);
            } else {
                useToast().error(__('Error Creating Request'));
            }
        },
        onSuccess: () => {
            useToast().success(__('Request Created Successfully'));
            form.reset();
        }
    });
};

</script>
<template>
    <Head :title="__('Request Create')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <ReqTabs />
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{__('Create a Request')}}</h1>
                    <form @submit.prevent="submitForm">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="type_id" :value="__('Type')"/>
                                <select id="manager_id" class="fancy-selector" v-model="form.type">
                                    <option selected value="">{{__('Choose a Request Type')}}</option>
                                    <option v-for="type in types" :key="type.id" :value="type">
                                        {{ type }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.type"/>
                            </div>
                            <div>
                                <InputLabel for="date" :value="__('Date (Range selection is available)')"/>
                                <VueDatePicker
                                    id="date"
                                    v-model="form.date"
                                    class="py-1 block w-full"
                                    :class="{'border border-red-500': form.errors.date}"
                                    :placeholder="__('Select Date...')"
                                    :enable-time-picker="false"
                                    :min-date="form.type === 'leave'? dayjs().tz().format() : ''"
                                    :dark="inject('isDark').value"
                                    range
                                    required
                                ></VueDatePicker>
                                <InputError class="mt-2" :message="form.errors.date"/>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4 mt-4">
                            <div>
                                <InputLabel for="message" :value="__('Message')"/>
                                <TextInput
                                    id="message"
                                    type="text"
                                    class="mt-1 block w-full"
                                    :class="{'border border-red-500': form.errors.message}"
                                    v-model="form.message"
                                    autocomplete="off"
                                    :placeholder="__('I will be absent for 3 days because I\'m sick.')"
                                />
                                <InputError class="mt-2" :message="form.errors.message"/>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ltr:ml-4 rtl:mr-4" :class="{ 'opacity-25': form.processing }"
                                           :disabled="form.processing">
                                {{__('Initiate Request')}}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
