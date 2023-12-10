<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import PemeriksaanForm from './Partials/PemeriksaanForm.vue';
import UpdateInformationForm from './Partials/UpdateInformationForm.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    data: {
        type: Object,
        default: [],
    },
    masterKecamatan: {
        type: Object,
        default: [],
    },
    masterKelurahan: {
        type: Object,
        default: [],
    },
    masterEppgbm: {
        type: Object,
        default: [],
    },
    mustVerifyEmail: Boolean,
    status: String,
});

function back(){
    window.location.href = "/balita";
}
</script>

<template>
    <Head title="Data Balita" />

    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center float-right">
                <SecondaryButton @click="back">Kembali</SecondaryButton>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{data != [] && data.nama != undefined ? "Edit Data Pengukuran Balita an. "+data.nama : "Tambah Data Pengukuran Balita"}}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdateInformationForm
                        :data="data"
                        :masterKecamatan="masterKecamatan"
                        :masterKelurahan="masterKelurahan"
                        class="max-w"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <PemeriksaanForm 
                        :masterEppgbm="masterEppgbm"
                        class="max-w" 
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
