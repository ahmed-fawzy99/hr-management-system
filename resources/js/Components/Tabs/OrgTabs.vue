<script setup>
import NavLink from "@/Components/NavLink.vue";
import GoBackNavLink from "@/Components/GoBackNavLink.vue";
</script>

<template>
    <GoBackNavLink/>

    <NavLink :href="route('branches.index')" :active="route().current('branches.index') || route().current('branches.edit') || route().current('branches.show') || route().current('branches.create') ">
        {{__('Branches')}}
    </NavLink>
    <NavLink :href="route('departments.index')" :active="route().current('departments.index') || route().current('departments.edit') || route().current('departments.show') ||route().current('departments.create')">
        {{__('Departments')}}
    </NavLink>
    <NavLink :href="route('positions.index')" :active="route().current('positions.index') || route().current('positions.edit') || route().current('positions.show') ||route().current('positions.create')">
        {{__('Positions')}}
    </NavLink>
    <NavLink :href="route('shifts.index')" :active="route().current('shifts.index') || route().current('shifts.edit') || route().current('shifts.show') ||route().current('shifts.create')">
        {{__('Shifts')}}
    </NavLink>
    <NavLink :href="route('metrics.index')" :active="route().current('metrics.index') || route().current('metrics.edit') || route().current('metrics.show') ||route().current('metrics.create')">
        {{__('Metrics')}}
    </NavLink>
    <NavLink :href="route('globals.index')" :active="route().current('globals.index') || route().current('globals.edit')">
        {{__('Globals')}}
    </NavLink>
    <NavLink :href="route('logs.index')" :active="route().current('logs.index')">
        {{__('Logs')}}
    </NavLink>

</template>

