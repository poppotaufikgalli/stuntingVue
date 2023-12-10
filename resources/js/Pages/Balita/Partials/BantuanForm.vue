<script setup>
import { ref, inject, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import dayjs from 'dayjs'
import _ from 'lodash';

const swal = inject('$swal')

const props = defineProps({
    data: {
        type: Object,
        default: []
    },
    masterOrtu: {
        type: Object,
        default: []
    },
});

const asalBantuan = computed({
    get(){
        return [
            {id: 1, label: "Orang Tua Asuh"},
            {id: 2, label: "Pemerintah"},
            {id: 3, label: "Swasta"},
            {id: 4, label: "Lainnya"},
        ]
    }
})

const modalVisible = ref(false)

function textAsalBantuan(ido){
    let result = _.find(asalBantuan.value, {id: ido*1})
    return result?.label;
}

const form = useForm({
    nik_balita: props.data.nik,
    asal_bantuan: '',
    penduduk: '',
    tempat_bantuan: '',
    nama_pemberi: '',
    tgl_bantuan: '',
    item_bantuan: '',
    nilai_bantuan: 0,
});

const refModal = ref();
const next = ref('create');
const selItemID = ref(0)
const summaryOfBantuan = computed({
    get(){
        return _.sumBy(props.data.bantuan, 'nilai_bantuan')
    }
})

const selBantuan = computed({
    get(){
        //console.log(form)
        return form.asal_bantuan.id == 1 ? true : false
    }
})

function editBantuan(item){
    openModal()
    selItemID.value = item.id
    next.value = 'edit';

    let v = _.find(props.masterOrtu, {id: item.id_penduduk})

    if(v){
        form.penduduk = {
            'id': v.id,
            'nama' : v.nama
        }
    }

    form.asal_bantuan = {
        id: item.asal_bantuan,
        label: textAsalBantuan(item.asal_bantuan)
    }    
    
    form.tempat_bantuan = item.tempat_bantuan
    form.nama_pemberi = item.nama_pemberi
    form.tgl_bantuan = item.tgl_bantuan
    form.item_bantuan = item.item_bantuan
    form.nilai_bantuan = item.nilai_bantuan
}

function hapusBantuan(item){
    //console.log(item)
    swal({
        title: 'Yakin untuk Menghapus Data?',
        text: "Data Bantuan "+ item.nama,
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: "Batal",
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            //router.visit(route('eppgbm.destroy', {id: item.encid}))
            form.delete(route('bantuan.destroy', {id: item.encid}))
            closeModal()
        }  
    })
}

const submit = () => {
    if(next.value !== 'create'){
        form.post(route('bantuan.update', {id: selItemID.value}), {
            onSuccess: () => {
                closeModal()
                form.reset()
            },
        });
    }else{
        form.post(route('bantuan.store'), {
             onSuccess: () => {
                //console.log('onFinish')
                closeModal()
                form.reset()
            },
        });
    }
}

function dateFormat(dateString) {
    if(dayjs(dateString, 'YYYY-MM-DD').isValid()){
        return dayjs(dateString, 'YYYY-MM-DD').format('DD-MM-YYYY') 
    }
    return ""
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

const openModal = () => {
    modalVisible.value = true
}

const closeModal = () => {
    modalVisible.value = false
    selItemID.value = 0
    next.value = 'create'  
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
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Distribusi Bantauan</h2>
                <PrimaryButton
                    type="button" 
                    class="gap-x-2" v-on:click="openModal">
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
                                                    Asal Bantuan
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Tanggal / Tempat Pemberian
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Item Bantuan
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Nilai Bantuan
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right"></th>
                                    </tr>
                                </thead>            
                                <tbody v-if="data.bantuan" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="(item, index) in data.bantuan" :key="index">
                                        <td class="h-px w-px border">
                                            <div class="py-1 text-center">
                                                <span class="block text-sm font-base text-gray-800 dark:text-gray-200">
                                                    {{index+1}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 ps-2">
                                                <span class="block px-1 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{textAsalBantuan(item.asal_bantuan)}}
                                                </span>
                                                <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                    {{item.nama_pemberi}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block text-sm font-base text-gray-800 dark:text-gray-200">
                                                    {{dateFormat(item.tgl_bantuan)}}
                                                </span>
                                                <span class="block text-sm font-base text-gray-800 dark:text-gray-200">
                                                    {{item.tempat_bantuan}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block text-sm font-base text-gray-800 dark:text-gray-200">
                                                    {{item.item_bantuan}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 px-2 flex justify-between">
                                                <span class="block text-sm font-base text-gray-800 dark:text-gray-200">
                                                    Rp.
                                                </span>
                                                <span class="block text-sm font-base text-gray-800 dark:text-gray-200">
                                                    {{numberWithCommas(item.nilai_bantuan || 0)}},-
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 gap-x-1 flex justify-evenly items-center">
                                                <button 
                                                    class="inline-flex items-center gap-x-1.5 text-sm text-blue-600 decoration-2 hover:underline font-medium"
                                                    v-on:click="editBantuan(item)"
                                                >
                                                    Edit
                                                </button>
                                                <button 
                                                    class="inline-flex items-center gap-x-1.5 text-sm text-red-600 decoration-2 hover:underline font-medium"
                                                    v-on:click="hapusBantuan(item)"
                                                >
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-slate-800">
                                    <tr>
                                        <th colspan="4" scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Total Bantuan Diterima
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-between items-center gap-x-2">
                                                <span class="text-sm font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Rp.
                                                </span>
                                                <span class="text-sm font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    {{numberWithCommas(summaryOfBantuan) || 0}},-
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right"></th>
                                    </tr>
                                </tfoot>
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
                        <h2 class="block text-2xl font-bold text-gray-800 dark:text-gray-200">Tambah Data Bantuan</h2>
                    </div>

                    <div class="mt-5">
                        <!-- Form -->
                        <form @submit.prevent="submit" class="mt-6 space-y-5">
                            <div>
                                <InputLabel for="asal_bantuan" value="Asal Bantauan" />

                                <v-select
                                    class="mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required
                                    :options="asalBantuan"
                                    v-model="form.asal_bantuan"
                                >
                                    <template #search="{attributes, events}">
                                        <input
                                            class="vs__search"
                                            :required="!form.asal_bantuan"
                                            v-bind="attributes"
                                            v-on="events"
                                        />
                                    </template>
                                </v-select>
                                
                                <InputError class="mt-2" :message="form.errors.asal_bantuan" />
                            </div>

                            <template v-if="selBantuan">
                                <div>
                                    <InputLabel for="penduduk" value="Nama Orang Tua Asuh" />

                                    <v-select
                                        class="mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        label="nama"
                                        required
                                        :options="masterOrtu"
                                        v-model="form.penduduk"
                                    >
                                        <template #search="{attributes, events}">
                                            <input
                                                class="vs__search"
                                                :required="!form.penduduk"
                                                v-bind="attributes"
                                                v-on="events"
                                            />
                                        </template>
                                    </v-select>
                                    
                                    <InputError class="mt-2" :message="form.errors.penduduk" />
                                </div>
                            </template>

                            <template v-else>
                                <div>
                                    <InputLabel for="nama_pemberi" value="Pemberi Bantuan" />

                                    <TextInput
                                        id="nama_pemberi"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.nama_pemberi"
                                        required
                                        autofocus
                                    />

                                    <InputError class="mt-2" :message="form.errors.nama_pemberi" />
                                </div>
                            </template>

                            <div>
                                <InputLabel for="tempat_bantuan" value="Tempat Pemberian Bantuan" />

                                <TextInput
                                    id="tempat_bantuan"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.tempat_bantuan"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.tempat_bantuan" />
                            </div>
                            
                            <div>
                                <InputLabel for="tgl_bantuan" value="Tanggal Pemberian Bantuan" />

                                <TextInput
                                    id="tgl_bantuan"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.tgl_bantuan"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.tgl_bantuan" />
                            </div>

                            <div>
                                <InputLabel for="item_bantuan" value="Item Bantuan" />

                                <textarea
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm disabled:opacity-60 disabled:italic"
                                    v-model="form.item_bantuan"
                                    required
                                ></textarea>


                                <InputError class="mt-2" :message="form.errors.item_bantuan" />
                            </div>

                            <div>
                                <InputLabel for="nilai_bantuan" value="Nilai Bantuan (Rp.)" />

                                <TextInput
                                    id="nilai_bantuan"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.nilai_bantuan"
                                />

                                <InputError class="mt-2" :message="form.errors.nilai_bantuan" />
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
