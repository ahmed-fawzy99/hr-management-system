<script setup>

</script>

<template>
    <div class="relative overflow-x-auto mt-4">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

            <!-- HEADER -->
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <slot name="Head"></slot>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody>
                <slot name="Body"></slot>
            </tbody>
        </table>
    </div>

</template>

