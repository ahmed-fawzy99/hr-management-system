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

const positionForm = useForm({
    name: '',
    description: '',
});

const submitPosition = () => {
    positionForm.post(route('positions.store'), {
        preserveScroll: true,
        onError: () => {
            useToast().error(__('Error Creating Position'));
        },
        onSuccess: () => {
            useToast().success(__('Position Created Successfully'));
            positionForm.reset();
        }
    });
};

</script>
<template>
    <Head :title="__('Position Create')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{__('Add A Position')}}</h1>
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
                            <PrimaryButton class="ltr:ml-4 rtl:mr-4" :class="{ 'opacity-25': positionForm.processing }"
                                           :disabled="positionForm.processing">
                                {{__('Add Position')}}
                            </PrimaryButton>
                        </div>
                    </form>
                </Card>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
