<script setup>
import {useToast} from "vue-toastification";

const props = defineProps({
    type: { //default, success, error, warning, info
        type: String,
        default: "default"
    },
    message: {
        type: String,
        default: "Default Toast Message"
    },
    timeout: {
        type: Number,
        default: 3000
    },
})

const toast = useToast();

if (props.type === "success") {
    toast.success(props.message, { timeout: props.timeout });
} else if (props.type === "error") {
    toast.error(props.message, { timeout: props.timeout });
} else if (props.type === "warning") {
    toast.warning(props.message, { timeout: props.timeout });
} else {
    toast(props.message, { timeout: props.timeout });
}

function test() {
    toast.error('asdasdasda');
}

</script>

<template>

</template>

<style scoped>

</style>
