<script setup>
import { ref, inject, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
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

const dataOrtuAsuh = computed({
    get(){
        return _.filter(props.masterOrtu, {'jns': 'asuh'})
    }
})

const form = useForm({
    penduduk: '',
    nik_balita: props.data.nik,
    tgl_asuh: '',
    status_asuh: 'A',
});

const refModal = ref();
const next = ref('create');
const selItemID = ref(0)
const modalVisible = ref(false)

function editAsuh(item){
    openModal()
    selItemID.value = item.id
    next.value = 'edit';

    form.penduduk = {
        'id': item.penduduk.id,
        'nama' : item.penduduk.nama
    }
    form.tgl_asuh = item.tgl_asuh
    form.status_asuh = item.status_asuh
}

function hapusAsuh(item){
    //console.log(item)
    swal({
        title: 'Yakin untuk Menghapus Data?',
        text: "Data Orang Tua Asuh "+ item.penduduk.nama,
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: "Batal",
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('asuh.destroy', {id: item.encid}))
        }  
    })
}

const submit = () => {
    if(next.value !== 'create'){
        form.post(route('asuh.update', {id: selItemID.value}), {
            onSuccess: () => {
                closeModal()
                form.reset()
                //selItemID.value = 0
            },
        });
    }else{
        //return route('eppgbm.store')
        form.post(route('asuh.store'), {
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
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Data Orang Tua Asuh</h2>
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
                                                    Nama Orang Tua Asuh
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Tanggal Mulai Asuh
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Status
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right"></th>
                                    </tr>
                                </thead>            
                                <tbody v-if="data.ortu_asuh" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="(item, index) in data.ortu_asuh">
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block text-sm font-base text-gray-800 dark:text-gray-200">
                                                    {{index+1}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                    {{item.penduduk.nama}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block text-sm font-base text-gray-800 dark:text-gray-200">
                                                    {{dateFormat(item.tgl_asuh)}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block text-sm font-base text-gray-800 dark:text-gray-200">
                                                    {{item.status_asuh == "A" ? "Aktif" : "Tidak Aktif"}}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-px border">
                                            <div class="px-1 gap-x-1 flex justify-evenly items-center">
                                                <button 
                                                    class="inline-flex items-center gap-x-1.5 text-sm text-blue-600 decoration-2 hover:underline font-medium"
                                                    v-on:click="editAsuh(item)"
                                                >
                                                    Edit
                                                </button>
                                                <button 
                                                    class="inline-flex items-center gap-x-1.5 text-sm text-red-600 decoration-2 hover:underline font-medium"
                                                    v-on:click="hapusAsuh(item)"
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
                        <h2 class="block text-2xl font-bold text-gray-800 dark:text-gray-200">Tambah Data Orang Tua Asuh</h2>
                    </div>

                    <div class="mt-5">
                        <!-- Form -->
                        <form @submit.prevent="submit" class="mt-6 space-y-5">
                            <div>
                                <InputLabel for="penduduk" value="Nama Orang Tua Asuh" />

                                <v-select
                                    class="mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    label="nama"
                                    required
                                    :options="dataOrtuAsuh"
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
                            
                            <div>
                                <InputLabel for="tgl_asuh" value="Tanggal Mulai Asuh" />

                                <TextInput
                                    id="tgl_asuh"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.tgl_asuh"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.tgl_asuh" />
                            </div>

                            <div>
                                <InputLabel for="status_asuh" value="Status Pengasuhan" />

                                <SelectInput
                                    id="status_asuh"
                                    class="mt-1 block w-full"
                                    v-model="form.status_asuh"
                                    :options="{
                                        'A' : 'Aktif',
                                        'T' : 'Tidak Aktif',
                                    }"
                                    required
                                />


                                <InputError class="mt-2" :message="form.errors.status_asuh" />
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
