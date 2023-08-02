<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import OrgTabs from "@/Components/Tabs/OrgTabs.vue";
import {useToast} from "vue-toastification";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Card from "@/Components/Card.vue";
import {__} from "@/Composables/useTranslations.js";

defineProps({
    employees: Object,
});

const departmentForm = useForm({
    name: '',
    manager_id: '',
});

const submitDepartment = () => {
    departmentForm.post(route('departments.store'), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Creating Department'));
        },
        onSuccess: () => {
            useToast().success(__('Department Created Successfully'));
            departmentForm.reset();
        }
    });
};

</script>
<template>
    <Head :title="__('Department Create')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{__('Add A Department')}}</h1>
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
                                    :placeholder="__('Sales')"
                                />
                                <InputError class="mt-2" :message="departmentForm.errors.name"/>
                            </div>
                            <div>
                                <InputLabel for="manager_id" :value="__('Manager')"/>
                                <select id="manager_id" class="fancy-selector" v-model="departmentForm.manager_id">
                                    <option selected value="">{{__('Choose an Employee')}}</option>
                                    <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                                        {{ employee.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="departmentForm.errors.manager_id"/>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ltr:ml-4 rtl:mr-4" :class="{ 'opacity-25': departmentForm.processing }"
                                           :disabled="departmentForm.processing">
                                {{__('Add Department')}}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
