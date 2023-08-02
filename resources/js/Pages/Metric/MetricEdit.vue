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
import Swal from "sweetalert2";
import {__} from "@/Composables/useTranslations.js";

const props = defineProps({
    metric: Object,
});
const form = useForm({
    criteria: props.metric.criteria,
    weight: props.metric.weight,
    step: props.metric.step*100, // Remove the % sign and convert to decimal
});

const submit = () => {
    form.put(route('metrics.update', {id: props.metric.id}), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Updating Metric'));
        },
        onSuccess: () => {
            useToast().success(__('Metric Updated Successfully'));
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
        title: __('Are you sure?'),
        text: __('You won\'t be able to revert this!'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: __('Yes, Delete!'),
        cancelButtonText:  __('No, Cancel!'),
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('metrics.destroy', { id: props.metric.id }), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire(__('Metric Removed!'), '', 'success')
                },
            });
        }
    })
};

</script>
<template>
    <Head :title="__('Metric Removed Successfully')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{__('Update Metric')}}</h1>
                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="Criterion" :value="__('Metric Criterion')"/>
                            <TextInput
                                id="Criterion"
                                type="text"
                                class="mt-1 block w-full"
                                :class="{'border border-red-500': form.errors.criteria}"
                                v-model="form.criteria"
                                required
                                autocomplete="off"
                                :placeholder="__('How effectively did the employee perform their duties and responsibilities?')"
                            />
                            <InputError class="mt-2" :message="form.errors.criteria"/>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="mt-1">
                                <InputLabel for="weight" :value="__('Weight')"/>
                                <TextInput
                                    id="weight"
                                    type="number"
                                    class="mt-1 block w-full"
                                    :class="{'border border-red-500': form.errors.weight}"
                                    v-model="form.weight"
                                    min="0"
                                    step="0.001"
                                    required
                                    autocomplete="off"
                                    placeholder="1"
                                />
                                <InputError class="mt-2" :message="form.errors.weight"/>
                            </div>
                            <div class="mt-1">
                                <InputLabel for="step" :value="__('Step') + ' (%)'"/>
                                <TextInput
                                    id="step"
                                    type="number"
                                    class="mt-1 block w-full"
                                    :class="{'border border-red-500': form.errors.step}"
                                    v-model="form.step"
                                    min="0"
                                    step="0.1"
                                    required
                                    autocomplete="off"
                                    placeholder="5"
                                />
                                <InputError class="mt-2" :message="form.errors.step"/>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <form @submit.prevent="destroy" class=" inline">
                                <PrimaryButton class="bg-red-600 hover:bg-red-700 ml-4" >
                                    {{__('Remove Metric')}}
                                </PrimaryButton>
                            </form>
                            <PrimaryButton class="ltr:ml-4 rtl:mr-4" :class="{ 'opacity-25': form.processing }"
                                           :disabled="form.processing">
                                {{__('Update Metric')}}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
