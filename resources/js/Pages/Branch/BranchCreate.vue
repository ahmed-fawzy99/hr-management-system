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
const branchForm = useForm({
    name: '',
    address: '',
    phone: '',
    email: '',
    manager_id: '',
});

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
        }
    });
};

</script>
<template>
    <Head :title="__('Branches')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs />
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{__('Add A Branch')}}</h1>
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
                                    <option selected value="">{{__('Choose an Employee')}}</option>
                                    <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                                        {{ employee.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="branchForm.errors.manager_id"/>
                            </div>
                            <div> </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ltr:ml-4 rtl:mr-4" :class="{ 'opacity-25': branchForm.processing }"
                                           :disabled="branchForm.processing">
                                {{__('Add Branch')}}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
