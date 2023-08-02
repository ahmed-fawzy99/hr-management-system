<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {computed, onMounted} from "vue";
import EmployeeTabs from "@/Components/Tabs/EmployeeTabs.vue";
import FlexButton from "@/Components/FlexButton.vue";
import {useExtractPersonalDetails} from "@/Composables/useExtractPersonalDetails.js";
import HistoryDescriptionList from "@/Components/DescriptionList/HistoryDescriptionList.vue";
import {initModals} from "flowbite";
import {useAgeCalculator} from "@/Composables/useAgeCalculator.js";
import Card from "@/Components/Card.vue";
import ModifyIcon from "@/Components/Icons/ModifyIcon.vue";
import DescriptionList from "@/Components/DescriptionList/DescriptionList.vue";
import DT from "@/Components/DescriptionList/DT.vue";
import DD from "@/Components/DescriptionList/DD.vue";
import DescriptionListItem from "@/Components/DescriptionList/DescriptionListItem.vue";
import GenericModal from "@/Components/GenericModal.vue";
import Table from "@/Components/Table/Table.vue";
import TableBody from "@/Components/Table/TableBody.vue";
import TableHead from "@/Components/Table/TableHead.vue";
import TableRow from "@/Components/Table/TableRow.vue";
import ToolTip from "@/Components/ToolTip.vue";
import {__} from "@/Composables/useTranslations.js";


let {extractPersonalDetails} = useExtractPersonalDetails()

onMounted(() => {
    initModals();
});

const props = defineProps({
    employee: Object,
})

const computedManages = computed(() => {
    const filteredArray = props.employee.manages
        .map(({department_id, branch_id}) => ({department_id, branch_id})) // Extract properties
        .filter(({department_id, branch_id}) => department_id !== null || branch_id !== null); // Ignore null values

    const branches = filteredArray
        .map(({branch_id}) => branch_id)
        .filter(Boolean)
        .join(", ");

    const departments = filteredArray
        .map(({department_id}) => department_id)
        .filter(Boolean)
        .join(", ");

    let result = '';
    if (branches !== '') {
        result += __('Branches') + `: #${branches}`;
    }
    if (departments !== '') {
        if (result !== '') {
            result += ' - ';
        }
        result += __('Departments') + `: #${departments}`;
    }

    return result;
});
</script>

<template>
    <Head :title="__('Employee View')"/>
    <AuthenticatedLayout>
        <template #tabs>
            <EmployeeTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="card-header">{{__('Employee View :ifAdmin', {ifAdmin: props.employee.manages.length > 0 ? '(' + __('Manager') + ')' : ''})}} </h1>
                        <div class="flex inline-flex gap-4">
                            <FlexButton v-if="$page.props.auth.user.roles.includes('admin')"
                                        :text="__('Modify Employee Data')" :href="route('employees.edit', {id: employee.id})">
                                <ModifyIcon/>
                            </FlexButton>
                            <FlexButton v-else :text="__('Modify Data')"
                                        :href="route('profile.edit', {id: employee.id})">
                                <ModifyIcon/>
                            </FlexButton>
                        </div>
                    </div>

                    <h2 class="card-subheader">{{__('Basic Info')}}</h2>
                    <DescriptionList>
                        <DescriptionListItem colored>
                            <DT>{{__('Name')}}</DT>
                            <DD>{{ employee.name }}</DD>
                        </DescriptionListItem>
                        <DescriptionListItem colored>
                            <DT>{{__('ID')}}</DT>
                            <DD>{{ employee.id }}</DD>
                        </DescriptionListItem>
                        <DescriptionListItem>
                            <DT>{{__('Phone')}}</DT>
                            <DD><a :href="'tel:' + employee.phone">{{ employee.phone }}</a></DD>
                        </DescriptionListItem>
                        <DescriptionListItem>
                            <DT>{{__('National ID')}}</DT>
                            <DD>{{ employee.national_id }}</DD>
                        </DescriptionListItem>
                        <DescriptionListItem colored>
                            <DT>{{__('Email')}}</DT>
                            <DD><a :href="'mailto:' + employee.email">{{ employee.email }}</a></DD>
                        </DescriptionListItem>

                        <DescriptionListItem colored>
                            <DT>{{__('Gender')}}</DT>
                            <DD>{{ extractPersonalDetails(employee.national_id).isMale ? __('Male') : __('Female') }}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem >
                            <DT>{{__('Bank Account Details')}}</DT>
                            <DD>{{ employee.bank_acc_no ?? __('N/A') }}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem>
                            <DT>{{__('Birthday')}}</DT>
                            <DD>{{
                                extractPersonalDetails(employee.national_id).date_localized +
                                ' - ' + useAgeCalculator(extractPersonalDetails(employee.national_id).date) + ' ' + __('Years')
                              }}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem colored>
                            <DT>{{__('Hire Date')}}</DT>
                            <DD>{{ employee.hired_on }}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem colored>
                            <DT>{{__('Remote Worker?')}}</DT>
                            <DD>{{ props.employee.is_remote ? __('Yes') : __('No') }}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem >
                            <DT>{{__('Address')}}</DT>
                            <DD><a :href="'https://www.google.com/maps/search/?api=1&query=' + props.employee.address" target=”_blank” >{{ employee.address }}</a></DD>

                        </DescriptionListItem>
                    </DescriptionList>
                </Card>
                <Card>
                    <h2 class="mb-2 ml-1 font-semibold">{{__('Technical Info')}}</h2>
                    <DescriptionList>
                        <DescriptionListItem colored>
                            <DT>{{__('Branch')}}</DT>
                            <DD>{{ employee.branch_name ?? __('N/A')}}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem colored>
                            <DT>{{__('Department')}}</DT>
                            <DD>{{ employee.department_name ?? __('N/A') }}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem>
                            <DT>{{__('Salary')}}</DT>
                            <DD>{{ employee.salaries[employee.salaries.length - 1]['salary'].toLocaleString() + ' ' + employee.salaries[employee.salaries.length - 1]['currency'] }}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem>
                            <DT>{{__('Position')}}</DT>
                            <DD>{{ (employee.employee_positions.length === 0) ? __('N/A') : employee.employee_positions[employee.employee_positions.length - 1]['position']?.['name'] ?? __('N/A') }}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem colored>
                            <DT>{{__('Shift')}}</DT>
                            <DD>{{ (employee.employee_shifts.length === 0) ? __('N/A') : employee.employee_shifts.filter(shift => shift.end_date === null).map(shift => shift.shift?.name)[0] ?? __('N/A') }}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem colored>
                            <DT>{{__('Access Permissions')}}</DT>
                            <DD>{{ (employee.roles.length === 0) ? __('Not Assigned') : employee.roles[employee.roles.length - 1]['name'].replace(/_/g, ' ').replace(/\b\w/g, (match) => match.toUpperCase()) }}</DD>
                        </DescriptionListItem>

                        <DescriptionListItem >
                            <DT>
                                {{__('Manages')}}
                                <ToolTip direction="top">{{__('IDs of the branches and/or departments that this employee manages, if any.')}}</ToolTip></DT>
                            <DD>{{ props.employee.manages.length > 0 ? computedManages : __('Nothing') }}</DD>
                        </DescriptionListItem>
                    </DescriptionList>
                </Card>
                <Card>
                    <h2 class="mb-2 ml-1 font-semibold">{{__('History')}}</h2>

                    <HistoryDescriptionList>
                        <div class="px-4 py-3.5">
                            <dt class="text-sm font-medium">{{__('Previous Salaries')}}</dt>

                            <GenericModal modalId='Salaries Modal'
                                          :title="__('Click Here To See Salary History')" :modalHeader="__('Previous Salaries')"
                                          :hasCancel="false" >

                                <Table :totalNumber="1" :enablePaginator="false">
                                    <p class="test">{{employee.salaries}}</p>
                                    <template #Head>
                                        <TableHead>{{__('Currency')}}</TableHead>
                                        <TableHead>{{__('Salary')}}</TableHead>
                                        <TableHead>{{__('Starting From')}}</TableHead>
                                        <TableHead>{{__('Ending At')}}</TableHead>
                                    </template>

                                    <!--Iterate Here-->
                                    <template #Body>
                                        <TableRow v-for="salary in employee.salaries" :key="salary.id">
                                            <TableBody>{{salary.currency}}</TableBody>
                                            <TableBody>{{salary.salary}}</TableBody>
                                            <TableBody>{{salary.start_date}}</TableBody>
                                            <TableBody>{{salary.end_date ?? __('Current')}}</TableBody>
                                        </TableRow>
                                    </template>
                                </Table>
                            </GenericModal>
                        </div>

                        <div class="px-4 py-3.5">
                            <dt class="text-sm font-medium">{{__('Previous Positions')}}</dt>

                            <GenericModal modalId='Positions Modal'
                                          :title="__('Click Here To See Positions History')" :modalHeader="__('Previous Positions')"
                                          :hasCancel="false" >

                                <Table :totalNumber="1" :enablePaginator="false">
                                    <template #Head>
                                        <TableHead>{{__('Position')}}</TableHead>
                                        <TableHead>{{__('Starting From')}}</TableHead>
                                        <TableHead>{{__('Ending At')}}</TableHead>
                                    </template>

                                    <!--Iterate Here-->
                                    <template #Body>
                                        <TableRow v-for="position in employee.employee_positions" :key="position.id">
                                            <TableBody>{{position.position?.name ?? __('DELETED POSITION')}}</TableBody>
                                            <TableBody>{{position.start_date}}</TableBody>
                                            <TableBody>{{position.end_date ?? __('Current')}}</TableBody>
                                        </TableRow>
                                    </template>
                                </Table>
                            </GenericModal>
                        </div>

                        <div class="px-4 py-3.5">
                            <dt class="text-sm font-medium">{{__('Previous Shifts')}}</dt>

                            <GenericModal modalId='Shifts Modal'
                                          :title="__('Click Here To See Shifts History')" :modalHeader="__('Previous Shifts')"
                                          :hasCancel="false" >

                                <Table :totalNumber="1" :enablePaginator="false">
                                    <template #Head>
                                        <TableHead>{{__('Shift')}}</TableHead>
                                        <TableHead>{{__('Starting From')}}</TableHead>
                                        <TableHead>{{__('Ending At')}}</TableHead>
                                    </template>

                                    <!--Iterate Here-->
                                    <template #Body>
                                        <TableRow v-for="shift in employee.employee_shifts" :key="shift.id">
                                            <TableBody>{{shift.shift?.name ?? __('DELETED SHIFT')}}</TableBody>
                                            <TableBody>{{shift.start_date}}</TableBody>
                                            <TableBody>{{shift.end_date ?? __('Current')}}</TableBody>
                                        </TableRow>
                                    </template>
                                </Table>
                            </GenericModal>
                        </div>

                    </HistoryDescriptionList>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
