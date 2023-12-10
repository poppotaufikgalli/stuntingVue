<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { ref, inject } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Modal from '@/Components/Modal.vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import dayjs from 'dayjs'

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

const form = useForm({
    id_penduduk: props.data.id,
    puskesmas: '',
    posyandu: '',
    tgl_pengukuran: '',
    berat: 0,
    tinggi: 0,
    lila: 0,
    hb: 0,
    hb: 0,
});

const refModal = ref();
const next = ref('create');
const selItemID = ref(0)
const modalVisible = ref(false)

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
    
    form.posyandu = item.posyandu
    form.tgl_pengukuran = item.tgl_pengukuran
    form.berat = item.berat
    form.tinggi = item.tinggi
    form.lila = item.lila
    form.hb = item.hb
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
            form.delete(route('catin.destroy', {id: item.encid}))
        }  
    })
}

const submit = () => {
    if(next.value !== 'create'){
        form.post(route('catin.update', {id: selItemID.value}), {
            onSuccess: () => {
                form.reset()
                closeModal() 
            },
        });
    }else{
        console.log(form);
        form.post(route('catin.store'), {
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
                                                    Posyandu / Lokasi Pemeriksaan
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Tanggal Pemeriksaan
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Usia Pada Saat Pemeriksaan
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Berat Badan (BB) cm
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Tinggi Badan (TB) cm
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Lingkar Lengan (LiLa) cm
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
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Hemoglobin (Hb) gram/dL
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right"></th>
                                    </tr>
                                </thead>            
                                <tbody v-if="data.catin" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="(item, index) in data.catin">
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{index+1}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block px-1 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{item.puskesmas}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{item.posyandu}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{dateFormat(item.tgl_pengukuran)}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="whitespace-pre-line text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{item.usia_saat_ukur}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{item.berat}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{item.tinggi}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block px-1 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{item.lila}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block px-1 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{item.lila_stunting}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block px-1 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{item.hb}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <Modal :show="modalVisible" @close="closeModal" maxWidthClass="3xl">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="p-4 sm:p-7">
                    <div class="text-center">
                        <h2 class="block text-2xl font-bold text-gray-800 dark:text-gray-200">Tambah Data Pengukuran</h2>
                    </div>

                    <div class="mt-5">
                        <!-- Form -->
                        <form @submit.prevent="submit" class="mt-6 space-y-5">
                            <div>
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
                            <div>
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
                            <div>
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
                                    <InputLabel for="hb" value="Hemoglobin" />

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
