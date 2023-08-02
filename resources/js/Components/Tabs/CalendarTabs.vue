<script setup>
import NavLink from "@/Components/NavLink.vue";
import GoBackNavLink from "@/Components/GoBackNavLink.vue";
</script>

<template>
    <GoBackNavLink/>

    <div v-if="$page.props.auth.user.roles.includes('admin')" class="space-x-8 rtl:space-x-reverse sm:flex">
        <NavLink :href="route('calendar.index')" :active="route().current('calendar.index')">
            {{__('Calendar')}}
        </NavLink>
        <NavLink :href="route('calendars.index')" :active="route().current('calendars.index')">
            {{__('Calendar Items')}}
        </NavLink>
        <NavLink :href="route('calendars.create')" :active="route().current('calendars.create')">
            {{__('Add a Calendar Item')}}
        </NavLink>
    </div>
    <div v-else class="space-x-8 sm:flex">
        <NavLink :href="route('calendar.index')" active>
            {{__('My Calendar')}}
        </NavLink>
    </div>

</template>

