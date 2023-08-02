<script setup>
import NavLink from "@/Components/NavLink.vue";
import GoBackNavLink from "@/Components/GoBackNavLink.vue";

function back(){
    window.history.back();
}
</script>

<template>
    <GoBackNavLink/>

    <div v-if="$page.props.auth.user.roles.includes('admin')" class="space-x-8 rtl:space-x-reverse sm:flex">
        <NavLink :href="route('payrolls.index')" :active="route().current('payrolls.index')">
            {{__('Payrolls')}}
        </NavLink>
    </div>
    <div v-else class="space-x-8 sm:flex">
        <NavLink :href="route('payrolls.index')" active>
            {{__('My Payrolls')}}
        </NavLink>
    </div>

</template>

