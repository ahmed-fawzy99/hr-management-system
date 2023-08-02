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


const form = useForm({
    criteria: '',
    weight: '',
    step: '',
});

const submit = () => {
    form.post(route('metrics.store'), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Creating Metric'));
        },
        onSuccess: () => {
            useToast().success(__('Metric Created Successfully'));
            form.reset();
        }
    });
};

</script>
<template>
    <Head :title="__('Metric Create')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{__('Add A Metric')}}</h1>
                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="criteria" :value="__('Metric Criteria')"/>
                            <TextInput
                                id="criteria"
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
                                <InputLabel for="step" :value="__('Step') + '(%)'"/>
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
                            <PrimaryButton class="ltr:ml-4 rtl:mr-4" :class="{ 'opacity-25': form.processing }"
                                           :disabled="form.processing">
                                {{__('Add Metric')}}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
