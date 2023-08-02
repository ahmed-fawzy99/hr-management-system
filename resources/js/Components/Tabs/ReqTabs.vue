<script setup>
import NavLink from "@/Components/NavLink.vue";
import GoBackNavLink from "@/Components/GoBackNavLink.vue";
</script>

<template>
    <GoBackNavLink/>

    <div v-if="$page.props.auth.user.roles.includes('admin')" class="space-x-8 rtl:space-x-reverse sm:flex">
        <NavLink :href="route('requests.index')" :active="route().current('requests.index')">
            {{__('All Requests')}}
        </NavLink>
        <NavLink :href="route('requests.create')" :active="route().current('requests.create')">
            {{__('Create a Request')}}
        </NavLink>
    </div>

    <div v-else class="space-x-8 sm:flex">
        <NavLink :href="route('requests.index')" :active="route().current('requests.index') || route().current('requests.show') ">
            {{__('My Requests')}}
        </NavLink>
        <NavLink :href="route('requests.create')" :active="route().current('requests.create')">
            {{__('Create a Request')}}
        </NavLink>
    </div>


</template>

