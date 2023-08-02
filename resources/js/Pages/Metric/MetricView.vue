<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import FlexButton from "@/Components/FlexButton.vue";
import OrgTabs from "@/Components/Tabs/OrgTabs.vue";
import Card from "@/Components/Card.vue";
import DT from "@/Components/DescriptionList/DT.vue";
import DescriptionList from "@/Components/DescriptionList/DescriptionList.vue";
import DescriptionListItem from "@/Components/DescriptionList/DescriptionListItem.vue";
import DD from "@/Components/DescriptionList/DD.vue";
import ModifyIcon from "@/Components/Icons/ModifyIcon.vue";
import ToolTip from "@/Components/ToolTip.vue";
import GenericButton from "@/Components/GenericButton.vue";
import Swal from "sweetalert2";
import XIcon from "@/Components/Icons/XIcon.vue";
import {__} from "@/Composables/useTranslations.js";

const props = defineProps({
    metric: Object,
})

const form = useForm({});
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
    <Head :title="__('Metric View')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <OrgTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h1 class="card-header">{{__('Metric Details')}}</h1>
                            <div class="flex gap-4">
                                <form @submit.prevent="destroy"  class="flex gap-4">
                                    <GenericButton :text="__('Remove Metric')" :class="{ 'opacity-25': form.processing }"
                                                   :disabled="form.processing">
                                        <XIcon/>
                                    </GenericButton>
                                </form>
                                <FlexButton :text="__('Modify Metric Data')"
                                    :href="route('metrics.edit', {id: metric.id})"
                                >
                                    <ModifyIcon/>
                                </FlexButton>
                            </div>
                        </div>

                        <h2 class="mb-2 ml-1 font-semibold">{{__('Basic Info')}}</h2>
                        <DescriptionList class="!pb-0">

                            <DescriptionListItem class="!col-span-2" colored>
                                <DT>{{__('Metric Criterion')}}</DT>
                                <DD>{{ metric.criteria }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem >
                                <DT>{{__('Metric Weight')}}</DT>
                                <DD>{{ metric.weight }}</DD>
                            </DescriptionListItem>

                            <DescriptionListItem>
                                <DT>{{__('Metric Step')}}
                                    <ToolTip direction="bottom">
                                        {{__('Metric step is the increase/decrease between each evaluation stage.')}}
                                        <br/><br/>{{__('For Example, if the step is 5%:')}}<br/><br/>
                                        <b>{{__('Good')}}:</b> {{__('5% increase from Neutral.')}}<br/>
                                        <b>{{__('Very Good')}}:</b>{{__(' 5% increase from Good, 10% increase from Neutral.')}}<br/>
                                        <b>{{__('Excellent')}}:</b> {{__('5% increase from Very Good, 15% increase from Neutral.')}}<br/><br/>
                                        {{__('And similarly for the decrease:')}} <br/><br/>
                                        <b>{{__('Poor')}}:</b> {{__('-5% decrease from Neutral.')}}<br/>
                                        <b>{{__('Very Poor')}}:</b> {{__('-5% decrease from Poor, -10% decrease from Neutral.')}}<br/>
                                        <b>{{__('Extremely Poor')}}:</b> {{__('-5% decrease from Very Poor, -15% decrease from Neutral.')}}<br/>
                                    </ToolTip>
                                </DT>
                                <DD>{{ metric.step * 100 }}%</DD>
                            </DescriptionListItem>
                        </DescriptionList>
                    </div>
                </Card>

                <Card>
                    <h1 class="card-header">{{__('What is Metric Step?')}}</h1>
                    <p class="text-sm pt-4 ">
                        {{__('Metric step is the increase/decrease between each evaluation stage.')}}
                        <br/><br/>{{__('For Example, if the step is 5%:')}}<br/><br/>
                        <b>{{__('Good')}}:</b> {{__('5% increase from Neutral.')}}<br/>
                        <b>{{__('Very Good')}}:</b>{{__(' 5% increase from Good, 10% increase from Neutral.')}}<br/>
                        <b>{{__('Excellent')}}:</b> {{__('5% increase from Very Good, 15% increase from Neutral.')}}<br/><br/>
                        {{__('And similarly for the decrease:')}} <br/><br/>
                        <b>{{__('Poor')}}:</b> {{__('-5% decrease from Neutral.')}}<br/>
                        <b>{{__('Very Poor')}}:</b> {{__('-5% decrease from Poor, -10% decrease from Neutral.')}}<br/>
                        <b>{{__('Extremely Poor')}}:</b> {{__('-5% decrease from Very Poor, -15% decrease from Neutral.')}}<br/>
                    </p>
                </Card>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
