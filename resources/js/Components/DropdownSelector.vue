<template>
    <div class="relative inline-block text-left ">
        <button
            type="button"
            class="py-2 px-4 border dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700
                    bg-white dark:bg-gray-700 dark:text-white dark:hover:bg-gray-700 focus:outline-none w-full
                    focus:ring-2 focus:ring-purple-500"
            @click="toggleListbox"
        >
            {{ selectedOption.label }}
        </button>

        <transition
            enter-active-class="transition ease-out duration-100"
            enter-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <ul
                v-if="isOpen"
                class="absolute z-10 mt-2 w-96 rounded-md bg-white shadow-lg dark:bg-gray-800 origin-top-left focus:outline-none "
                @click="isOpen = false"
            >
                <li
                    v-for="option in options"
                    :key="option.value"
                    @click="selectOption(option)"
                    class="cursor-pointer select-none relative px-4 py-2 text-sm text-gray-900 dark:text-white
                            hover:bg-purple-600 dark:hover:bg-purple-600 hover:text-gray-100 rounded  w-full"
                    :class="{ 'text-purple-600': option.value === selectedOption.value }"
                >
                    <span>{{ option.label }}</span>
                    <span
                        v-if="option.value === selectedOption.value"
                        class="absolute inset-y-0 left-0 flex items-center pl-3"
                    ></span>
                </li>
            </ul>
        </transition>
    </div>
</template>


<script setup>
import {ref} from 'vue';

const options = ref([
    {value: 'option1', label: 'Option 1'},
    {value: 'option2', label: 'Option 2'},
    {value: 'option3', label: 'Option 3'},
]);

const isOpen = ref(false);

const selectedOption = ref(options.value[0]);

const toggleListbox = () => {
    isOpen.value = !isOpen.value;
};

const selectOption = (option) => {
    selectedOption.value = option;
    isOpen.value = false;
};
</script>

<style>
/* Add your custom styles or Tailwind classes here */
</style>
