<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {computed, inject, onMounted, ref, watch} from "vue";
import {Head, router, useForm, usePage} from '@inertiajs/vue3';
import {useToast} from "vue-toastification";
import VueDatePicker from "@vuepic/vue-datepicker";
import '@vuepic/vue-datepicker/dist/main.css'
import AttendanceTabs from "@/Components/Tabs/AttendanceTabs.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Notice from "@/Components/Notice.vue";
import Card from "@/Components/Card.vue";
import HorizontalRule from "@/Components/HorizontalRule.vue";
import {__} from "@/Composables/useTranslations.js";
import {attendance_types} from "@/Composables/useAttendanceTypes.js"

const props = defineProps({
    employees: Object,
    attendances: Object,
    dateParam: String,
    attendable: Boolean,
});

const full_attendance_exists = computed(() => {
    return props.attendances !== null && Array.isArray(props.attendances) && props.attendances.length === props.employees.length;
});
const partial_attendance_exists = computed(() => {
    return props.attendances !== null && Array.isArray(props.attendances) && (props.attendances.length > 0 && props.attendances.length < props.employees.length);
});

const form = useForm({
    date: ref(new Date(props.dateParam).toISOString().split("T")[0]),
    employee_id: [],
    status: [],
    sign_in_time: [],
    sign_off_time: [],
    notes: [],
    is_manually_filled: [],
});

const submit = () => {
    form.post(route('attendances.store'), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Taking Attendance'));
        },
        onSuccess: () => {
            useToast().success(__('Attendance Taken Successfully'));
        },
    });
};

function fillForm() {
    form.employee_id = Array.from({length: props.employees.length}, (_, index) => props.employees[index].id);
    form.status = Array(props.employees.length).fill("on_time");
    form.sign_in_time = Array(props.employees.length).fill({hours: 9, minutes: 0, seconds: 0});
    form.sign_off_time = Array(props.employees.length).fill({hours: 17, minutes: 0, seconds: 0});
    form.notes = Array(props.employees.length).fill(null);
    form.is_manually_filled = Array(props.employees.length).fill(false);
}

function fillOldForm() {
    // Mapping the employees with the attendances
    let index = 0;
    while (true) {
        for (let i = 0; i < props.attendances.length; i++) {
            if (props.attendances[i].employee_id === props.employees[index].id) {
                const fillObj = props.attendances[i];
                form.status[index] = fillObj.status;
                form.notes[index] = fillObj.notes;
                form.is_manually_filled[index] = fillObj.is_manually_filled;

                if (fillObj.sign_in_time === null) {
                    form.sign_in_time[index] = null;
                } else {
                    const [in_hour, in_minute, in_second] = fillObj.sign_in_time.split(':').map(Number);
                    form.sign_in_time[index] = {hours: in_hour, minutes: in_minute, seconds: in_second};
                }

                if (fillObj.sign_off_time === null) {
                    form.sign_off_time[index] = null;
                } else {
                    const [out_hour, out_minute, out_second] = fillObj.sign_off_time.split(':').map(Number);
                    form.sign_off_time[index] = {hours: out_hour, minutes: out_minute, seconds: out_second};
                }

                break;
            }
        }
        index++;
        if (index >= props.employees.length) {
            break;
        }
    }
}

function displayStatus(partial_attendance_exists, form, index) {
    if (partial_attendance_exists) {
        if (!form.is_manually_filled[index] && form.status[index] !== 'missed') {
            return '⚠️'; // ⚠️ for warning
        } else if (form.is_manually_filled[index]) {
            return form.sign_off_time[index] === null ? '✍️' : '✅'; // ✍️ for manual entry, ✅ for success
        }
    }
    return ''; // Return empty string if no icon should be displayed
}


onMounted(() => {
    if (Object.keys(usePage().props.errors).length) {
        alert(usePage().props.errors['term']);
    }
    fillForm();
    if (full_attendance_exists.value || partial_attendance_exists.value) {
        fillOldForm();
    }
});

// No Need for debounce here
const search = (() => {
    router.visit(route('attendances.create', {term: form.date.toISOString().split('T')[0]}),
        {preserveState: false, preserveScroll: true})
});
watch(() => form.date, search);

</script>

<template>
    <Head :title="__('Take Attendance')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <AttendanceTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-1">{{ __('Take/Edit Attendance') }}</h1>
                    <InputLabel v-if="partial_attendance_exists" class="!font-thin">
                        {{ __('Note: This page overwrites employees manually registered attendance') }}.
                    </InputLabel>
                    <form @submit.prevent="submit" class="pt-4">
                        <div>
                            <InputLabel for="date" :value="__('Select Day')"/>
                            <VueDatePicker
                                id="date"
                                v-model="form.date"
                                class="py-1 block w-full"
                                :class="{'border border-red-500': form.errors.date}"
                                placeholder="Select Date..."
                                :enable-time-picker="false"
                                :max-date="new Date()"
                                :dark="inject('isDark').value"
                                required
                            ></VueDatePicker>
                            <InputError class="mt-2" :message="form.errors.date"/>
                        </div>
                        <Notice v-if="!attendable" type="success" :bold="__('Off Day!')"
                                :text="__('This day is off (Weekend or a Holiday). No attendance for this day') + '. 🎉'"
                        />

                        <Notice v-if="full_attendance_exists" type="warning" :bold="__('Notice!')"
                                :text="__('The selected day\'s attendance has been already taken. You are now editing the previously taken attendance.')"
                        />

                        <Notice v-else-if="partial_attendance_exists" type="warning"
                                :bold="__('Incomplete Attendance!')" br
                                :text="__('Some employees have taken their attendances here while others did not. Any changes you make in this page will overwrite the records here.') + '<br />' +
                                      __('Records with the sign ✍️ denotes that this employee has taken their attendance but hasn\'t signed off yet (incomplete attendance).') + '<br />' +
                                      __('Records with the sign ✅ denotes that this employee has taken their attendance and has signed off (complete attendance).') + '<br />'"
                        />

                        <HorizontalRule class="mt-6"/>

                        <div class="relative shadow-md sm:rounded-lg mt-8 overflow-x-scroll md:overflow-visible">
                            <table class="w-full text-sm text-left ">
                                <thead class="table-thead">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Employee name (ID)') }}
                                    </th>
<!--                                    <th scope="col" class="px-6 py-3">-->
<!--                                        {{ __('ID') }}-->
<!--                                    </th>-->
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Status') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Sign In Time') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Sign Off Time') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Notes?') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(employee, index) in employees" :key="employee.id"
                                    class="table-tr" :class="{'bg-gray-50': index % 2 === 1}"
                                >
                                    <th scope="row" class="table-th">
                                        {{ employee.name + ' (' + employee.id + ')' }}
                                        {{ displayStatus(partial_attendance_exists, form, index) }}
                                        <span class="hidden">{{ form.employee_id[index] = employee.id }}</span>
                                    </th>
<!--                                    <td class="table-td">-->
<!--                                        {{ employee.id }}-->
<!--                                        <span class="hidden">{{ form.employee_id[index] = employee.id }}</span>-->
<!--                                    </td>-->

                                    <td class="table-td w-40">
                                        <select v-model="form.status[index]" class="fancy-selector"
                                                :disabled="!attendable"
                                                :class="{ 'opacity-75 cursor-not-allowed': !attendable }"
                                        >
                                            <option value="on_time" selected>{{ attendance_types['on_time'] }}</option>
                                            <option value="late">{{ attendance_types['late'] }}</option>
                                            <option value="missed">{{ attendance_types['missed'] }}</option>
                                        </select>
                                    </td>
                                    <td class="table-td">
                                        <VueDatePicker
                                            v-model="form.sign_in_time[index]"
                                            class="mt-1 block w-full"
                                            :placeholder="__('Select Time...')"
                                            time-picker
                                            :is-24="false"
                                            required
                                            :dark="inject('isDark').value"
                                            :disabled="!attendable"
                                            :class="{ 'opacity-75 cursor-not-allowed': !attendable }"
                                        ></VueDatePicker>
                                    </td>
                                    <td class="table-td">
                                        <VueDatePicker
                                            v-model="form.sign_off_time[index]"
                                            class="mt-1 block w-full"
                                            :placeholder="__('Select Time...')"
                                            time-picker
                                            :is-24="false"
                                            required
                                            :dark="inject('isDark').value"
                                            :disabled="!attendable"
                                            :class="{ 'opacity-75 cursor-not-allowed': !attendable }"
                                        ></VueDatePicker>
                                    </td>
                                    <td class="table-td">
                                        <TextInput
                                            id="notes"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.notes[index]"
                                            autocomplete="off"
                                            :placeholder="__('Note...')"
                                            :disabled="!attendable"
                                            :class="{ 'opacity-75 cursor-not-allowed': !attendable }"
                                        />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="p-4 text-sm">
                                <p>
                                    {{ __('⚠️ Means there this page has registered attendances recorded, but this record is not recorded and needs to be filled') }}.</p>
                                <p>
                                    {{ __('✍️ Means there this page has registered attendances recorded, and this record has been taken by employee, but hasn\'t signed off yet (incomplete attendance)') }}.</p>
                                <p>
                                    {{ __('✅ Means there this page has registered attendances recorded, and this record\'s attendance has been fully taken by employee (complete attendance)') }}.</p>
                            </div>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ltr:ml-4 rtl:mr-4"
                                           :class="{ 'opacity-25 cursor-not-allowed': form.processing || !attendable }"
                                           :disabled="form.processing || !attendable">
                                {{ __('Confirm Attendance') }}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>

            </div>
        </div>
    </AuthenticatedLayout>
</template>


