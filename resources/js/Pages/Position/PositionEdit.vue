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
    position: Object,
})

const positionForm = useForm({
    name: props.position.name,
    description: props.position.description,
});

const submitPosition = () => {
    positionForm.put(route('positions.update', {id: props.position.id}), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Editing Position'));
        },
        onSuccess: () => {
            useToast().success(__('Position Edited Successfully'));
            positionForm.reset();
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
        title: __('This position has :n employee. Are you sure?', {n: props.position.employees_count}),
        text: __('You won\'t be able to revert this!'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: __('Yes, Delete!'),
        cancelButtonText:  __('No, Cancel!'),
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            positionForm.delete(route('positions.destroy', {id: props.position.id}), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire(__('Position Removed!'), '', 'success')
                },
            });
        }
    })
};

</script>
<template>
    <Head :title="__('Position Edit')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{__('Edit A Position')}}</h1>
                    <form @submit.prevent="submitPosition">
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
                        <div class="flex items-center justify-end mt-4">
                            <form @submit.prevent="destroy" class=" inline">
                                <PrimaryButton class="bg-red-600 hover:bg-red-700 ml-4">
                                    {{__('Delete Position')}}
                                </PrimaryButton>
                            </form>
                            <PrimaryButton class="ltr:ml-4 rtl:mr-4" :class="{ 'opacity-25': positionForm.processing }"
                                           :disabled="positionForm.processing">
                                {{__('Edit Position')}}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
