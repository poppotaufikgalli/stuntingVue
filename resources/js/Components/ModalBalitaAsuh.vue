<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Modal from '@/Components/Modal.vue';
import _ from 'lodash';
import dayjs from 'dayjs';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const props = defineProps(['data', 'modelValue', 'modalBalitaShow', 'title']);

const emit = defineEmits(['update:modelValue']);

const modalVisible = ref(props.modalBalitaShow ?? false)

const form = useForm({
    penduduk: props.data,
    balita: '',
    nik_balita: '',
    tgl_asuh: '',
    status_asuh: 'A',
});

const openModal = () =>{
	modalVisible.value = true;
}

const closeModal = () =>{
	modalVisible.value = false;
}

function dateFormat(dateString) {
    if(dayjs(dateString, 'YYYY-MM-DD').isValid()){
        return dayjs(dateString, 'YYYY-MM-DD').format('DD-MM-YYYY') 
    }
    return ""
}

</script>

<style scoped>
>>> {
  --vs-controls-color: rgb(209 213 219 / var(--tw-text-opacity));
  --vs-border-color: rgb(55 65 81 / var(--tw-border-opacity));
  --vs-dropdown-bg: rgb(255 255 255 / var(--tw-bg-opacity));
  --vs-dropdown-option-color: rgb(209 213 219 / var(--tw-text-opacity));
  --vs-selected-color: rgb(209 213 219 / var(--tw-text-opacity));
  --vs-search-input-color: rgb(209 213 219 / var(--tw-text-opacity));
  --vs-dropdown-option--active-color: rgb(209 213 219 / var(--tw-text-opacity));
}
</style>

<template>
    <div>
        <PrimaryButton type="button" v-on:click="openModal" class="mt-1 gap-2">
            Cari Data Balita 1
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
            </svg>
        </PrimaryButton>
    </div>
	
</template>