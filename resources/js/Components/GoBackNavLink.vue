<script setup>
import NavLink from "@/Components/NavLink.vue";
import LeftChevron from "@/Components/Icons/LeftChevron.vue";
import RightChevron from "@/Components/Icons/RightChevron.vue";

function back(){
    window.history.back();
}
const isLtr = document.dir === 'ltr'
</script>

<template>
  <NavLink href="#" @click="back" class="border-b-0" Preserve-Scroll>
      <LeftChevron class="mr-2" v-if="isLtr"/>
      <RightChevron class="ml-2" v-else/>
      {{__('Go Back')}}
  </NavLink>
</template>
