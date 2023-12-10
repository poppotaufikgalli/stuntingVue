<script setup>
import { ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextInput2 from '@/Components/TextInput2.vue';
import SelectInput from '@/Components/SelectInput.vue';

import _ from 'lodash';

const props = defineProps({
    data: {
        type: Object,
        default: [],
    },
    role: {
        type: Object,
        default: [],  
    }
});

const refImg = ref(props.data.fileUpload ? '/file/'+ props.data.fileUpload : '/img/no-image.jpeg');

function defRoute(){
    if(_.size(props.data) > 0){
        return route('pengguna.update', {'id' : props.data.id})    
    }else{
        return route('pengguna.store')
    }
}

async function reset(){
    form.reset()
    usePage().props.flash = {
        type: 'info',
        message: 'Data Telah Di Reset',
    }
    //console.log()
}

const form = useForm({
    nip: props.data.nip,
    name: props.data.name,
    email: props.data.email,
    username: props.data.username,
    role: props.data.role,
    password: '',
    confirm_password: '',
    fileUpload: '',
    status_user: props.data.status_user,
});

function onFileChange(e){
    var files = e.target.files || e.dataTransfer.files;

    if(files[0].size > 2097152){
        usePage().props.flash = {
            type: 'error',
            message: 'Ukuran Gambar terlalu Besar',
        }
    }else{
        refImg.value = URL.createObjectURL(files[0]);
        form.fileUpload = files[0];
    }
}

function back(){
    window.location.href = "/pengguna";
}

</script>

<template>
    <Head title="Data Pengguna" />

    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center float-right">
                <SecondaryButton @click="back">Kembali</SecondaryButton>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{_.size(data) > 0  ? "Edit Pengguna an. "+data.name : "Tambah Pengguna "}} 
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8 space-t-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <section>
                        <form @submit.prevent="form.post(defRoute())" class="mt-6 space-y-5">
                            <div class="flex flex-row gap-4">
                                <div class="basis-3/4">
                                    <div class="flex flex-col gap-4">
                                        <div class="flex flex-row gap-4">
                                            <div class="basis-1/3">
                                                <div>
                                                    <InputLabel for="nip" value="NIP" />

                                                    <TextInput
                                                        id="nip"
                                                        type="text"
                                                        class="mt-1 block w-full"
                                                        v-model="form.nip"
                                                        required
                                                        autofocus
                                                    />

                                                    <InputError class="mt-2" :message="form.errors.nip" />
                                                </div>
                                            </div>

                                            <div class="basis-2/3">
                                                <div>
                                                    <InputLabel for="name" value="Nama" />

                                                    <TextInput
                                                        id="name"
                                                        type="text"
                                                        class="mt-1 block w-full"
                                                        v-model="form.name"
                                                        required
                                                    />

                                                    <InputError class="mt-2" :message="form.errors.name" />
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <InputLabel for="email" value="Email" />

                                            <TextInput
                                                id="email"
                                                type="email"
                                                class="mt-1 block w-full"
                                                v-model="form.email"
                                                required
                                            />

                                            <InputError class="mt-2" :message="form.errors.email" />
                                        </div>

                                        <div class="flex flex-row gap-4">
                                            <div class="basis-1/2">
                                                <div>
                                                    <InputLabel for="username" value="Username" />

                                                    <TextInput
                                                        id="username"
                                                        type="text"
                                                        class="mt-1 block w-full"
                                                        v-model="form.username"
                                                        required
                                                    />

                                                    <InputError class="mt-2" :message="form.errors.username" />
                                                </div>
                                            </div>

                                            <div class="basis-1/2">
                                                <div>
                                                    <InputLabel for="role" value="Hak Akses" />

                                                    <SelectInput
                                                        id="role"
                                                        class="mt-1 block w-full"
                                                        :options="role"
                                                        v-model="form.role"
                                                        required
                                                    />

                                                    <InputError class="mt-2" :message="form.errors.role" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-row gap-4">
                                            <template v-if="_.size(props.data) == 0">
                                                <div class="basis-1/2">
                                                    <div>
                                                        <InputLabel for="password" value="Password" />

                                                        <TextInput2
                                                            id="password"
                                                            type="text"
                                                            class="mt-1 w-full"
                                                            v-model="form.password"
                                                            :generate="true"
                                                            required
                                                        />
                                                        <InputError class="mt-2" :message="form.errors.password" />
                                                    </div>
                                                </div>
                                            </template>

                                            <div class="basis-1/2">
                                                <div>
                                                    <InputLabel for="status_user" value="Status User" />

                                                    <SelectInput
                                                        id="status_user"
                                                        class="mt-1 block w-full"
                                                        :options="{
                                                            0: 'Tidak Aktif', 
                                                            1: 'Aktif'
                                                        }"
                                                        v-model="form.status_user"
                                                        required
                                                    />

                                                    <InputError class="mt-2" :message="form.errors.status_user" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="basis-1/4">
                                    <div class="w-full">
                                        <label for="fileUpload">
                                            <img :src="refImg" class="bg-white dark:bg-gray-800 border h-full w-full" />    
                                        </label>
                                        
                                        <input type="file" id="fileUpload" class="hidden" v-on:change="onFileChange" accept="image/*">
                                        <InputError class="mt-2" :message="form.errors.fileUpload" />
                                    </div>
                                </div>

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
