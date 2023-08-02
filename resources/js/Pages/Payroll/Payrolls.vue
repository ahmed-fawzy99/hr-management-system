<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import Table from "@/Components/Table/Table.vue";
import TableHead from "@/Components/Table/TableHead.vue";
import TableBody from "@/Components/Table/TableBody.vue";
import TableBodyHeader from "@/Components/Table/TableBodyHeader.vue";
import TableBodyAction from "@/Components/Table/TableBodyAction.vue";
import TableRow from "@/Components/Table/TableRow.vue";
import PayrollTabs from "@/Components/Tabs/PayrollTabs.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import '@vuepic/vue-datepicker/dist/main.css'
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {inject, ref, watch} from "vue";
import Card from "@/Components/Card.vue";

const props = defineProps({
    payrolls: Object,
    dateParam: String,
    statusParam: String,
});

const date = ref(new Date(props.dateParam));
if (props.dateParam === '') {
    date.value = '';
}

const status = ref(props.statusParam);
if (props.statusParam === '') {
    status.value = 'all';
}

const filter = (() => {
    const routeParameters = {date: date.value === null ? null : date.value, status: status.value === null ? null : status.value};
    router.visit(route('payrolls.index', routeParameters),
        {preserveState: true, preserveScroll: true})
});
watch(date, filter);
watch(status, filter);

</script>

<template>
    <Head :title="__('Payrolls')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <PayrollTabs />
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header !mb-4">{{__('Payrolls')}}</h1>
                    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                        <div>
                            <InputLabel for="date" :value="__('Filter by Month') +':'"/>
                            <VueDatePicker
                                id="date"
                                v-model="date"
                                class="py-1 block w-full"
                                :placeholder="__('Select a Date...')"
                                :enable-time-picker="false"
                                :max-date="new Date()"
                                month-picker
                                :dark="inject('isDark').value"
                                required
                            ></VueDatePicker>
                            <InputError v-if="Object.keys($page.props.errors).length" class="mt-2" :message="$page.props.errors"/>
                        </div>

                        <div class="w-1/2" dir="ltr">
                            <InputLabel for="date" :value="__('Filter by Status')+':'"/>
                            <ul class="ul-checkbox mb-1">
                                <li class="li-checkbox">
                                    <div class="ul-li-div-radio">
                                        <input id="horizontal-list-radio-all" type="radio" value="all" v-model="status" name="list-radio" class="li-radio-input">
                                        <label for="horizontal-list-radio-all" class="li-radio-label">{{__('All')}}</label>
                                    </div>
                                </li>
                                <li class="li-checkbox">
                                    <div class="ul-li-div-radio">
                                        <input id="horizontal-list-radio-pending" type="radio" value="pending" v-model="status" name="list-radio" class="li-radio-input">
                                        <label for="horizontal-list-radio-pending" class="li-radio-label">{{__('Pending')}}</label>
                                    </div>
                                </li>
                                <li class="li-checkbox">
                                    <div class="ul-li-div-radio">
                                        <input id="horizontal-list-radio-reviewed" type="radio" value="reviewed" v-model="status" name="list-radio" class="li-radio-input">
                                        <label for="horizontal-list-radio-reviewed" class="li-radio-label">{{__('Reviewed')}}</label>
                                    </div>
                                </li>
                                <li class="li-checkbox">
                                    <div class="ul-li-div-radio">
                                        <input id="horizontal-list-radio-paid" type="radio" value="paid" v-model="status" name="list-radio" class="li-radio-input">
                                        <label for="horizontal-list-radio-paid" class="li-radio-label">{{__('Paid')}}</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <Table :links="payrolls.links" :showingNumber="payrolls.data.length" :totalNumber="payrolls.total">
                        <template #Head>
                            <TableHead>{{__('Payroll ID')}}</TableHead>
                            <TableHead>{{__('Employee Name')}}</TableHead>
                            <TableHead>{{__('Total Amount')}}</TableHead>
                            <TableHead>{{__('Due Date')}}</TableHead>
                            <TableHead>{{__('Status')}}</TableHead>
                            <TableHead v-if="$page.props.auth.user.roles.includes('admin')">{{__('Action')}}</TableHead>
                        </template>

                        <!--Iterate Here-->
                        <template #Body>
                            <TableRow v-for="payroll in payrolls.data" :key="payroll.id">
                                <TableBodyHeader :href="route('payrolls.show', {id: payroll.id})">{{payroll.id}}</TableBodyHeader>
                                <TableBodyHeader :href="route('payrolls.show', {id: payroll.id})" >{{payroll.employee_name}}</TableBodyHeader>
                                <TableBody :href="route('payrolls.show', {id: payroll.id})">{{payroll.currency}} {{payroll.total_payable}}</TableBody>
                                <TableBody :href="route('payrolls.show', {id: payroll.id})">{{payroll.due_date}}</TableBody>
                                <TableBody :href="route('payrolls.show', {id: payroll.id})">{{payroll.status  ? "Paid" : (payroll.is_reviewed ? "Reviewed" : "Pending Review")}}</TableBody>
                                <TableBodyAction v-if="$page.props.auth.user.roles.includes('admin')" :href="route('payrolls.edit', {id: payroll.id})">{{__('Edit')}}</TableBodyAction>
                            </TableRow>
                        </template>
                    </Table>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
