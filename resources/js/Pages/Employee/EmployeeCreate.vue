<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import EmployeeTabs from "@/Components/Tabs/EmployeeTabs.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import GenericModal from "@/Components/GenericModal.vue";
import {useToast} from "vue-toastification";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import {Switch} from "@headlessui/vue";
import Card from "@/Components/Card.vue";
import {inject} from "vue";
import {__} from "@/Composables/useTranslations.js";
import ToolTip from "@/Components/ToolTip.vue";
import dayjs from "dayjs";

const props = defineProps({
    departments: Object,
    branches: Object,
    positions: Object,
    shifts: Object,
    roles: Object,
})

const form = useForm({
    name: '',
    national_id: '',
    email: '',
    phone: '',
    address: '',
    bank_acc_no: '',
    hired_on: new Date(),
    branch_id: '',
    department_id: '',
    position_id: '',
    shift_id: '',
    currency: '',
    salary: '',
    role: '',
    is_remote: false,
});

const positionForm = useForm({
    name: '',
    description: '',
});
const shiftForm = useForm({
    name: '',
    start_time: '',
    end_time: '',
    shift_payment_multiplier: '',
    description: '',
});

const branchForm = useForm({
    name: '',
    address: '',
    phone: '',
    email: '',
});
const departmentForm = useForm({
    name: '',
});

const submit = () => {
    form.hired_on = dayjs(form.hired_on).format('YYYY-MM-DD');
    form.post(route('employees.store'), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Creating Employee'));
        },
        onSuccess: () => {
            useToast().success(__('Employee Created Successfully'));
        },
    });
};

const submitPosition = () => {
    positionForm.post(route('positions.store'), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Creating Position'));
        },
        onSuccess: () => {
            useToast().success(__('Position Created Successfully'));
            document.getElementById('closePositionModal').click();
            positionForm.reset();
            form.position_id = props.positions.length;
        }
    });
};
const submitBranch = () => {
    branchForm.post(route('branches.store'), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Creating Branch'));
        },
        onSuccess: () => {
            useToast().success(__('Branch Created Successfully'));
            document.getElementById('closeBranchModal').click();
            branchForm.reset();
            form.branch_id = props.branches.length;
        }
    });
};
const submitDepartment = () => {
    departmentForm.post(route('departments.store'), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Creating Department'));
        },
        onSuccess: () => {
            useToast().success(__('Department Created Successfully'));
            document.getElementById('closeDepartmentModal').click();
            departmentForm.reset();
            form.department_id = props.departments.length;
        }
    });
};
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
            form.shift_id = props.shifts.length;
        }
    });
};

</script>

<template>
    <Head :title="__('Employee Register')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <EmployeeTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                        <p class="card-header">{{__('Add a New Employee')}}</p>
                        <form @submit.prevent="submit" class="form">
                            <div class="grid grid-cols-2 gap-8">
                                <div>
                                    <InputLabel for="name" :value="__('Full Name')"/>
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        :class="{'border-2 border-red-500': form.errors.name}"
                                        v-model="form.name"
                                        required
                                        autofocus
                                        autocomplete="name"
                                        :placeholder="__('John Doe')"
                                    />
                                    <InputError class="mt-2" :message="form.errors.name"/>
                                </div>

                                <div>
                                    <InputLabel for="national_id" :value="__('National ID')"/>
                                    <TextInput
                                        id="national_id"
                                        type="number"
                                        class="mt-1 block w-full"
                                        :class="{'border border-red-500': form.errors.national_id}"
                                        v-model="form.national_id"
                                        required
                                        pattern="[0-9]{14}"
                                        autocomplete="off"
                                        placeholder="29412010135971"
                                    />
                                    <InputError class="mt-2" :message="form.errors.national_id"/>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-8 mt-4">
                                <div>
                                    <InputLabel for="phone" :value="__('Phone')"/>
                                    <TextInput
                                        id="phone"
                                        type="text"
                                        class="mt-1 block w-full"
                                        :class="{'border border-red-500': form.errors.phone}"
                                        v-model="form.phone"
                                        required
                                        autocomplete="off"
                                        placeholder="0110118999"
                                    />
                                    <InputError class="mt-2" :message="form.errors.phone"/>
                                </div>
                                <div>
                                    <InputLabel for="email" :value="__('Email')"/>
                                    <TextInput
                                        id="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        :class="{'border border-red-500': form.errors.email}"
                                        v-model="form.email"
                                        required
                                        autocomplete="off"
                                        placeholder="john.doe@mail.com"
                                    />
                                    <InputError class="mt-2" :message="form.errors.email"/>
                                </div>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="address" :value="__('Address')"/>
                                <TextInput
                                    id="address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    :class="{'border border-red-500': form.errors.address}"
                                    v-model="form.address"
                                    required
                                    autocomplete="off"
                                    :placeholder="__('114 Joseph Tito, El-Nozha, Cairo, Egypt.')"
                                />
                                <InputError class="mt-2" :message="form.errors.address"/>
                            </div>

                            <div class="grid grid-cols-2 gap-8 mt-4 ">
                                <div>
                                    <InputLabel for="ban_acc_no" :value="__('Bank Account Number (Optional)')"/>
                                    <TextInput
                                        id="bank_acc_no"
                                        type="text"
                                        class="mt-1 block w-full"
                                        :class="{'border border-red-500': form.errors.bank_acc_no}"
                                        v-model="form.bank_acc_no"
                                        autocomplete="off"
                                        placeholder="EG380019000500000000263180002"
                                    />
                                    <InputError class="mt-2" :message="form.errors.bank_acc_no"/>

                                </div>
                                <div>
                                    <InputLabel for="hired_on" :value="__('Hire Date')"/>
                                    <VueDatePicker
                                        id="hired_on"
                                        v-model="form.hired_on"
                                        class="py-1 block w-full"
                                        :class="{'border border-red-500': form.errors.hired_on}"
                                        :enable-time-picker="false"
                                        :placeholder="__('Select a Date...')"
                                        :dark="inject('isDark').value"
                                        required
                                    ></VueDatePicker>

                                    <InputError class="mt-2" :message="form.errors.hired_on"/>
                                </div>
                            </div>


                            <div class="grid grid-cols-2 gap-8 mt-4 ">
                                <div>
                                    <InputLabel for="branch_id" :value="__('Branch')"/>
                                    <select id="branch_id" class="fancy-selector" v-model="form.branch_id">
                                        <option selected value="">{{__('Choose a Branch')}}</option>
                                        <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                                            {{ branch.name }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.branch_id"/>
                                    <p class="text-xs inline ltr:mr-1 rtl:ml-1 "> {{__('Branch not listed?')}} </p>
                                    <form @submit.prevent="submitBranch" class=" inline">
                                        <GenericModal :modalId="'branchModal'"
                                                      :title="__('Create a new one.')" :modalHeader="__('Create a New Branch')"
                                                      :footerActionName="__('Save')" :hasCancel="false"
                                                      :has-custom-footer="true">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <InputLabel for="branch_name" :value="__('Branch Name')"/>
                                                    <TextInput
                                                        id="branch_name"
                                                        type="text"
                                                        class="mt-1 block w-full"
                                                        :class="{'border border-red-500': branchForm.errors.name}"
                                                        v-model="branchForm.name"
                                                        required
                                                        autocomplete="off"
                                                        :placeholder="__('Joseph Tito Branch')"
                                                    />
                                                    <InputError class="mt-2" :message="branchForm.errors.name"/>
                                                </div>
                                                <div>
                                                    <InputLabel for="branch_address" :value="__('Branch Address')"/>
                                                    <TextInput
                                                        id="branch_address"
                                                        type="text"
                                                        class="mt-1 block w-full"
                                                        :class="{'border border-red-500': branchForm.errors.address}"
                                                        v-model="branchForm.address"
                                                        required
                                                        autocomplete="off"
                                                        :placeholder="__('El-Nozha, Cairo, Egypt')"
                                                    />
                                                    <InputError class="mt-2" :message="branchForm.errors.address"/>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 mt-4">
                                                <div>
                                                    <InputLabel for="branch_phone" :value="__('Department Phone')"/>
                                                    <TextInput
                                                        id="branch_phone"
                                                        type="text"
                                                        class="mt-1 block w-full"
                                                        :class="{'border border-red-500': branchForm.errors.phone}"
                                                        v-model="branchForm.phone"
                                                        required
                                                        autocomplete="off"
                                                        placeholder="+20123456789"
                                                    />
                                                    <InputError class="mt-2" :message="branchForm.errors.phone"/>
                                                </div>
                                                <div>
                                                    <InputLabel for="branch_email" :value="__('Department Email')"/>
                                                    <TextInput
                                                        id="branch_email"
                                                        type="text"
                                                        class="mt-1 block w-full"
                                                        :class="{'border border-red-500': branchForm.errors.email}"
                                                        v-model="branchForm.email"
                                                        required
                                                        autocomplete="off"
                                                        placeholder="JospephTito@Company.com"
                                                    />
                                                    <InputError class="mt-2" :message="branchForm.errors.email"/>
                                                </div>
                                            </div>
                                            <template #customFooter>
                                                <button id="submitBranchButton" type="submit"
                                                        class="ltr:mr-4 rtl:ml-4 text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">
                                                    {{__('Save')}}
                                                </button>
                                                <button data-modal-hide="branchModal" type="button" id="closeBranchModal"
                                                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                    {{__('Close')}}
                                                </button>
                                            </template>
                                        </GenericModal>
                                    </form>
                                </div>

                                <div>
                                    <InputLabel for="department_id" :value="__('Department')"/>
                                    <select id="department_id" class="fancy-selector" v-model="form.department_id">
                                        <option selected value="">{{__('Choose a Department')}}</option>
                                        <option v-for="department in departments" :key="department.id"
                                                :value="department.id">{{ department.name }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.department_id"/>
                                    <p class="text-xs inline ltr:mr-1 rtl:ml-1"> {{__('Department not listed?')}} </p>
                                    <form @submit.prevent="submitDepartment" class=" inline">
                                        <GenericModal modalId='departmentModal'
                                                      :title="__('Create a new one.')" :modalHeader="__('Create a New Department')"
                                                      :footerActionName="__('Save')" :hasCancel="false"
                                                      :has-custom-footer="true">
                                            <div>
                                                <InputLabel for="department_name" :value="__('Department Name')"/>
                                                <TextInput
                                                    id="department_name"
                                                    type="text"
                                                    class="mt-1 block w-full"
                                                    :class="{'border border-red-500': departmentForm.errors.name}"
                                                    v-model="departmentForm.name"
                                                    required
                                                    autocomplete="off"
                                                    :placeholder="__('Sales')"
                                                />
                                                <InputError class="mt-2" :message="departmentForm.errors.name"/>
                                            </div>
                                            <template #customFooter>
                                                <button id="submitDepartmentButton" type="submit"
                                                        class="ltr:mr-4 rtl:ml-4 text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">
                                                    {{__('Save')}}
                                                </button>
                                                <button data-modal-hide="departmentModal" type="button"
                                                        id="closeDepartmentModal"
                                                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                    {{__('Close')}}
                                                </button>
                                            </template>
                                        </GenericModal>
                                    </form>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-8 mt-4">
                                <div>
                                    <InputLabel for="position_id" :value="__('Position')"/>
                                    <select id="position_id" class="fancy-selector" v-model="form.position_id">
                                        <option selected value="">{{__('Choose a Position')}}</option>
                                        <option v-for="position in positions" :key="position.id" :value="position.id">
                                            {{ position.name }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.position_id"/>
                                    <p class="text-xs inline ltr:mr-1 rtl:ml-1"> {{__('Position not listed?')}} </p>
                                    <form @submit.prevent="submitPosition" class=" inline">
                                        <GenericModal modalId="positionModal"
                                                      :title="__('Create a new one.')" :modalHeader="__('Create a New Position')"
                                                      :footerActionName="__('Save')" :hasCancel="false"
                                                      :has-custom-footer="true">
                                            <div>
                                                <InputLabel for="position" :value="__('Position Name')"/>
                                                <TextInput
                                                    id="position"
                                                    type="text"
                                                    class="mt-1 block w-full"
                                                    :class="{'border border-red-500': positionForm.errors.name}"
                                                    v-model="positionForm.name"
                                                    required
                                                    autocomplete="off"
                                                    :placeholder="__('Software Engineer')"
                                                />
                                                <InputError class="mt-2" :message="positionForm.errors.name"/>
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="description" :value="__('Position Description')"/>
                                                <TextInput
                                                    id="description"
                                                    type="text"
                                                    class="mt-1 block w-full"
                                                    :class="{'border border-red-500': positionForm.errors.description}"
                                                    v-model="positionForm.description"
                                                    autocomplete="off"
                                                    :placeholder="__('Responsible for designing and developing software solutions.')"
                                                />
                                                <InputError class="mt-2" :message="positionForm.errors.description"/>
                                            </div>
                                            <template #customFooter>
                                                <button id="submitPositionButton" type="submit"
                                                        class="ltr:mr-4 rtl:ml-4 text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">
                                                    {{__('Save')}}
                                                </button>
                                                <button data-modal-hide="positionModal" type="button"
                                                        id="closePositionModal"
                                                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                    {{__('Close')}}
                                                </button>
                                            </template>
                                        </GenericModal>
                                    </form>
                                </div>
                                <div>
                                    <InputLabel for="shift_id" :value="__('Shift')"/>
                                    <select id="shift_id" class="fancy-selector" v-model="form.shift_id">
                                        <option selected value="">{{__('Choose a Shift')}}</option>
                                        <option v-for="shift in shifts" :key="shift.id" :value="shift.id">
                                            {{ shift.name }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.shift_id"/>
                                    <p class="text-xs inline ltr:mr-1 rtl:ml-1"> {{__('Shift not listed?')}} </p>
                                    <form @submit.prevent="submitShift" class=" inline">
                                        <GenericModal modalId='shiftModal'
                                                      :title="__('Create a new one.')" :modalHeader="__('Create a New Shift')"
                                                      :footerActionName="__('Save')" :hasCancel="false"
                                                      :has-custom-footer="true">
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
                                            <div>
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
                                            <div>
                                                <InputLabel for="end_time" :value="__('Shift End Time')"/>
                                                <VueDatePicker
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
                                            <div>
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
                                                />
                                                <InputError class="mt-2" :message="shiftForm.errors.shift_payment_multiplier"/>
                                            </div>
                                            <div>
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

                                            <template #customFooter>
                                                <button id="submitShiftButton" type="submit"
                                                        class="ltr:mr-4 rtl:ml-4 text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">
                                                    {{__('Save')}}
                                                </button>
                                                <button data-modal-hide="shiftModal" type="button" id="closeShiftModal"
                                                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                    {{__('Close')}}
                                                </button>
                                            </template>
                                        </GenericModal>
                                    </form>
                                </div>

                            </div>
                            <div class="grid grid-cols-2 gap-8 mt-4">
                                <div>
                                    <InputLabel for="salary" :value="__('Salary')" class="mb-1"/>
                                    <div class="grid grid-cols-6">
                                        <select id="currency"
                                                class="fancy-selector-inline-textInput col-span-2 z-10 !mt-0"
                                                v-model="form.currency">
                                            <option value='' selected>Currency</option>
                                            <option value="EGP">EGP</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                            <option value="GBP">GBP</option>
                                            <option value="CAD">CAD</option>
                                            <option value="SAR">SAR</option>
                                            <option value="AED">AED</option>
                                            <option value="KWD">KWD</option>
                                        </select>
                                        <TextInput
                                            id="salary"
                                            type="number"
                                            class="inline ltr:rounded-l-none rtl:rounded-r-none col-span-4"
                                            :class="{'border border-red-500': form.errors.salary}"
                                            v-model="form.salary"
                                            required
                                            autocomplete="off"
                                            placeholder="10000"
                                        />
                                    </div>
                                    <InputError class="mt-2" :message="form.errors.currency"/>
                                    <InputError class="mt-2" :message="form.errors.salary"/>
                                </div>
                                <div>
                                    <InputLabel for="role" :value="__('Permissions Level')"/>
                                    <select id="role" class="fancy-selector" v-model="form.role">
                                        <option selected value="">{{__('Choose a Permission Level')}}</option>
                                        <option v-for="role in roles" :key="role.id" :value="role.name">
                                            {{ role.name }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.role"/>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-8 mt-4">
                                <div>
                                    <InputLabel for="is_remote" :value="__('Remote Worker?')" class="inline"/>
                                    <ToolTip>
                                        {{__('Remote Workers can take attendance anywhere, not necessarily from the organization')}}, <br/>
                                        {{__('if ip-based attendance is enabled from the organization settings')}}.
                                    </ToolTip>
                                    <div>
                                        <Switch
                                            v-model="form.is_remote" dir="ltr"
                                            :class="form.is_remote ? 'bg-purple-600' : 'bg-gray-400'"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full mt-1"
                                        >
                                            <span class="sr-only">{{__('Remote Worker')}}</span>
                                            <span
                                                :class="form.is_remote ? 'translate-x-6' : 'translate-x-1'"
                                                class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                                            />
                                        </Switch>
                                    </div>
                                    <InputError class="mt-2" :message="form.errors.is_remote"/>
                                </div>
                            </div>


                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton class="ltr:ml-4 rtl:mr-4" :class="{ 'opacity-25': form.processing }"
                                               :disabled="form.processing">
                                    {{__('Add Employee')}}
                                </PrimaryButton>
                            </div>
                        </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>




