<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import FlexButton from "@/Components/FlexButton.vue";
import CalendarTabs from "@/Components/Tabs/CalendarTabs.vue";
import Card from "@/Components/Card.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";
import {calendar_types} from "@/Composables/useCalendarItemTypes.js";
import Table from "@/Components/Table/Table.vue";
import TableBodyHeader from "@/Components/Table/TableBodyHeader.vue";
import TableBody from "@/Components/Table/TableBody.vue";
import TableHead from "@/Components/Table/TableHead.vue";
import TableRow from "@/Components/Table/TableRow.vue";
import TableBodyAction from "@/Components/Table/TableBodyAction.vue";


defineProps({
    calendarItems: Object,
})
</script>
<template>
    <Head :title="__('Calendar Items')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <CalendarTabs />
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{__('Current Calendar Items')}}</h1>
                    <div class="flex justify-between items-center pb-4">
                        <FlexButton :href="route('calendars.create')" :text="__('Create a New Item')">
                            <PlusIcon/>
                        </FlexButton>
                    </div>
                    <Table :links="calendarItems.links" :showingNumber="calendarItems.data.length"
                           :totalNumber="calendarItems.total">
                        <template #Head>
                            <TableHead>{{__('ID')}}</TableHead>
                            <TableHead>{{__('Title')}}</TableHead>
                            <TableHead>{{__('Type')}}</TableHead>
                            <TableHead>{{__('Start Date')}}</TableHead>
                            <TableHead>{{__('End Date')}}</TableHead>
                            <TableHead>{{__('Actions')}}</TableHead>
                        </template>

                        <!--Iterate Here-->
                        <template #Body>
                            <TableRow v-for="item in calendarItems.data" :key="item.id">
                                <TableBodyHeader>
                                    <Link :href="route('calendars.show', {id: item.id})">{{ item.id }}</Link>
                                </TableBodyHeader>
                                <TableBodyHeader>
                                    <Link :href="route('calendars.show', {id: item.id})">{{ item.title }}</Link>
                                </TableBodyHeader>
                                <TableBody>
                                    <Link :href="route('calendars.show', {id: item.id})">{{ calendar_types[item.type] }}</Link>
                                </TableBody>
                                <TableBody>
                                    <Link :href="route('calendars.show', {id: item.id})">{{ item.start_date }}</Link>
                                </TableBody>
                                <TableBody>
                                    <Link :href="route('calendars.show', {id: item.id})">{{ item.end_date ?? __('None') }}</Link>
                                </TableBody>
                                <TableBodyAction>
                                    <Link :href="route('calendars.edit', {id: item.id})"> {{__('Edit')}}</Link>
                                </TableBodyAction>
                            </TableRow>
                        </template>
                    </Table>

                </Card>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
