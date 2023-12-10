<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { ref, inject, computed } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import Modal from '@/Components/Modal.vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import dayjs from 'dayjs';
import _ from 'lodash';

const swal = inject('$swal')

const props = defineProps({
    data: {
        type: Object,
        default: []
    },
    masterPuskesmas: {
        type: Object,
        default: []
    },
});

const dataHamil = computed({
    get(){
        let result =  _.groupBy(props.data.hamil, 'hamil_ke')
        //console.log(result)
        return result;
    }
})

const form = useForm({
    id_penduduk: props.data.id,
    hamil_ke: '',
    anak_ke: '',
    puskesmas: '',
    posyandu: '',
    tgl_pengukuran: '',
    usia_kehamilan: '',
    berat: 0,
    tinggi: 0,
    lila: 0,
    tekanan_darah: 0,
    tinggi_fu: 0,
    hb: 0,
    vdrl: 0,
    denyut_janin: 0,
    protein_urine: 0,
    reduksi_urine: 0,
    imunisasi_tt: false,
    tablet_fe: false,
    perawatan_payudara: false,
    senam_hamil: false,
    temu_wicara: false,
    terapi_yodium: false,
    terapi_malaria: false,
    catatan: '',
});

const refModal = ref();
const next = ref('create');
const selItemID = ref(0)
const modalVisible = ref(false)

function tambahPengukuran(index){
    form.reset()
    openModal()
    form.hamil_ke = index
    //form.anak_ke = item.anak_ke
}

function editPengukuran(item){
    openModal()
    selItemID.value = item.id
    next.value = 'edit';

    if(item.id_puskesmas == "" || item.id_puskesmas == null){
        //console.log("A")
    }else{
        form.puskesmas = {
            'id': item.id_puskesmas,
            'nama': item.puskesmas,
        };    
    }
    form.hamil_ke = item.hamil_ke
    form.anak_ke = item.anak_ke
    form.posyandu = item.posyandu
    form.tgl_pengukuran = item.tgl_pengukuran
    form.usia_kehamilan = item.usia_kehamilan
    form.berat = item.berat
    form.tinggi = item.tinggi
    form.lila = item.lila
    form.tekanan_darah = item.tekanan_darah
    form.tinggi_fu = item.tinggi_fu
    form.hb = item.hb
    form.vdrl = item.vdrl
    form.denyut_janin = item.denyut_janin
    form.protein_urine = item.protein_urine
    form.reduksi_urine = item.reduksi_urine
    form.imunisasi_tt = item.imunisasi_tt == 1
    form.tablet_fe = item.tablet_fe == 1
    form.perawatan_payudara = item.perawatan_payudara == 1
    form.senam_hamil = item.senam_hamil == 1
    form.temu_wicara = item.temu_wicara == 1
    form.terapi_yodium = item.terapi_yodium == 1
    form.terapi_malaria = item.terapi_malaria == 1
    form.catatan = item.catatan
}

function hapusPengukuran(item){
    //console.log(item)
    swal({
        title: 'Yakin untuk Menghapus Data?',
        text: "Data pengukuran tanggal "+ dateFormat(item.tgl_pengukuran),
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: "Batal",
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('hamil.destroy', {id: item.encid}))
        }  
    })
}

const submit = () => {
    if(next.value !== 'create'){
        form.post(route('hamil.update', {id: selItemID.value}), {
            onSuccess: () => {
                form.reset()
                closeModal() 
            },
        });
    }else{
        console.log(form);
        form.post(route('hamil.store'), {
             onSuccess: () => {
                form.reset()
                closeModal() 
            },
        });
    }
}

function cleanVariable(evt){
    //let v = e.target.value.replace(/[^0-9]/g, "")
    const charCode = evt.which ? evt.which : evt.keyCode;
    if (
        charCode > 31 &&
        (charCode < 48 || charCode > 57) &&
        charCode !== 46
    ) {
        evt.preventDefault();
    }
}

function dateFormat(dateString) {
    if(dayjs(dateString, 'YYYY-MM-DD').isValid()){
        return dayjs(dateString, 'YYYY-MM-DD').format('DD-MM-YYYY') 
    }
    return ""
}

const closeModal = () => {
    modalVisible.value = false
    next.value = 'create';
    selItemID.value = 0
}

const openModal = () => {
    modalVisible.value = true
}

function calcUrine(i){
    if(i > 0){
        let res = "";
        for (var c = i - 1; c >= 0; c--) {
            res = res + "+";
        }
        return res;
    }else{
        return ""
    }
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
    <section>
        <header>
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Data Pengukuran</h2>
                <PrimaryButton 
                    type="button" class="gap-x-2" v-on:click="openModal">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Tambah Data
                </PrimaryButton>
            </div>
        </header>

        <div class="mt-6">
            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-slate-800">
                                    <tr>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    No.
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Puskesmas
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Posyandu / Lokasi & Tanggal Pemeriksaan
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Usia Kehamilan
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right" colspan="10">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Pemeriksaan
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Potensi Stunting (/Lila)
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right"></th>
                                    </tr>
                                </thead>            
                                <tbody v-if="dataHamil" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <template v-for="(group, index) in dataHamil" :key="index">
                                        <tr>
                                            <td class="h-px w-px border align-top">
                                                <div class="py-1 text-center">
                                                    <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                        {{index}}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="h-px w-px border align-top" colspan="14">
                                                <div class="py-1 px-2 text-left">
                                                    <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                        Kehamilan Ke- {{index}}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="h-px w-px border align-top">
                                                <div class="py-1 gap-x-1 flex justify-evenly items-center">
                                                    <button 
                                                        class="inline-flex items-center gap-x-1.5 text-sm text-green-600 decoration-2 hover:underline font-medium"
                                                        v-on:click="tambahPengukuran(index)"
                                                    >
                                                        Tambah
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <template v-if="group" v-for="(item, key) in group" :key="key">
                                            <tr>
                                                <td class="h-px w-px border align-top" rowspan="2"></td>
                                                <td class="h-px w-px border align-top" rowspan="2">
                                                    <div class="py-1 text-left">
                                                        <span class="block ps-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                            {{item.puskesmas}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top" rowspan="2">
                                                    <div class="py-1 ps-2 text-left">
                                                        <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                            {{item.posyandu}}
                                                        </span>
                                                        <span class="block text-sm font-base text-gray-800 dark:text-gray-200">
                                                            {{dateFormat(item.tgl_pengukuran)}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top" rowspan="2">
                                                    <div class="py-1 text-center">
                                                        <span class="whitespace-pre-line text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                            {{item.usia_kehamilan}} Minggu
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-center border border-right">
                                                    <div class="flex justify-center">
                                                        <span class="px-1 text-xs font-semibold tracking-wide text-gray-800 dark:text-gray-200">
                                                            BB
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-center border border-right">
                                                    <div class="flex justify-center">
                                                        <span class="px-1 text-xs font-semibold tracking-wide text-gray-800 dark:text-gray-200">
                                                            TB
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-center border border-right">
                                                    <div class="flex justify-center">
                                                        <span class="px-1 text-xs font-semibold tracking-wide text-gray-800 dark:text-gray-200">
                                                            LiLa
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-center border border-right">
                                                    <div class="flex justify-center">
                                                        <span class="px-1 text-xs font-semibold tracking-wide text-gray-800 dark:text-gray-200">
                                                            Tekanan Darah
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-center border border-right">
                                                    <div class="flex justify-center">
                                                        <span class="px-1 text-xs font-semibold tracking-wide text-gray-800 dark:text-gray-200">
                                                            Tinggi FU
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-center border border-right">
                                                    <div class="flex justify-center">
                                                        <span class="px-1 text-xs font-semibold tracking-wide text-gray-800 dark:text-gray-200">
                                                            Hb
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-center border border-right">
                                                    <div class="flex justify-center">
                                                        <span class="px-1 text-xs font-semibold tracking-wide text-gray-800 dark:text-gray-200">
                                                            Denyut Janin
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-center border border-right">
                                                    <div class="flex justify-center">
                                                        <span class="px-1 text-xs font-semibold tracking-wide text-gray-800 dark:text-gray-200">
                                                            VDRL
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-center border border-right">
                                                    <div class="flex justify-center">
                                                        <span class="px-1 text-xs font-semibold tracking-wide text-gray-800 dark:text-gray-200">
                                                            Protein Urine
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-center border border-right">
                                                    <div class="flex justify-center">
                                                        <span class="px-1 text-xs font-semibold tracking-wide text-gray-800 dark:text-gray-200">
                                                            Reduksi Urine
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top" rowspan="2">
                                                    <div class="py-1 text-center">
                                                        <span class="whitespace-pre-line text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                            {{item.lila_stunting}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top" rowspan="2">
                                                    <div class="py-1 gap-x-1 flex justify-evenly items-center">
                                                        <button 
                                                            class="inline-flex items-center gap-x-1.5 text-sm text-blue-600 decoration-2 hover:underline font-medium"
                                                            v-on:click="editPengukuran(item)"
                                                        >
                                                            Edit
                                                        </button>
                                                        <button 
                                                            class="inline-flex items-center gap-x-1.5 text-sm text-red-600 decoration-2 hover:underline font-medium"
                                                            v-on:click="hapusPengukuran(item)"
                                                        >
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="h-px w-px border align-top">
                                                    <div class="py-1 text-center">
                                                        <span class="block text-xs font-semibold text-gray-800 dark:text-gray-200">
                                                            {{item.berat}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top">
                                                    <div class="py-1 text-center">
                                                        <span class="block text-xs font-semibold text-gray-800 dark:text-gray-200">
                                                            {{item.tinggi}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top">
                                                    <div class="py-1 text-center">
                                                        <span class="block px-1 text-xs font-semibold text-gray-800 dark:text-gray-200">
                                                            {{item.lila}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top">
                                                    <div class="py-1 text-center">
                                                        <span class="block px-1 text-xs font-semibold text-gray-800 dark:text-gray-200">
                                                            {{item.tekanan_darah}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top">
                                                    <div class="py-1 text-center">
                                                        <span class="block px-1 text-xs font-semibold text-gray-800 dark:text-gray-200">
                                                            {{item.tinggi_fu}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top">
                                                    <div class="py-1 text-center">
                                                        <span class="block px-1 text-xs font-semibold text-gray-800 dark:text-gray-200">
                                                            {{item.hb}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top">
                                                    <div class="py-1 text-center">
                                                        <span class="block px-1 text-xs font-semibold text-gray-800 dark:text-gray-200">
                                                            {{item.denyut_janin}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top">
                                                    <div class="py-1 text-center">
                                                        <span class="block px-1 text-xs font-semibold text-gray-800 dark:text-gray-200">
                                                            {{item.vdrl == -1 ? "-" : (item.vdrl == 1 ? "+": "")}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top">
                                                    <div class="py-1 text-center">
                                                        <span class="block px-1 text-xs font-semibold text-gray-800 dark:text-gray-200">
                                                            {{calcUrine(item.protein_urine)}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="h-px w-px border align-top">
                                                    <div class="py-1 text-center">
                                                        <span class="block px-1 text-xs font-semibold text-gray-800 dark:text-gray-200">
                                                            {{calcUrine(item.reduksi_urine)}}
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <Modal :show="modalVisible" @close="closeModal" maxWidth="4xl">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="p-4 sm:p-7">
                    <div class="text-center">
                        <h2 class="block text-2xl font-bold text-gray-800 dark:text-gray-200">Tambah Data Pengukuran</h2>
                    </div>

                    <div class="mt-5">
                        <!-- Form -->
                        <form @submit.prevent="submit" class="mt-6 space-y-5">
                            <div class="flex flex-row gap-2">
                                <div class="basis-1/2">
                                    <InputLabel for="puskesmas" value="Nama Puskesmas" />

                                    <v-select
                                        class="mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        label="nama"
                                        required
                                        :options="masterPuskesmas"
                                        v-model="form.puskesmas"
                                    >
                                        <template #search="{attributes, events}">
                                            <input
                                                class="vs__search"
                                                :required="!form.puskesmas"
                                                v-bind="attributes"
                                                v-on="events"
                                            />
                                        </template>
                                    </v-select>
                                    
                                    <InputError class="mt-2" :message="form.errors.puskesmas" />
                                </div>
                                <div class="basis-1/2">
                                    <InputLabel for="posyandu" value="Nama Posyandu / Lokasi Pemeriksaan" />

                                    <TextInput
                                        id="posyandu"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.posyandu"
                                        required
                                        autofocus
                                    />

                                    <InputError class="mt-2" :message="form.errors.posyandu" />
                                </div>
                            </div>

                            <div class="flex flex-row gap-2">
                                <div class="basis-1/2">
                                    <InputLabel for="tgl_pengukuran" value="Tanggal Pengukuran" />

                                    <TextInput
                                        id="tgl_pengukuran"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.tgl_pengukuran"
                                        required
                                        autofocus
                                    />

                                    <InputError class="mt-2" :message="form.errors.tgl_pengukuran" />
                                </div>

                                <div class="basis-1/2">
                                    <InputLabel for="hamil_ke" value="Kehamilan Ke-" />

                                    <TextInput
                                        id="hamil_ke"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.hamil_ke"
                                        required
                                        autofocus
                                    />

                                    <InputError class="mt-2" :message="form.errors.hamil_ke" />
                                </div>

                                <div class="basis-1/2">
                                    <InputLabel for="anak_ke" value="Anak Ke-" />

                                    <TextInput
                                        id="anak_ke"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.anak_ke"
                                        required
                                    />

                                    <InputError class="mt-2" :message="form.errors.anak_ke" />
                                </div>

                                <div class="basis-1/2">
                                    <InputLabel for="usia_kehamilan" value="Usia Kehamilan" />

                                    <div class="relative">
                                        <TextInput
                                            id="usia_kehamilan"
                                            type="number"
                                            class="mt-1 block w-full"
                                            v-model="form.usia_kehamilan"
                                            required
                                        />
                                        <div class="absolute inset-y-0 right-0 flex items-center pointer-events-none z-20 pr-4">
                                            <span class="text-gray-500">Minggu</span>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.usia_kehamilan" />
                                </div>
                            </div>

                            <hr>

                            <div class="flex flex-row gap-2">
                                <div class="basis-1/4">
                                    <InputLabel for="berat" value="Berat Badan" />

                                    <div class="relative">
                                        <TextInput
                                            id="berat"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.berat"
                                            @keypress="cleanVariable"
                                            required
                                        />
                                        <div class="absolute inset-y-0 right-0 flex items-center pointer-events-none z-20 pr-4">
                                            <span class="text-gray-500">Kg</span>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.berat" />
                                </div>

                                <div class="basis-1/4">
                                    <InputLabel for="tinggi" value="Tinggi Badan" />

                                    <div class="relative">
                                        <TextInput
                                            id="tinggi"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.tinggi"
                                            @keypress="cleanVariable"
                                            required
                                        />
                                        <div class="absolute inset-y-0 right-0 flex items-center pointer-events-none z-20 pr-4">
                                            <span class="text-gray-500">cm</span>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.tinggi" />
                                </div>

                                <div class="basis-1/4">
                                    <InputLabel for="lila" value="Lingkar Lengan" />

                                    <div class="relative">
                                        <TextInput
                                            id="lila"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.lila"
                                            @keypress="cleanVariable"
                                            required
                                        />
                                        <div class="absolute inset-y-0 right-0 flex items-center pointer-events-none z-20 pr-4">
                                            <span class="text-gray-500">cm</span>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.lila" />
                                </div>

                                <div class="basis-1/4">
                                    <InputLabel for="tekanan_darah" value="Tekanan Darah" />

                                    <div class="relative">
                                        <TextInput
                                            id="tekanan_darah"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.tekanan_darah"
                                            @keypress="cleanVariable"
                                            required
                                        />
                                        <div class="absolute inset-y-0 right-0 flex items-center pointer-events-none z-20 pr-4">
                                            <span class="text-gray-500">mmHg</span>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.tekanan_darah" />
                                </div>
                            </div>

                            <div class="flex flex-row gap-2">
                                <div class="basis-1/4">
                                    <InputLabel for="tinggi_fu" value="Tinggi Fundus Uteri" />

                                    <div class="relative">
                                        <TextInput
                                            id="tinggi_fu"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.tinggi_fu"
                                            @keypress="cleanVariable"
                                            required
                                        />
                                        <div class="absolute inset-y-0 right-0 flex items-center pointer-events-none z-20 pr-4">
                                            <span class="text-gray-500">cm</span>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.tinggi_fu" />
                                </div>

                                <div class="basis-1/4">
                                    <InputLabel for="hb" value="Pemeriksaan Hemoglobin" />

                                    <div class="relative">
                                        <TextInput
                                            id="hb"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.hb"
                                            @keypress="cleanVariable"
                                            required
                                        />    
                                        <div class="absolute inset-y-0 right-0 flex items-center pointer-events-none z-20 pr-4">
                                            <span class="text-gray-500">gram/dL</span>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.hb" />
                                </div>

                                <div class="basis-1/4">
                                    <InputLabel for="denyut_janin" value="Pemeriksaan Denyut Janin" />

                                    <div class="relative">
                                        <TextInput
                                            id="denyut_janin"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.denyut_janin"
                                            @keypress="cleanVariable"
                                            required
                                        />    
                                        <div class="absolute inset-y-0 right-0 flex items-center pointer-events-none z-20 pr-4">
                                            <span class="text-gray-500">bpm</span>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.denyut_janin" />
                                </div>

                                <div class="basis-1/4">
                                    <InputLabel for="vdrl" value="Pemeriksaan VDRL" />

                                    <SelectInput
                                        id="vdrl"
                                        class="mt-1 block w-full"
                                        v-model="form.vdrl"
                                        :options="{
                                            '0': 'Tidak Diperiksa', 
                                            '1': '+',
                                            '-1': '-'
                                        }"
                                    />

                                    <InputError class="mt-2" :message="form.errors.vdrl" />
                                </div>
                            </div>

                            <div class="flex flex-row gap-2">
                                <div class="basis-1/2">
                                    <InputLabel for="protein_urine" value="Protein Urine Atas Indikasi" />

                                    <SelectInput
                                        id="protein_urine"
                                        class="mt-1 block w-full"
                                        v-model="form.protein_urine"
                                        :options="{
                                            '0': 'Tidak Diperiksa', 
                                            '1': '+',
                                            '2': '++',
                                            '3': '+++',
                                            '4': '++++',
                                        }"
                                    />

                                    <InputError class="mt-2" :message="form.errors.protein_urine" />
                                </div>

                                <div class="basis-1/2">
                                    <InputLabel for="reduksi_urine" value="Reduksi Urine Atas Indikasi" />

                                    <SelectInput
                                        id="reduksi_urine"
                                        class="mt-1 block w-full"
                                        v-model="form.reduksi_urine"
                                        :options="{
                                            '0': 'Tidak Diperiksa', 
                                            '1': '+',
                                            '2': '++',
                                            '3': '+++',
                                            '4': '++++',
                                        }"
                                    />

                                    <InputError class="mt-2" :message="form.errors.reduksi_urine" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div class="block">
                                    <label class="flex items-center">
                                        <Checkbox name="imunisasi_tt" v-model:checked="form.imunisasi_tt" />
                                        <span class="ms-2 font-medium text-sm text-gray-700 dark:text-gray-300">Pemberian Imunisasi TT</span>
                                    </label>
                                </div>
                                <div class="block">
                                    <label class="flex items-center">
                                        <Checkbox name="tablet_fe" v-model:checked="form.tablet_fe" />
                                        <span class="ms-2 font-medium text-sm text-gray-700 dark:text-gray-300">Pemberian Tablet Fe</span>
                                    </label>
                                </div>

                                <div class="block">
                                    <label class="flex items-center">
                                        <Checkbox name="perawatan_payudara" v-model:checked="form.perawatan_payudara" />
                                        <span class="ms-2 font-medium text-sm text-gray-700 dark:text-gray-300">Perawatan Payudara</span>
                                    </label>
                                </div>

                                <div class="block">
                                    <label class="flex items-center">
                                        <Checkbox name="senam_hamil" v-model:checked="form.senam_hamil" />
                                        <span class="ms-2 font-medium text-sm text-gray-700 dark:text-gray-300">Senam Ibu Hamil</span>
                                    </label>
                                </div>

                                <div class="block">
                                    <label class="flex">
                                        <Checkbox name="temu_wicara" v-model:checked="form.temu_wicara" />
                                        <span class="ms-2 font-medium text-sm text-gray-700 dark:text-gray-300">Temu Wicara dalam Rangka Persiapan Rujukan</span>
                                    </label>
                                </div>

                                <div class="block">
                                    <label class="flex">
                                        <Checkbox name="terapi_yodium" v-model:checked="form.terapi_yodium" />
                                        <span class="ms-2 font-medium text-sm text-gray-700 dark:text-gray-300">Pemberian Terapi Kapsul Yodium u/ Daerah Endemis Gondok</span>
                                    </label>
                                </div>

                                <div class="block">
                                    <label class="flex">
                                        <Checkbox name="terapi_malaria" v-model:checked="form.terapi_malaria" />
                                        <span class="ms-2 font-medium text-sm text-gray-700 dark:text-gray-300">Pemberian Terapi Anti Malaria u/ Daerah Endemis Malaria</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <InputLabel for="catatan" value="Catatan Pemeriksaan Lainnya" />

                                <textarea
                                    id="catatan"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm disabled:opacity-60 disabled:italic"
                                    v-model="form.catatan"
                                ></textarea>    

                                <InputError class="mt-2" :message="form.errors.catatan" />
                            </div>

                            <div class="flex flex-row">
                                <div class="flex items-center gap-4">
                                    <PrimaryButton :disabled="form.processing">Simpan</PrimaryButton>

                                    <SecondaryButton :disabled="form.processing" @click="closeModal" >Tutup</SecondaryButton>
                                </div>
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                </div>
            </div>
        </Modal>
    </section>
</template>
