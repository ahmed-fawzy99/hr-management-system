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
    branch: Object,
    employees: Object,
})

const branchForm = useForm({
    name: props.branch.name,
    address: props.branch.address,
    phone: props.branch.phone,
    email: props.branch.email,
    manager_id: props.branch.manager ? props.branch.manager.id : '0',
});

const submitBranch = () => {
    branchForm.put(route('branches.update', { id: props.branch.id }), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Editing Branch'));
        },
        onSuccess: () => {
            useToast().success(__('Branch Edited Successfully'));
            branchForm.reset();
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
        title: __('This branch has :n employee. Are you sure?', {n: props.branch.employees_count}),
        text: __('You won\'t be able to revert this!'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: __('Yes, Delete!'),
        cancelButtonText: __('No, Cancel!'),
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            branchForm.delete(route('branches.destroy', {id: props.branch.id}), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire(__('Branch Removed!'), '', 'success')
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
                    <h1 class="card-header !mb-4">{{__('Edit A Branch')}}</h1>
                    <form @submit.prevent="submitBranch">
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
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <InputLabel for="manager_id" :value="__('Manager')"/>
                                <select id="manager_id" class="fancy-selector" v-model="branchForm.manager_id">
                                    <option value="0">{{ __('Choose an Employee') }}</option>
                                    <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                                        {{ employee.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="branchForm.errors.branch_id"/>
                            </div>
                            <div> </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <form @submit.prevent="destroy" class=" inline">
                                <PrimaryButton class="bg-red-600 hover:bg-red-700 ml-4">
                                    {{__('Delete Branch')}}
                                </PrimaryButton>
                            </form>
                            <PrimaryButton class="ltr:ml-4 rtl:mr-4" :class="{ 'opacity-25': branchForm.processing }"
                                           :disabled="branchForm.processing">
                                {{__('Edit Branch')}}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
