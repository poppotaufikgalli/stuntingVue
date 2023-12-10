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
    nik: props.data.nik,
    tgl_lahir: props.data.tgl_lahir,
    puskesmas: '',
    posyandu: '',
    tgl_pengukuran: '',
    berat: 0,
    tinggi: 0,
    cara_ukur_tinggi: '',
    jml_vit_a: 0,
    pmt_diterima_kg: 0,
    lila:0,
});

const refModal = ref();
const next = ref('create');
const selItemID = ref(0)
const modalVisible = ref(false)


const reset = () => {
    //form.reset()
    //puskesmas_id.value = 0
    HSOverlay.close(refModal.value)
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
    
    form.posyandu = item.posyandu
    form.tgl_pengukuran = item.tgl_pengukuran
    form.berat = item.berat
    form.tinggi = item.tinggi
    form.cara_ukur_tinggi = item.cara_ukur_tinggi
    form.jml_vit_a = item.jml_vit_a
    form.pmt_diterima_kg = item.pmt_diterima_kg
    form.lila = item.lila
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
            form.delete(route('eppgbm.destroy', {id: item.encid}))
        }  
    })
}

const submit = () => {
    if(next.value !== 'create'){
        form.post(route('eppgbm.update', {id: selItemID.value}), {
            onSuccess: () => {
                form.reset()
                closeModal() 
            },
        });
    }else{
        form.post(route('eppgbm.store'), {
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
                                                    Posyandu
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
                                                    Berat Badan (BB)
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Tinggi Badan (TB)
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    BB/U
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    TB/U
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    BB/TB
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right"></th>
                                    </tr>
                                </thead>            
                                <tbody v-if="data.pengukuran" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="(item, index) in data.pengukuran">
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
                                                    {{item.bb_u}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block px-1 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{item.tb_u}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block px-1 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{item.bb_tb}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 gap-x-1 flex justify-evenly items-center">
                                                <button 
                                                    class="inline-flex items-center gap-x-1.5 text-sm text-blue-600 decoration-2 hover:underline font-medium hs-dropdown-toggle"
                                                    data-hs-overlay="#hs-modal-pengukuran"
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
        
        <Modal :show="modalVisible" @close="closeModal">
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
                                <InputLabel for="posyandu" value="Nama Posyandu" />

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
                                <div class="basis-1/3">
                                    <div class="flex flex-row items-center gap-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 01-2.031.352 5.989 5.989 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971z" />
                                        </svg>

                                        <InputLabel for="berat" value="Berat Badan" />
                                    </div>

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
                                            <span class="text-gray-500">kg</span>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.berat" />
                                </div>
                            
                                <div class="basis-1/3">
                                    <div class="flex flex-row items-center gap-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                        </svg>

                                        <InputLabel for="tinggi" value="Tinggi Badan" />
                                    </div>

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
                                <div class="basis-1/3">
                                    <InputLabel for="cara_ukur_tinggi" value="Cara Ukur Tinggi" />

                                    <SelectInput
                                        id="cara_ukur_tinggi"
                                        class="mt-2 block w-full"
                                        v-model="form.cara_ukur_tinggi"
                                        :options="{
                                            '1' : 'Berdiri',
                                            '2' : 'Terlentang',
                                        }"
                                        required
                                    />


                                    <InputError class="mt-2" :message="form.errors.cara_ukur_tinggi" />
                                </div>
                            </div>

                            <div class="flex flex-row gap-2">
                                <div class="basis-1/2">
                                    <InputLabel for="lila" value="Pengukuran Lingkar Lengan" />

                                    <TextInput
                                        id="lila"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.lila"
                                        @keypress="cleanVariable"
                                    />

                                    <InputError class="mt-2" :message="form.errors.lila" />
                                </div>

                                <div class="basis-1/2">
                                    <InputLabel for="jml_vit_a" value="Pemberian Vitamin A" />

                                    <SelectInput
                                        id="jml_vit_a"
                                        class="mt-1 block w-full"
                                        v-model="form.jml_vit_a"
                                        :options="{
                                            '1' : 'Ya',
                                            '0' : 'Tidak',
                                        }"
                                        required
                                    />

                                    <InputError class="mt-2" :message="form.errors.jml_vit_a" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="pmt_diterima_kg" value="PMT / Kg" />

                                <TextInput
                                    id="pmt_diterima_kg"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.pmt_diterima_kg"
                                    @keypress="cleanVariable"
                                />

                                <InputError class="mt-2" :message="form.errors.pmt_diterima_kg" />
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
        <div class="flex">
            dasda
        </div>
    </section>
</template>
