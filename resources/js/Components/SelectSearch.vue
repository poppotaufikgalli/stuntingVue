<script setup>
import { onMounted, ref, computed } from 'vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

defineProps(['modelValue', 'options']);

defineEmits(['update:modelValue']);

const input = ref(null);

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <v-select
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
        label="nama"
        :options="options"
        :value="modelValue"
        @input="$emit('update:modelValue', val)"
        ref="input"
    >
    </v-select>
</template>
