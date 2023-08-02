<script setup>
import NavLink from "@/Components/NavLink.vue";
import GoBackNavLink from "@/Components/GoBackNavLink.vue";
</script>

<template>
    <GoBackNavLink/>

    <NavLink :href="route('attendance.dashboard')" :active="route().current('attendance.dashboard')">
        {{__('Dashboard')}}
    </NavLink>

    <div v-if="$page.props.auth.user.roles.includes('admin')" class="space-x-8 rtl:space-x-reverse sm:flex">
        <NavLink :href="route('attendances.index')" :active="route().current('attendances.index') || route().current('attendance.show')">
            {{__('Attendance List')}}
        </NavLink>
        <NavLink :href="route('attendances.create')" :active="route().current('attendances.create')">
            {{__('Take/Edit Attendance')}}
        </NavLink>
    </div>

</template>

