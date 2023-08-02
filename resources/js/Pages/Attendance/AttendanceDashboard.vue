<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link} from '@inertiajs/vue3';
import {computed, onMounted} from "vue";
import {initTooltips} from "flowbite";
import AttendanceTabs from "@/Components/Tabs/AttendanceTabs.vue";
import ToolTip from "@/Components/ToolTip.vue";
import Card from "@/Components/Card.vue";

onMounted(() => {
    initTooltips();
});

const props = defineProps({
    EmployeeStats: Object,
});

const workableThisYear = computed(() => {
    return props.EmployeeStats['YearStats']['workingDaysThisYear'] - props.EmployeeStats['YearStats']['WeekendOffDaysThisYear'] - props.EmployeeStats['YearStats']['HolidaysThisYear'];
});

const attendancePercentage = computed(() => {
    return (props.EmployeeStats['totalAttendanceSoFar'] / (workableThisYear.value) * 100).toFixed(0);
});

const absencePercentage = computed(() => {
    return (props.EmployeeStats['totalAbsenceSoFar'] / props.EmployeeStats['YearStats']['absence_limit'] * 100).toFixed(0);
});
</script>
<template>
    <Head :title="__('Attendance Dashboard') "/>
    <AuthenticatedLayout>
        <template #tabs>
            <AttendanceTabs/>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="!mt-0">
                    <h1 class="text-2xl">{{__('Attendance Dashboard')}}</h1>
                </Card>

                <Card>
                    <h1 class="text-2xl mb-6">{{__('Your Attendance This Month (:attendance)', {attendance: new Date().toLocaleString('default', { month: 'long' })})}}</h1>

                    <!-- Attendance -->
                    <div class="mt-4">
                        <h2 class="font-bold mb-1">{{__('Attendance')}}</h2>
                        <p class="mb-2">{{__('Attended')}} {{ EmployeeStats['attendedThisMonth'] }} {{__('of')}} {{ EmployeeStats['attendableThisMonth'] }} {{__('Days')}}.</p>
                    </div>

                    <hr class="my-3 h-0.5 border-t-0 bg-neutral-100 opacity-100 dark:opacity-50"/>

                    <!-- Absence -->
                    <div class="mt-4">
                        <h2 class="font-bold mb-1 mt-4">{{__('Absence')}}</h2>
                        <p class="mb-2">{{__('Absented')}} {{ EmployeeStats['absentedThisMonth'] }} {{__('Days')}}.</p>
                    </div>

                    <hr class="my-3 h-0.5 border-t-0 bg-neutral-100 opacity-100 dark:opacity-50"/>

                    <!-- Hours -->
                    <div class="mt-4">
                        <h2 class="inline font-bold mt-8">{{__('Hour Compensation')}}</h2>
                        <ToolTip>
                            <div>{{__('Compensation Hours are the hours you have worked more/less than the required hours')}}.</div>
                            <div>{{__('Extra Hours are rewarded, while negative hours will result in deduction..')}}</div>
                            <div>{{__('The final numbers are cleared and accounted in the payroll by the end of the month.')}}</div>
                        </ToolTip>

                        <p class="mb-2 mt-1">{{__('Worked :worked of :of Hours', {worked: EmployeeStats['actualHoursThisMonth'].toFixed(2), of: EmployeeStats['expectedHoursThisMonth']})}}</p>

                        <p class="mb-2 mt-1">{{__('Progress So Far: :hoursDifference Hours', {hoursDifference: EmployeeStats['hoursDifferenceSoFar'].toFixed(2)})}}</p>

                        <p class="mb-2 mt-1">{{__('Remaining Until Month\'s End')}}: <span v-if="EmployeeStats['hoursDifference'] > 0">+</span> {{ EmployeeStats['hoursDifference'].toFixed(2)}} {{__('Hours')}}.</p>
                    </div>
                </Card>

                <Card>
                    <h1 class="text-2xl mb-6">{{__('Your Attendance This Year')}}</h1>

                    <!-- Attendance -->
                    <div class="mt-4">
                        <h2 class="font-bold mb-1">{{__('Attendance')}}</h2>
                        <p class="mb-2">{{__('Attended :attended from :from', {attended: EmployeeStats['totalAttendanceSoFar'], from: workableThisYear})}}</p>
                        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                            <div
                                class="bg-green-500 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                :style="'width:'+(attendancePercentage > 100 ? 100 : attendancePercentage)+'%'"> {{ attendancePercentage+'%' }}
                            </div>
                        </div>
                    </div>

                    <!-- Absence -->
                    <div class="mt-4">
                        <h2 class="font-bold mb-1 mt-4">{{__('Absence')}}</h2>
                        <p class="mb-2">{{__('Absented :absented of :of', {absented: EmployeeStats['totalAbsenceSoFar'], of: EmployeeStats['YearStats']['absence_limit'] })}} </p>
                        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                            <div
                                class="bg-red-500 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                :style="'width:'+ (absencePercentage > 100 ? 100 : absencePercentage) + '%'"> {{ absencePercentage+'%' }}
                            </div>
                        </div>
                    </div>

                    <hr class="my-6 h-0.5 border-t-0 bg-neutral-100 opacity-100 dark:opacity-50"/>

                    <!-- Days Breakdown -->
                    <div class="mt-4">
                        <h2 class="font-bold mb-1 mt-4">{{__('Your Year\'s Data')}}</h2>
                        <div class="block mt-4">
                            <span class="inline">{{__('Weekend Off Days')}}: </span>
                            <p class="mb-2 inline">{{ EmployeeStats['YearStats']['weekendOffDays'].map(day => {return day.charAt(0).toUpperCase() + day.slice(1);}).join(', ') }}.</p>
                        </div>
                        <div class="block mt-2">
                            <span class="inline">{{__('Your Total Weekend Days Off This Year')}}: </span>
                            <p class="mb-2 inline">{{ EmployeeStats['YearStats']['WeekendOffDaysThisYear'] }} {{__('Days')}}.</p>
                        </div>
                        <div class="block mt-2">
                            <span class="inline">{{__('Your Total National Holidays Days')}}: </span>
                            <p class="mb-2 inline">{{ EmployeeStats['YearStats']['HolidaysThisYear']}} {{__('Days')}}.</p>
                        </div>
                        <div class="block mt-2">
                            <span class="inline">{{__('Your Total Off Days This Year (Weekends + Holidays)')}}: </span>
                            <p class="mb-2 inline">{{ EmployeeStats['YearStats']['WeekendOffDaysThisYear'] + EmployeeStats['YearStats']['HolidaysThisYear']}} {{__('Days')}}.</p>
                        </div>
                    </div>

                    <hr class="my-6 h-0.5 border-t-0 bg-neutral-100 opacity-100 dark:opacity-50"/>

                    <!-- Support -->

                    <div class="mt-5">
                        <p class="font-bold mb-2">{{__('Something\'s Wrong?')}}</p>
                        <p>{{__('If there is anything wrong in the presented data above please')}}
                            <Link :href="route('requests.create')" class="underline text-purple-700" >{{__('Submit a Complaint Here')}}</Link>.
                        </p>
                    </div>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
