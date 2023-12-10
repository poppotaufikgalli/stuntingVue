<script setup>
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TableImport from '@/Components/TableImport.vue';
import _ from 'lodash';

const props = defineProps({
    data: {
        type: Object,
        default: [],
    }
})

//console.log(props.data)

const form = useForm({
    fileUpload: null,
})

function submit() {
    form.post('/import/store')
}

const reset = () => {
    form.reset()
    console.log("reset")
}

const displayData = computed({
    get(){
        var data = props.data
        var result = {
            data : data
        }
        return result
    }
})

function deleteRow(rowid){
    var data = displayData.value
    data.data.splice(rowid, 1)
    console.log(displayData.value)
}

function deleteCol(colid){
    var data = displayData.value
    data.data.splice(rowid, 1)
    console.log(displayData.value)
}


function back(){
    window.location.href = "/import";
}
</script>

<template>
    <Head title="Import Data" />

    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center float-right">
                <SecondaryButton v-on:click="back">Kembali</SecondaryButton>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{_.size(data) > 0 && data.total > 0  ? "Edit Hak Akses an. "+data.nama : "Tambah Data Import "}} 
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8 space-t-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <template v-if="data && data.total > 0">
                        <div class="flex justify-end mb-2">
                            <SecondaryButton>Import Data</SecondaryButton>
                        </div>
                        <!-- Table -->
                        <TableImport 
                            :data="data"
                            :actionBtn="{
                                'delete' : deleteRow
                            }"
                        />
                        <!-- End Table -->
                    </template>
                    <template v-else>
                        <form @submit.prevent="submit">
                            <!-- File Uploading Progress Form -->
                            <div class="flex flex-row">
                                <div class="w-full">
                                    <InputLabel for="ket" value="Upload File yang akan diimport (.xlsx)" />
                                    <div class="flex flex-row items-center gap-x-4">
                                        <input type="file" class="mt-1 block border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm disabled:opacity-60 disabled:italic w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:dark:text-gray-700 hover:file:bg-gray-300" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" v-on:input="form.fileUpload = $event.target.files[0]">

                                        <a class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                                        :href="route('import.show')" download
                                        >Download Template</a>
                                    </div>
                                    <InputError class="mt-2" :message="form.errors.ket" />
                                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                        {{ form.progress.percentage }}%
                                    </progress>
                                </div>
                            </div>
                            <div class="flex flex-row mt-4">
                                <div class="flex items-center gap-4">
                                    <PrimaryButton :disabled="form.processing">Upload</PrimaryButton>
                                    <SecondaryButton :disabled="form.processing" v-on:click="form.reset()">Reset</SecondaryButton>
                                </div>
                            </div>
                        </form>
                    </template>
                </div>
            </div>
        </div>        
    </AuthenticatedLayout>
</template>
