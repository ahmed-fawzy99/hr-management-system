<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import {useToast} from "vue-toastification";
import Swal from "sweetalert2";
import PayrollTabs from "@/Components/Tabs/PayrollTabs.vue";
import Card from "@/Components/Card.vue";
import TableBody from "@/Components/Table/TableBody.vue";
import TableBodyHeader from "@/Components/Table/TableBodyHeader.vue";
import TableRow from "@/Components/Table/TableRow.vue";
import InvoiceTable from "@/Components/InvoiceTable/InvoiceTable.vue";
import InvoiceTableHead from "@/Components/InvoiceTable/InvoiceTableHead.vue";
import DescriptionListItem from "@/Components/DescriptionList/DescriptionListItem.vue";
import DT from "@/Components/DescriptionList/DT.vue";
import DD from "@/Components/DescriptionList/DD.vue";
import DescriptionList from "@/Components/DescriptionList/DescriptionList.vue";
import TextInput from "@/Components/TextInput.vue";
import ToolTip from "@/Components/ToolTip.vue";
import HorizontalRule from "@/Components/HorizontalRule.vue";
import {computed, onMounted, ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {__} from "@/Composables/useTranslations.js";
import {Switch} from "@headlessui/vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    payroll: Object,
    month_stats: Array,
    additions: Object,
    deductions: Object,
    hours: Object,
    income_tax: Number,
    shift_modifier: Number,
    performance_multiplier: Number,
    metrics: Object,
});

const form = useForm({

    // Quick Pay
    quick_pay: ref(false),
    quick_pay_send_email: ref(true),

    // Additions
    rewards: props.additions.rewards,
    incentives: props.additions.incentives,
    reimbursements: props.additions.reimbursements,
    commissions: props.additions.commissions,
    extra_hour_rate: props.additions.extra_hour_rate,

    // Deductions
    social_security_contributions: props.deductions.social_security_contributions,
    health_insurance: props.deductions.health_insurance,
    retirement_plan: props.deductions.retirement_plan,
    benefits: props.deductions.benefits,
    union_fees: props.deductions.union_fees,
    negative_hour_rate: props.deductions.negative_hour_rate,

    // Metric Multiplier
    metricsIDs: props.metrics.map(metric => metric.id),
    metrics: Array(props.metrics.length).fill(1),
    performance_multiplier: ref(props.performance_multiplier),
});

const shift_multiplier = (((props.shift_modifier - 1)) * parseInt(props.payroll.base)).toFixed(2);
const income_tax_calc = ((props.income_tax.income_tax / 100) * props.payroll.base).toFixed(2);
const extraHours = props.hours.hoursDifference > 0 ? props.hours.hoursDifference : 0;
const negativeHours = props.hours.hoursDifference < 0 ? props.hours.hoursDifference : 0;

const recommended_multiplier = computed(() => {
    if (form.quick_pay)
        return 1;

    let total = 0;
    props.metrics.forEach((metric, index) => {
        total += (metric.weight * (form.metrics[index] - 1));
    });
    return (1 + total).toFixed(2);
});

const total_additions = computed(() => {
    return form.quick_pay ? 0 : (
        parseFloat(shift_multiplier) +
        parseFloat((extraHours * form.extra_hour_rate)) +
        parseFloat(form.rewards) + parseFloat(form.incentives) +
        parseFloat(form.reimbursements) +
        parseFloat(form.commissions)
    ).toFixed(2);
});

const total_deductions = computed(() => {
    return form.quick_pay ? 0 : (
        parseFloat(income_tax_calc) +
        parseFloat(form.social_security_contributions) +
        parseFloat(form.health_insurance) +
        parseFloat(form.retirement_plan) +
        parseFloat(form.benefits) +
        parseFloat(form.union_fees) +
        parseFloat(Math.abs(negativeHours) * form.negative_hour_rate)
    ).toFixed(2);
});

onMounted(() => {
    form.performance_multiplier = ref(recommended_multiplier.value);
});

const submit = () => {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            cancelButton: 'mx-4 text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900',
            confirmButton: 'text-white bg-purple-700 hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: __('Are you sure You want to confirm this Payroll?'),
        text: __('Press Cancel to return to payroll reviewing.'),
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: __('Confirm'),
        cancelButtonText: __('Cancel'),
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route('payrolls.update', {id: props.payroll.id}), {
                preserveScroll: true,
                onError: () => {
                    useToast().error(__('Error Reviewing Payroll'));
                },
                onSuccess: () => {
                    Swal.fire(__('Payroll Confirmed!'), '', 'success')
                }
            });
        }
    })
};


</script>
<template>
    <Head :title="__('Payroll Review')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <PayrollTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="card-header">{{ __('Payroll Review') }}</h1>
                </Card>

                <Card>
                    <h1 class="card-header">{{ __('Basic Data') }}</h1>
                    <DescriptionList class="!pb-0">
                        <DescriptionListItem colored>
                            <DT>{{ __('Payroll ID') }}</DT>
                            <DD>{{ payroll.id }}</DD>
                        </DescriptionListItem>
                        <DescriptionListItem colored>
                            <DT>{{ __('Issued for Employee') }}</DT>
                            <Link :href="route('employees.show', {id: payroll.employee.id})">
                                <DD>{{ payroll.employee.name }}</DD>
                            </Link>
                        </DescriptionListItem>
                        <DescriptionListItem>
                            <DT>{{ __('Base Salary') }}</DT>
                            <DD>{{ payroll.currency + ' ' + parseInt(payroll.base).toLocaleString() }}</DD>
                        </DescriptionListItem>
                        <DescriptionListItem>
                            <DT>{{ __('Due Date') }}</DT>
                            <DD>{{ payroll.due_date }}</DD>
                        </DescriptionListItem>
                        <DescriptionListItem colored>
                            <DT>{{ __('Invoice Currency') }}</DT>
                            <DD>{{ payroll.currency }}</DD>
                        </DescriptionListItem>
                        <DescriptionListItem colored></DescriptionListItem>
                    </DescriptionList>
                </Card>

                <Card>
                    <h1 class="card-header">{{ __('Attendance Data') }}</h1>
                    <DescriptionList class="!pb-0">

                        <DescriptionListItem colored>
                            <DT>{{ __('Attendable Days') }}</DT>
                            <DD>{{ month_stats['attendable_days']  + ' ' + __('Day')}}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem colored>
                            <DT>{{ __('Attended') }}</DT>
                            <DD>{{ month_stats['attended']  + ' ' + __('Day')}}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem>
                            <DT>{{ __('Attended Late') }}</DT>
                            <DD>{{ month_stats['absented']  + ' ' + __('Day')}}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem>
                            <DT>{{ __('Absented') }}</DT>
                            <DD>{{ month_stats['late']  + ' ' + __('Day')}}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem colored>
                            <DT>{{ hours.hoursDifference > 0 ? __('Extra Hours') :
                                ( hours.hoursDifference < 0 ?  __('Negative Hours') : __('Hours'))}}</DT>
                            <DD>{{ hours.hoursDifference + ' ' + __('Hour')}}</DD>
                        </DescriptionListItem>
                        <DescriptionListItem colored></DescriptionListItem>

                    </DescriptionList>
                </Card>

                <Card>
                    <h1 class="card-header">{{ __('Quick Pay?') }}</h1>
                    <p class="mt-1">
                        {{__('Use this option if you only want to pay the salary without any additions, deductions or evaluations.')}}</p>
                    <div class="block">
                        <Switch dir="ltr"
                                v-model="form.quick_pay"
                                :class="form.quick_pay ? 'bg-purple-600' : 'bg-gray-400'"
                                class="col-span-4 mt-2 relative inline-flex h-6 w-11 items-center rounded-full">
                                    <span
                                        :class="form.quick_pay ? 'translate-x-6' : 'translate-x-1'"
                                        class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                                    />
                        </Switch>
                    </div>
                    <div v-if="form.quick_pay" class="mt-2">
                        <InputLabel class="mt-2 ltr:mr-2 rtl:ml-2 inline">
                            {{ __('Send Email to employee with this payroll details after confirming this payroll.') }}
                        </InputLabel>
                        <input id="checked-checkbox" type="checkbox" v-model="form.quick_pay_send_email" :checked="form.quick_pay_send_email"
                               class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    </div>
                </Card>
                <div v-if="!form.quick_pay">
                    <Card>
                        <h1 class="card-header">{{ __('Additions') }}
                            <ToolTip>
                                {{ __('The following values are calculated based on the base salary value and currency.')}}
                            </ToolTip>
                        </h1>
                        <InvoiceTable>
                            <template #Head>
                                <InvoiceTableHead>{{ __('Criteria') }}</InvoiceTableHead>
                                <InvoiceTableHead>{{ __('Amount') }}</InvoiceTableHead>
                                <InvoiceTableHead>{{ __('Calculation') }}</InvoiceTableHead>
                                <InvoiceTableHead>{{ __('Total') }}</InvoiceTableHead>
                            </template>

                            <!--Iterate Here-->
                            <template #Body>
                                <TableRow>
                                    <TableBodyHeader>{{ __('Shift Differentials') }}
                                        <ToolTip>
                                            {{ __('This value is obtained from the current employee\'s shift multiplier, which is :SD', {SD: shift_modifier * 100 + '%'}) }}
                                        </ToolTip>
                                    </TableBodyHeader>
                                    <TableBody>{{ (shift_modifier - 1) * 100 + '%' }}</TableBody>
                                    <TableBody>{{ (shift_modifier - 1) * 100 + '% * ' + payroll.base }}</TableBody>
                                    <TableBody>{{ shift_multiplier }}</TableBody>
                                </TableRow>

                                <TableRow :class="{'cursor-not-allowed':extraHours<=0}">
                                    <TableBodyHeader>{{ __('Extra Hours Compensation') }}</TableBodyHeader>
                                    <TableBody v-if="extraHours>0" class="!pl-0">
                                        <TextInput
                                            id="rewards" type="number" class=" mt-1 inline"
                                            v-model="form.extra_hour_rate"
                                            autocomplete="off" step="10" min="0" placeholder="0"
                                            :disabled="extraHours<=0" :class="{'cursor-not-allowed': extraHours<=0}"
                                        />
                                        <span class="inline">{{ __('/Hour') }}</span></TableBody>
                                    <TableBody v-else class="!pl-0">{{ __('There are no extra hours this month.') }}
                                    </TableBody>
                                    <TableBody>{{ extraHours.toFixed(2) }}
                                        {{ __('Hours * :ex_rate /hr', {ex_rate: form.extra_hour_rate}) }}
                                    </TableBody>
                                    <TableBody>{{ (extraHours * form.extra_hour_rate).toFixed(2) }}</TableBody>
                                </TableRow>

                                <TableRow>
                                    <TableBodyHeader>{{ __('Rewards') }}</TableBodyHeader>
                                    <TextInput
                                        id="rewards" type="number" class="w-full mt-1 block" v-model="form.rewards"
                                        autocomplete="off" step="10" min="0" placeholder="0"
                                    />
                                    <TableBody></TableBody>
                                    <TableBody>{{ form.rewards }}</TableBody>
                                </TableRow>
                                <TableRow>
                                    <TableBodyHeader>{{ __('Incentives') }}</TableBodyHeader>
                                    <TextInput
                                        id="rewards" type="number" class="w-full mt-1 block" v-model="form.incentives"
                                        autocomplete="off" step="10" min="0" placeholder="0"
                                    />
                                    <TableBody></TableBody>
                                    <TableBody>{{ form.incentives }}</TableBody>
                                </TableRow>
                                <TableRow>
                                    <TableBodyHeader>{{ __('Reimbursements') }}</TableBodyHeader>
                                    <TextInput
                                        id="rewards" type="number" class="w-full mt-1 block"
                                        v-model="form.reimbursements"
                                        autocomplete="off" step="10" min="0" placeholder="0"
                                    />
                                    <TableBody></TableBody>
                                    <TableBody>{{ form.reimbursements }}</TableBody>
                                </TableRow>
                                <TableRow>
                                    <TableBodyHeader>{{ __('Commissions') }}</TableBodyHeader>
                                    <TextInput
                                        id="rewards" type="number" class="w-full mt-1 block" v-model="form.commissions"
                                        autocomplete="off" step="10" min="0" placeholder="0"
                                    />
                                    <TableBody></TableBody>
                                    <TableBody>{{ form.commissions }}</TableBody>
                                </TableRow>
                                <TableRow>
                                    <TableBodyHeader>{{ __('Total') }}</TableBodyHeader>
                                    <TableBody></TableBody>
                                    <TableBody></TableBody>
                                    <TableBody>
                                        {{total_additions}}
                                    </TableBody>
                                </TableRow>
                            </template>
                        </InvoiceTable>
                    </Card>
                    <Card>
                        <h1 class="card-header">{{ __('Deductions') }}
                            <ToolTip>
                                {{ __('The following values are calculated based on the base salary value and currency.')}}
                            </ToolTip>
                        </h1>
                        <InvoiceTable>
                            <template #Head>
                                <InvoiceTableHead>{{ __('Criteria') }}</InvoiceTableHead>
                                <InvoiceTableHead>{{ __('Amount') }}</InvoiceTableHead>
                                <InvoiceTableHead>{{ __('Calculation') }}</InvoiceTableHead>
                                <InvoiceTableHead>{{ __('Total') }}</InvoiceTableHead>
                            </template>

                            <!--Iterate Here-->
                            <template #Body>
                                <TableRow>
                                    <TableBodyHeader>{{ __('Income Tax') }}
                                        <ToolTip>
                                            {{ __('This Value is obtained from Organization\'s settings.') }}
                                        </ToolTip>
                                    </TableBodyHeader>
                                    <TableBody>{{ income_tax.income_tax + '%' }}</TableBody>
                                    <TableBody>{{ income_tax.income_tax + '% * ' + payroll.base }}</TableBody>
                                    <TableBody>{{ income_tax_calc }}</TableBody>
                                </TableRow>
                                <TableRow :class="{'cursor-not-allowed':negativeHours>=0}">
                                    <TableBodyHeader>{{ __('Negative Hours Penalty') }}</TableBodyHeader>
                                    <TableBody v-if="negativeHours<0" class="!pl-0">
                                        <TextInput
                                            id="rewards" type="number" class=" mt-1 inline"
                                            v-model="form.negative_hour_rate"
                                            autocomplete="off" step="10" min="0" placeholder="0"
                                            :disabled="negativeHours>=0"
                                        />
                                        <span class="inline">{{ __('/Hour') }}</span></TableBody>
                                    <TableBody v-else>{{ __('There are no negative hours this month.') }}</TableBody>
                                    <TableBody>{{ Math.abs(negativeHours).toFixed(2) }}
                                        {{ __('Hours * :ng_rate /hr', {ng_rate: form.negative_hour_rate}) }}
                                    </TableBody>
                                    <TableBody>{{(Math.abs(negativeHours) * form.negative_hour_rate).toFixed(2)}}
                                    </TableBody>
                                </TableRow>
                                <TableRow>
                                    <TableBodyHeader>{{ __('Social Security Contributions') }}</TableBodyHeader>
                                    <TextInput
                                        id="rewards" type="number" class="mt-1 block"
                                        v-model="form.social_security_contributions"
                                        autocomplete="off" step="10" min="0" placeholder="0"
                                    />
                                    <TableBody>-</TableBody>
                                    <TableBody>{{ form.social_security_contributions }}</TableBody>
                                </TableRow>
                                <TableRow>
                                    <TableBodyHeader>{{ __('Health Insurance') }}</TableBodyHeader>
                                    <TextInput
                                        id="rewards" type="number" class="mt-1 block" v-model="form.health_insurance"
                                        autocomplete="off" step="10" min="0" placeholder="0"
                                    />
                                    <TableBody>-</TableBody>
                                    <TableBody>{{ form.health_insurance }}</TableBody>
                                </TableRow>
                                <TableRow>
                                    <TableBodyHeader>{{ __('Retirement Plan') }}</TableBodyHeader>
                                    <TextInput
                                        id="rewards" type="number" class="mt-1 block" v-model="form.retirement_plan"
                                        autocomplete="off" step="10" min="0" placeholder="0"
                                    />
                                    <TableBody>-</TableBody>
                                    <TableBody>{{ form.retirement_plan }}</TableBody>
                                </TableRow>
                                <TableRow>
                                    <TableBodyHeader>{{ __('Benefits') }}</TableBodyHeader>
                                    <TextInput
                                        id="rewards" type="number" class="mt-1 block" v-model="form.benefits"
                                        autocomplete="off" step="10" min="0" placeholder="0"
                                    />
                                    <TableBody>-</TableBody>
                                    <TableBody>{{ form.benefits }}</TableBody>
                                </TableRow>
                                <TableRow>
                                    <TableBodyHeader>{{ __('Union Fees') }}</TableBodyHeader>
                                    <TextInput
                                        id="rewards" type="number" class="mt-1 block" v-model="form.union_fees"
                                        autocomplete="off" step="10" min="0" placeholder="0"
                                    />
                                    <TableBody>-</TableBody>
                                    <TableBody>{{ form.union_fees }}</TableBody>
                                </TableRow>
                                <TableRow>
                                    <TableBodyHeader>{{ __('Total') }}</TableBodyHeader>
                                    <TableBody></TableBody>
                                    <TableBody></TableBody>
                                    <TableBody>{{total_deductions}}
                                    </TableBody>
                                </TableRow>
                            </template>
                        </InvoiceTable>
                    </Card>
                    <Card>
                        <h1 class="card-header !mb-6">{{ __('Employee Evaluation') }}
                            <ToolTip>
                                {{ __('The following matrix will be used to determine the performance multiplier.') }}
                                <br/>
                                {{ __('You can override the multiplier value by entering a value in the input field below.')}}
                            </ToolTip>
                        </h1>
                        <h1>{{ __('Please fill the following evaluation matrix:') }}</h1>
                        <p class="text-xs">
                            {{ __('*Note: Metrics created before the generation of this payroll will not be visible.')}}</p>

                        <div class="mt-4">
                            <div v-for="(metric, index) in metrics" :key="metric.id" :id="'metric-list-'+index"
                                 class="mb-4">
                                <div class="flex justify-between mb-1">
                                    <h2 class="font-bold">{{ metric.criteria }}</h2><span
                                    class="font-thin">({{ __('weight') }}: {{ metric.weight }})</span>
                                </div>
                                <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                        <div class="flex items-center pl-3">
                                            <input :id="'ex-poor-radio-'+index" type="radio"
                                                   v-model="form.metrics[index]"
                                                   :value="1-(parseFloat(metrics[index].step)*3)"
                                                   class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label :for="'ex-poor-radio-'+index"
                                                   class="li-checkbox-label">{{ __('Extremely Poor') }}</label>
                                        </div>
                                    </li>
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                        <div class="flex items-center pl-3">
                                            <input :id="'v-poor-radio-'+index" type="radio"
                                                   v-model="form.metrics[index]"
                                                   :value="1-(parseFloat(metrics[index].step)*2)"
                                                   class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label :for="'v-poor-radio-'+index"
                                                   class="li-checkbox-label">{{ __('Very Poor') }}</label>
                                        </div>
                                    </li>
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                        <div class="flex items-center pl-3">
                                            <input :id="'poor-radio-'+index" type="radio" v-model="form.metrics[index]"
                                                   :value="1-parseFloat(metrics[index].step)"
                                                   class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label :for="'poor-radio-'+index"
                                                   class="li-checkbox-label">{{ __('Poor') }}</label>
                                        </div>
                                    </li>
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                        <div class="flex items-center pl-3">
                                            <input :id="'neutral-radio-'+index" type="radio"
                                                   v-model="form.metrics[index]"
                                                   :value="1"
                                                   class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label :for="'neutral-radio-'+index"
                                                   class="li-checkbox-label">{{ __('Neutral') }}</label>
                                        </div>
                                    </li>
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                        <div class="flex items-center pl-3">
                                            <input :id="'good-radio-'+index" type="radio" v-model="form.metrics[index]"
                                                   :value="1+parseFloat(metrics[index].step)"
                                                   class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label :for="'good-radio-'+index"
                                                   class="li-checkbox-label">{{ __('Good') }}</label>
                                        </div>
                                    </li>
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                        <div class="flex items-center pl-3">
                                            <input :id="'v-good-radio-'+index" type="radio"
                                                   v-model="form.metrics[index]"
                                                   :value="1+(parseFloat(metrics[index].step)*2)"
                                                   class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label :for="'v-good-radio-'+index"
                                                   class="li-checkbox-label">{{ __('Very Good') }}</label>
                                        </div>
                                    </li>
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                        <div class="flex items-center pl-3">
                                            <input :id="'excellent-radio-'+index" type="radio"
                                                   v-model="form.metrics[index]"
                                                   :value="1+(parseFloat(metrics[index].step)*3)"
                                                   class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label :for="'excellent-radio-'+index"
                                                   class="li-checkbox-label">{{ __('Excellent') }}</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <p class="text-xs mt-1">{{ __('Weighted Points') }}: {{ recommended_multiplier }}</p>
                        </div>

                    </Card>
                    <Card>
                        <h1 class="card-header !mb-6">{{ __('Performance Multiplier') }}
                            <ToolTip>
                                {{ __('This value is a percentage that is applied to the base salary. Default value is 1 (no changes).') }}
                            </ToolTip>
                        </h1>
                        <div class="mb-2 "><p class="font-medium inline">
                            {{ __('Based on the evaluation matrix above, the system recommends') }}: </p>
                            <span> {{ recommended_multiplier }}</span> {{ __('as a performance multiplier.') }}
                        </div>
                        <HorizontalRule/>
                        <div class="mb-2 "><p class="font-medium inline">{{ __('Base Salary') }}: </p>
                            <span>{{ payroll.currency + ' ' + parseInt(payroll.base).toLocaleString() }}</span></div>
                        <div class="mb-2 ">
                            <p class="font-medium inline"> {{ __('Recommended Performance Multiplier') }}: </p>
                            <span>{{recommended_multiplier}}</span>
                        </div>
                        <div class="mb-2"><p class="font-medium inline">{{ __('Your Performance Multiplier') }}: </p>
                            <TextInput
                                id="performance_multiplier" type="number" class="ml-4 mt-1 inline !p-1"
                                v-model="form.performance_multiplier"
                                autocomplete="off" step="0.1" min="0" placeholder="1"
                            />
                        </div>
                        <div class="mb-2 ">
                            <p class="font-medium inline">{{ __('Total') }}: </p>
                            <span>{{ (payroll.base * form.performance_multiplier).toLocaleString() }}</span>
                        </div>
                    </Card>
                </div>
                <Card>
                    <h1 class="card-header">{{ __('Grand Total') }}</h1>
                    <div class="mb-2 ">
                        <p class="font-medium inline">{{ __('Formula') }}: </p>
                        <span> {{ __('(Base Salary * Performance Multiplier) + Additions - Deductions') }} </span>
                    </div>
                    <div class="mb-2 ">
                        <p class="font-medium inline">{{ __('Calculation') }}:</p>
                        <span> ({{ payroll.base }} * {{ form.performance_multiplier }}) + {{ total_additions }} - {{ total_deductions }} </span>
                    </div>
                    <HorizontalRule/>
                    <div class="!my-4 text-purple-700 dark:text-purple-500 !border-red-500"><p
                        class="font-medium inline">{{ __('Grand Total') }}: </p>
                        <span>
                            {{ payroll.currency + ' ' + (parseFloat(payroll.base) * parseFloat(form.performance_multiplier) +
                            parseFloat(total_additions) - parseFloat(total_deductions)).toLocaleString() }}
                        </span>
                    </div>
                    <div class="flex justify-end">
                        <form @submit.prevent="submit">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }"
                                           :disabled="form.processing">
                                {{ __('Confirm Payroll') }}
                            </PrimaryButton>
                        </form>
                    </div>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
