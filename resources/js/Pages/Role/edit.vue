<script setup>
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import _ from 'lodash';

const props = defineProps({
    data: {
        type: Object,
        default: [],
    },
    routeList: {
        type: Object,
        default: [],  
    }
});

function defRoute(){
    if(_.size(props.data) > 0){
        return route('akses.update', {'id' : props.data.id})    
    }else{
        return route('akses.store')
    }
}

async function reset(){
    form.reset()
    usePage().props.flash = {
        type: 'info',
        message: 'Data Telah Di Reset',
    }
}

const form = useForm({
    nama: props.data.nama,
    ket: props.data.ket,
    akses: props.data.akses ? props.data.akses.split(', ') : [],
});

function back(){
    window.location.href = "/akses";
}

function getKeyName(key)
{
    let data = key.split('.')
    return data[1] ?? data[0]
}   

</script>

<template>
    <Head title="Data Hak Akses" />

    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center float-right">
                <SecondaryButton @click="back">Kembali</SecondaryButton>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{_.size(data) > 0  ? "Edit Hak Akses an. "+data.nama : "Tambah Hak Akses "}} 
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8 space-t-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <section>
                        <form @submit.prevent="form.post(defRoute())" class="mt-6 space-y-5">
                            <div class="grid sm:grid-cols-2 gap-2">
                                <div>
                                    <InputLabel for="name" value="Nama Hak Akses" />

                                    <TextInput
                                        id="nama"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.nama"
                                        required
                                    />

                                    <InputError class="mt-2" :message="form.errors.nama" />
                                </div>
                                <div>
                                    <InputLabel for="ket" value="Keterangan Hak Akses" />

                                    <TextInput
                                        id="ket"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.ket"
                                        required
                                    />

                                    <InputError class="mt-2" :message="form.errors.ket" />
                                </div>
                            </div>

                            <div>
                                <InputLabel value="List Hak Akses" />

                                <template v-if="routeList">
                                    <template v-for="(routes, routeKey) in routeList" :key="routeKey">
                                        <div class="grid sm:grid-cols-6 gap-2 mt-2">
                                            <div v-for="(item, key) in routes" :key="key" class="mb-3">
                                                <label :for="key" class="flex p-3 block w-full bg-white border border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400">
                                                    <input type="checkbox" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 pointer-events-none focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" :id="key" v-model="form.akses" :value="key">
                                                    <span class="text-sm text-gray-500 ml-3 dark:text-gray-400 capitalize">{{getKeyName(key)}}</span>
                                                </label>
                                            </div>
                                        </div>    
                                    </template>
                                </template>
                                
                                <InputError class="mt-2" :message="form.errors.akses" />
                            </div>

                            <div class="flex flex-row">
                                <div class="flex items-center gap-4">
                                    <PrimaryButton :disabled="form.processing">Simpan</PrimaryButton>
                                    <SecondaryButton :disabled="form.processing" @click="reset">Reset</SecondaryButton>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
