<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BalitaForm from './Partials/BalitaForm.vue';
import OrtuAsuhForm from './Partials/OrtuAsuhForm.vue';
import PengukuranForm from './Partials/PengukuranForm.vue';
import BantuanForm from './Partials/BantuanForm.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    data: {
        type: Object,
        default: [],
    },
    masterOrtu: {
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
    masterPuskesmas: {
        type: Object,
        default: [],
    },
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
                {{data != [] && data.nama != undefined ? "Edit Data Balita an. "+data.nama+" / "+data.nik+" / "+data.jk : "Tambah Data Balita"}}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8 space-t-6">
                <nav class="relative z-0 flex border rounded-t-lg overflow-hidden dark:border-gray-700" aria-label="Tabs" role="tablist">
                    <button type="button" class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-l-0 border-l border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:hover:bg-gray-700 dark:hover:text-gray-400 active" id="bar-balita" data-hs-tab="#tab-balita" aria-controls="bar-balita" role="tab">
                        Data Balita
                    </button>
                    <button type="button" :disabled="data.encid == undefined" class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-l-0 border-l border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:hover:bg-gray-700 dark:hover:text-gray-400 dark:hover:text-gray-300" id="bar-pengukuran" data-hs-tab="#tab-pengukuran" aria-controls="bar-pengukuran" role="tab">
                        Data Pengukuran
                    </button>
                    <button type="button" :disabled="data.encid == undefined" class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-l-0 border-l border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:hover:bg-gray-700 dark:hover:text-gray-400 dark:hover:text-gray-300" id="bar-ortu-asuh" data-hs-tab="#tab-ortu-asuh" aria-controls="bar-ortu-asuh" role="tab">
                        Orang Tua Asuh
                    </button>
                    <button type="button" :disabled="data.encid == undefined" class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-l-0 border-l border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:hover:bg-gray-700 dark:hover:text-gray-400 dark:hover:text-gray-300" id="bar-bantuan" data-hs-tab="#tab-bantuan" aria-controls="bar-bantuan" role="tab">
                        Distribusi Bantuan
                    </button>
                </nav>
                <div>
                    <div id="tab-balita" role="tabpanel" aria-labelledby="bar-balita">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-b-lg">
                            <BalitaForm
                                :data="data"
                                :masterOrtu="masterOrtu"
                                :masterKecamatan="masterKecamatan"
                                :masterKelurahan="masterKelurahan"
                                class="max-w"
                            />
                        </div>
                    </div>
                    <div id="tab-pengukuran" class="hidden" role="tabpanel" aria-labelledby="bar-pengukuran">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-b-lg">
                            <PengukuranForm 
                                :data="data"
                                :masterPuskesmas="masterPuskesmas"
                                class="max-w" 
                            />
                        </div>
                    </div>
                    <div id="tab-ortu-asuh" class="hidden" role="tabpanel" aria-labelledby="bar-ortu-asuh">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-b-lg">
                            <OrtuAsuhForm 
                                :data="data"
                                :masterOrtu="masterOrtu"
                                class="max-w" 
                            />
                        </div>
                    </div>
                    <div id="tab-bantuan" class="hidden" role="tabpanel" aria-labelledby="bar-bantuan">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-b-lg">
                            <BantuanForm 
                                :data="data"
                                :masterOrtu="masterOrtu"
                                class="max-w" 
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
