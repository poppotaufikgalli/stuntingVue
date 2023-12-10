<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import HapusPendudukForm from './Partials/HapusPendudukForm.vue';
import PendudukForm from './Partials/PendudukForm.vue';
import BalitaAsuhForm from './Partials/BalitaAsuhForm.vue';
import SanitasiForm from './Partials/SanitasiForm.vue';
import PengukuranRemajaForm from './Partials/PengukuranRemajaForm.vue';
import PengukuranCatinForm from './Partials/PengukuranCatinForm.vue';
import PengukuranHamilForm from './Partials/PengukuranHamilForm.vue';

import SecondaryButton from '@/Components/SecondaryButton.vue';
import _ from 'lodash';

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
    masterBalita:{
        type: Object,
        default: [],
    },
    masterPuskesmas: {
        type: Object,
        default: [],  
    },
    njns: String,
    croute: String,
});

function back(){
    window.location.href = "/"+props.croute+"/";
}

</script>

<template>
    <Head :title="'Data ' + njns" />

    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center float-right">
                <SecondaryButton @click="back">Kembali</SecondaryButton>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{data != [] && data.id  ? "Edit Data "+njns+" an. "+data.nama : "Tambah Data "+njns+""}} 
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8 space-t-6">
                <nav class="relative z-0 flex border rounded-t-lg overflow-hidden dark:border-gray-700" aria-label="Tabs" role="tablist">
                    <button type="button" class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-l-0 border-l border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:hover:bg-gray-700 dark:hover:text-gray-400 active" id="bar-penduduk" data-hs-tab="#tab-penduduk" aria-controls="bar-penduduk" role="tab">
                        Data Utama
                    </button>
                    <template v-if="croute == 'penduduk'">
                        <button type="button" :disabled="data.encid == undefined" class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-l-0 border-l border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:hover:bg-gray-700 dark:hover:text-gray-400 dark:hover:text-gray-300" id="bar-balita-asuh" data-hs-tab="#tab-balita-asuh" aria-controls="bar-balita-asuh" role="tab">
                            Orang Tua dari Balita
                        </button>    
                    </template>
                    <template v-if="croute == 'asuh'">
                        <button type="button" :disabled="data.encid == undefined" class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-l-0 border-l border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:hover:bg-gray-700 dark:hover:text-gray-400 dark:hover:text-gray-300" id="bar-balita-asuh" data-hs-tab="#tab-balita-asuh" aria-controls="bar-balita-asuh" role="tab">
                            Balita Asuh
                        </button>    
                    </template>
                    <template v-if="croute == 'remaja'">
                        <button type="button" :disabled="data.encid == undefined" class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-l-0 border-l border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:hover:bg-gray-700 dark:hover:text-gray-400 dark:hover:text-gray-300" id="bar-pengukuran-remaja" data-hs-tab="#tab-pengukuran-remaja" aria-controls="bar-pengukuran-remaja" role="tab">
                            Pengukuran [Remaja]
                        </button>    
                    </template>
                    <template v-if="croute == 'hamil'">
                        <button type="button" :disabled="data.encid == undefined" class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-l-0 border-l border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:hover:bg-gray-700 dark:hover:text-gray-400 dark:hover:text-gray-300" id="bar-pengukuran-hamil" data-hs-tab="#tab-pengukuran-hamil" aria-controls="bar-pengukuran-hamil" role="tab">
                            Pemeriksaan Kehamilan
                        </button>    
                    </template>
                    <template v-if="croute == 'catin'">
                        <button type="button" :disabled="data.encid == undefined" class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-l-0 border-l border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:hover:bg-gray-700 dark:hover:text-gray-400 dark:hover:text-gray-300" id="bar-pengukuran-catin" data-hs-tab="#tab-pengukuran-catin" aria-controls="bar-pengukuran-catin" role="tab">
                            Pemeriksaan Calon Pengantin
                        </button>    
                    </template>
                    <template>
                        <button type="button" :disabled="data.encid == undefined" class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-l-0 border-l border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 text-sm font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:hover:bg-gray-700 dark:hover:text-gray-400 dark:hover:text-gray-300" id="bar-sanitasi" data-hs-tab="#tab-sanitasi" aria-controls="bar-sanitasi" role="tab">
                            Data Sanitasi
                        </button>    
                    </template>
                </nav>
                <div>
                    <div id="tab-penduduk" role="tabpanel" aria-labelledby="bar-penduduk">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-b-lg">
                            <PendudukForm
                                :data="data"
                                :masterOrtu="masterOrtu"
                                :masterKecamatan="masterKecamatan"
                                :masterKelurahan="masterKelurahan"
                                :croute="croute"
                                :njns="njns"
                                class="max-w"
                            />
                        </div>
                    </div>
                    <div id="tab-sanitasi" class="hidden" role="tabpanel" aria-labelledby="bar-sanitasi">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-b-lg">
                            <SanitasiForm
                                :data="data"
                                class="max-w"
                            />
                        </div>
                    </div>
                    <div id="tab-balita-asuh" class="hidden" role="tabpanel" aria-labelledby="bar-balita-asuh">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-b-lg">
                            <BalitaAsuhForm
                                :data="data"
                                :masterBalita="masterBalita"
                                :croute="croute"
                                class="max-w"
                            />
                        </div>
                    </div>
                    <div id="tab-pengukuran-remaja" class="hidden" role="tabpanel" aria-labelledby="bar-pengukuran-remaja">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-b-lg">
                            <PengukuranRemajaForm
                                :data="data"
                                :masterPuskesmas="masterPuskesmas"
                                :croute="croute"
                                class="max-w"
                            />
                        </div>
                    </div>
                    <div id="tab-pengukuran-catin" class="hidden" role="tabpanel" aria-labelledby="bar-pengukuran-catin">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-b-lg">
                            <PengukuranCatinForm
                                :data="data"
                                :masterPuskesmas="masterPuskesmas"
                                :croute="croute"
                                class="max-w"
                            />
                        </div>
                    </div>
                    <div id="tab-pengukuran-hamil" class="hidden" role="tabpanel" aria-labelledby="bar-pengukuran-hamil">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-b-lg">
                            <PengukuranHamilForm
                                :data="data"
                                :masterPuskesmas="masterPuskesmas"
                                :croute="croute"
                                class="max-w"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
