<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import OrgTabs from "@/Components/Tabs/OrgTabs.vue";
import {useToast} from "vue-toastification";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Swal from "sweetalert2";
import Card from "@/Components/Card.vue";
import {__} from "@/Composables/useTranslations.js";

const props = defineProps({
    department: Object,
    employees: Object,
})

const departmentForm = useForm({
    name: props.department.name,
    manager_id: props.department.manager ? props.department.manager.id : '0',
});

const submitDepartment = () => {
    departmentForm.put(route('departments.update', {id: props.department.id}), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Editing Department'));
        },
        onSuccess: () => {
            useToast().success(__('Department Editing Successfully'));
            departmentForm.reset();
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
        title: __('This department has :n employee. Are you sure?', {n: props.employees.length}),
        text: __('You won\'t be able to revert this!'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: __('Yes, Delete!'),
        cancelButtonText:  __('No, Cancel!'),
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            departmentForm.delete(route('departments.destroy', {id: props.department.id}), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire(__('Department Removed!'), '', 'success')
                },
            });
        }
    })
};

</script>
<template>
    <Head :title="__('Department Edit')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{__('Edit A Department')}}</h1>
                    <form @submit.prevent="submitDepartment">
                        <div class="grid grid-cols-2 gap-4 mt-4">
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
                                    :placeholder="__('Department Name')"
                                />
                                <InputError class="mt-2" :message="departmentForm.errors.name"/>
                            </div>
                            <div>
                                <InputLabel for="manager_id" :value="__('Manager')"/>
                                <select id="manager_id" class="fancy-selector" v-model="departmentForm.manager_id">
                                    <option value="0">{{ __('Choose an Employee') }}</option>
                                    <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                                        {{ employee.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="departmentForm.errors.manager_id"/>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <form @submit.prevent="destroy" class=" inline">
                                <PrimaryButton class="bg-red-600 hover:bg-red-700 ml-4">
                                    {{__('Delete Department')}}
                                </PrimaryButton>
                            </form>
                            <PrimaryButton class="ltr:ml-4 rtl:mr-4" :class="{ 'opacity-25': departmentForm.processing }"
                                           :disabled="departmentForm.processing">
                                {{__('Edit Department')}}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
