<script setup>
import {ref} from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import SidebarListItem from "@/Components/SidebarListItem.vue";
import {useDark, useToggle} from "@vueuse/core";
import EmployeeIcon from "@/Components/Icons/UsersIcon.vue";
import OrganizationIcon from "@/Components/Icons/OrganizationIcon.vue";
import MessageIcon from "@/Components/Icons/MessageIcon.vue";
import CalendarIcon from "@/Components/Icons/CalendarIcon.vue";
import TableIcon from "@/Components/Icons/TableIcon.vue";
import MoneyIcon from "@/Components/Icons/MoneyIcon.vue";
import RocketIcon from "@/Components/Icons/RocketIcon.vue";
import UserIcon from "@/Components/Icons/UserIcon.vue";
import "/node_modules/flag-icons/css/flag-icons.min.css";
import {router} from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);
const isDark = useDark();
const toggleDark = useToggle(isDark);

const locales = {
    // LOCALE: [Full Name for Front-End in Native Language, Country Flag Code],
    en: ['English','us'],
    ar: ['العربية', 'arab'],
};

function changeLanguage(locale){

    router.visit(route('language', {language: locale}),
        {
            onSuccess: () => {
                window.history.go(0); // Sorry SPA Lords, Have to do a full refresh here.
            },
        });
}


</script>

<template>


    <aside id="separator-sidebar"
           class="fixed top-0 z-40 w-64 h-screen transition-transform ltr:-translate-x-full  ltr:sm:translate-x-0
                                                                      rtl:translate-x-full rtl:sm:-translate-x-0"
           :class="$page.props.locale == 'ar' ? 'right-0' : 'left-0'"
           aria-label="Sidebar">
        <div
            class="h-full px-3 py-4 overflow-y-auto border-r flex flex-col justify-between dark:bg-gray-800 dark:border-gray-800">
            <ul v-if="$page.props.auth.user.roles.includes('admin')" class="space-y-2 font-medium mb-4">
                <div class="flex flex-row items-center">
                    <div class="bg-purple-500 h-px flex-grow"></div>
                    <div class=" px-2">{{__('Admin Tools')}}</div>
                    <div class="bg-purple-500 h-px flex-grow"></div>
                </div>

                <SidebarListItem :item-name="__('My Dashboard')" :hasBadge="false" link="dashboard.index"
                                 :active-links="['dashboard.index']">
                    <RocketIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>

                <SidebarListItem :item-name="__('Employees')" :hasBadge="true" badge="number"
                                 :badge-content="$page.props.ui.empCount.toString() ?? '?'"
                                 link="employees.index"
                                 :active-links="['employees.index', 'employees.create', 'employees.show',
                                 'employees.find', 'employees.edit', 'employees.archived']"
                >
                    <EmployeeIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>

                <SidebarListItem :item-name="__('Organization')"
                                 :active-links-recursive="['branches', 'departments', 'positions', 'shifts', 'globals', 'metrics', 'logs']"
                                 badge-content="0" link="branches.index">
                    <OrganizationIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>

                <SidebarListItem :item-name="__('Requests')" :hasBadge="($page.props.ui.reqCount.toString() !== '0')"
                                 badge="number" :badge-content="$page.props.ui.reqCount.toString() ?? '?'"
                                 link="requests.index" :active-links="['requests.index', 'requests.create',
                 'requests.show', 'requests.edit']">
                    <MessageIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>

                <SidebarListItem :item-name="__('Calendar')" link="calendar.index"
                                 :activeLinks="['calendar.index', 'calendars.index', 'calendars.create',
                 'calendars.show', 'calendars.edit']">
                    <CalendarIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>

                <SidebarListItem :item-name="__('Attendance')" link="attendances.index"
                                 :activeLinks="['attendance.dashboard', 'attendance.show', 'attendances.index', 'attendances.create']">
                    <TableIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>

                <SidebarListItem :item-name="__('Payrolls')" link="payrolls.index"
                                 :activeLinks="['payrolls.index', 'payrolls.show', 'payrolls.edit']"
                >
                    <MoneyIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>

            </ul>

            <ul v-else class="space-y-2 font-medium mb-4">
                <div class="flex flex-row items-center">
                    <div class="bg-purple-500 h-px flex-grow"></div>
                    <div class=" px-2">{{__('My Services')}}</div>
                    <div class="bg-purple-500 h-px flex-grow"></div>
                </div>
                <SidebarListItem :item-name="__('My Dashboard')" :hasBadge="false" link="dashboard.index"
                                 :active-links="['dashboard.index']">
                    <RocketIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>

                <SidebarListItem :item-name="__('My Profile')" :hasBadge="false"
                                 link="my-profile"
                                 :active-links="['my-profile']"
                >
                    <UserIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>

                <SidebarListItem :item-name="__('My Requests')" :hasBadge="($page.props.ui.reqCount.toString() !== '0')"
                                 badge="number" :badge-content="$page.props.ui.reqCount.toString() ?? '?'"
                                 link="requests.index"
                                 :active-links="['requests.index', 'requests.show', 'requests.create']"
                >
                    <MessageIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>

                <SidebarListItem :item-name="__('My Payrolls')"
                                 link="payrolls.index" :active-links="['payrolls.index', 'payrolls.show']">
                    <MoneyIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>

                <SidebarListItem :item-name="__('My Calendar')" link="calendar.index" :active-links="['calendar.index']">
                    <CalendarIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>

                <SidebarListItem :item-name="__('My Attendance')" link="attendance.dashboard"
                                 :active-links="['attendance.dashboard']">
                    <TableIcon class="text-gray-500 dark:text-gray-100"/>
                </SidebarListItem>
            </ul>
        </div>
    </aside>

    <div :class="$page.props.locale === 'ar' ? 'sm:mr-64' : 'sm:ml-64'">
        <div>
            <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
                <nav class=" border-b border-gray-300 dark:border-gray-600">

                    <!-- Primary Navigation Menu -->
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                        <div class="flex justify-between h-16">
                            <div class="flex">
                                <div class="block my-auto space-x-8 rtl:space-x-reverse sm:-my-px sm:flex">
                                    <slot name="tabs"></slot>
                                </div>
                            </div>

                            <div class="hidden sm:flex sm:items-center sm:ml-6">
                                <!-- Dark mode Switcher & Settings Dropdown -->
                                <button
                                    @click="toggleDark()"
                                    type="button"
                                    class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700
                                    focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700
                                    rounded-lg text-sm p-2.5"
                                >
                                    <svg
                                        id="theme-toggle-dark-icon"
                                        class="w-5 h-5"
                                        :class="isDark ? 'block' : 'hidden'"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"
                                        ></path>
                                    </svg>
                                    <svg
                                        id="theme-toggle-light-icon"
                                        class="w-5 h-5"
                                        :class="isDark ? 'hidden' : 'block'"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                </button>
                                <div class="ml-3 relative !flex">
                                    <Dropdown align="right" width="48">
                                        <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                <span :class="'fi fi-' + locales[$page.props.locale][1] + ' mx-2'"></span>
                                                {{ locales[$page.props.locale][0] }}
                                            </button>
                                        </span>
                                        </template>

                                        <template #content>
                                            <DropdownLink v-for="locale in Object.keys(locales).filter((locale) => locale !== $page.props.locale)"
                                                          @click="changeLanguage(locale)">
                                                <span :class="'fi fi-' + locales[locale][1] + ' mx-2'"> </span>
                                                {{ locales[locale][0] }}
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                                <div class="ml-3 relative">
                                    <Dropdown align="right" width="48">
                                        <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}
                                                <svg
                                                    class="mx-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                        </template>

                                        <template #content>
                                            <DropdownLink :href="route('profile.edit')">
                                                {{__('Profile')}}
                                            </DropdownLink>
                                            <DropdownLink :href="route('logout')" method="post" as="button">
                                                {{__('Log Out')}}
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>

                            <!-- Hamburger -->
                            <div class="-mr-2 flex items-center sm:hidden">
                                <button
                                    @click="showingNavigationDropdown = !showingNavigationDropdown"
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400
                                    hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100
                                    focus:text-gray-500 transition duration-150 ease-in-out"
                                >
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path
                                            :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Navigation Menu -->
                    <div
                        :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                        class="sm:hidden"
                    >

                        <Dropdown align="right" width="48">
                            <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                <span :class="'fi fi-' + locales[$page.props.locale][1] + ' mx-2'"></span>
                                                {{ locales[$page.props.locale][0] }}
                                            </button>
                                        </span>
                            </template>

                            <template #content>
                                <DropdownLink v-for="locale in Object.keys(locales).filter((locale) => locale !== $page.props.locale)"
                                              @click="changeLanguage(locale)">
                                    <span :class="'fi fi-' + locales[locale][1] + ' mx-2'"> </span>
                                    {{ locales[locale][0] }}
                                </DropdownLink>
                            </template>
                        </Dropdown>

                        <div class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink :href="route('dashboard.index')"
                                               :active="route().current('dashboard.index')">
                                Dashboard
                            </ResponsiveNavLink>

                            <ResponsiveNavLink :href="route('employees.index')">Employees</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('branches.index')">Organization</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('requests.index')">Requests</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('calendar.index')">Calendar</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('attendances.index')">Attendance</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('payrolls.index')">Payrolls</ResponsiveNavLink>
                        </div>

                        <!-- Responsive Settings Options -->
                        <div class="pt-4 pb-1 border-t border-gray-200">
                            <div class="px-4">
                                <div class="font-medium text-base text-gray-800">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('profile.edit')"> Profile</ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <main class="mx-4 md:mx-0">
                    <slot/>
                </main>
            </div>
        </div>
    </div>

</template>

<style>

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

</style>

