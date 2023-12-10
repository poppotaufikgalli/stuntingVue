<script setup>
import { ref, inject, watch, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
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
    masterBalita: {
        type: Object,
        default: []
    },
    croute: String,
});

const next = ref('create');

const selID = ref('')
const modalBalitaAsuhShow = ref(false)

const dataBalita = computed({
    get(){
        if(props.croute == 'penduduk'){
            return props.data.balita
        }

        if(props.croute == 'asuh'){
            return props.data.asuh
        }

        return []
    }
})

function editAsuh(item){
    openModal()
    next.value = 'edit';
    form.id = item.id
    form.balita = item.balita
    form.tgl_asuh = item.tgl_asuh
    form.status_asuh = item.status_asuh
}

function hapusAsuh(item){
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
            form.delete(route('asuh.destroy', {id: item.encid}))
        }  
    })
}

const form = useForm({
    nik: '',
    nik_balita: '',
    id_ortu: props.data.id,
    nama_ortu: props.data.nama ?? '',
    balita: '',
    tgl_asuh: '',
    status_asuh: 'A',
    penduduk: props.data,
})

const submit = () => {
    if(next.value !== 'create'){
        if(props.croute == 'asuh'){
            form.nik_balita = form.balita.nik
            form.post(route('asuh.update', {id : form.id}), {
                 onSuccess: () => {
                    form.reset()
                    closeModal() 
                },
            });
        } 
    }else{
        if(props.croute == 'penduduk'){
            let id = form.balita.id
            form.nik = form.balita.nik
            form.post(route('balita.update', {id: id}), {
                onSuccess: () => {
                    form.reset()
                    closeModal() 
                },
            });
        }
        if(props.croute == 'asuh'){
            form.nik_balita = form.balita.nik
            form.post(route('asuh.store'), {
                 onSuccess: () => {
                    form.reset()
                    closeModal() 
                },
            });
        }    
    }
    
}

function dateFormat(dateString) {
    if(dayjs(dateString, 'YYYY-MM-DD').isValid()){
        return dayjs(dateString, 'YYYY-MM-DD').format('DD-MM-YYYY') 
    }
    return ""
}

//modal do

const modalVisible = ref(false)

const openModal = () => {
    modalVisible.value = true
}
const closeModal = () => {
    modalVisible.value = false
    next.value = 'create';
    form.reset()
}
//end modal do

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
        <div class="flex justify-end gap-x-2">
            <PrimaryButton type="button" v-on:click="openModal" class="mt-1 gap-2">
                Cari Data
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                </svg>
            </PrimaryButton>
        </div>
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
                                                    NIK
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Nama / Tgl Lahir
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-3 text-center border border-right">
                                            <div class="flex justify-center items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                    Umur
                                                </span>
                                            </div>
                                        </th>
                                        <template v-if="croute == 'penduduk' ">
                                            <th scope="col" class="p-3 text-center border border-right">
                                                <div class="flex justify-center items-center gap-x-2">
                                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                        Berat Badan
                                                    </span>
                                                </div>
                                            </th>
                                            <th scope="col" class="p-3 text-center border border-right">
                                                <div class="flex justify-center items-center gap-x-2">
                                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                        Tinggi Badan
                                                    </span>
                                                </div>
                                            </th>
                                            <th scope="col" class="p-3 text-center border border-right">
                                                <div class="flex justify-center items-center gap-x-2">
                                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                        Tanggal Pengukuran
                                                    </span>
                                                </div>
                                            </th>
                                        </template>
                                        <template v-else>
                                            <th scope="col" class="p-3 text-center border border-right">
                                                <div class="flex justify-center items-center gap-x-2">
                                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                        Mulai Asuh
                                                    </span>
                                                </div>
                                            </th>
                                            <th scope="col" class="p-3 text-center border border-right">
                                                <div class="flex justify-center items-center gap-x-2">
                                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                        Status Asuh
                                                    </span>
                                                </div>
                                            </th>
                                            <th scope="col" class="p-3 text-center border border-right"></th>
                                        </template>
                                    </tr>
                                </thead>            
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-if="dataBalita" v-for="(item, index) in dataBalita">
                                        <td class="h-px w-px border align-top">
                                            <div class="py-1 text-center">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{index+1}}
                                                </span>
                                            </div>
                                        </td>
                                        <template v-if="croute == 'penduduk' ">
                                            <td class="h-px w-px border align-top">
                                                <div class="py-1 text-center">
                                                    <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                        {{item.nik}}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="h-px w-px border align-top">
                                                <div class="py-1 px-2">
                                                    <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                        {{item.nama}}
                                                    </span>
                                                    <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                        {{dateFormat(item.tgl_lahir)}}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="h-px w-px border align-top">
                                                <div class="py-1 text-center">
                                                    <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                        {{item.umur}}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="h-px w-px border align-top">
                                                <div class="py-1 text-center">
                                                    <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                        {{item.berat}}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="h-px w-px border align-top">
                                                <div class="py-1 text-center">
                                                    <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                        {{item.tinggi}}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="h-px w-px border align-top">
                                                <div class="py-1 text-center">
                                                    <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                        {{dateFormat(item.tgl_pengukuran)}}
                                                    </span>
                                                </div>
                                            </td>
                                        </template>
                                        <template v-else>
                                            <td class="h-px w-px border align-top">
                                                <div class="py-1 text-center">
                                                    <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                        {{item.balita.nik}}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="h-px w-px border align-top">
                                                <div class="py-1 px-2">
                                                    <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                        {{item.balita.nama}}
                                                    </span>
                                                    <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                        {{dateFormat(item.balita.tgl_lahir)}}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="h-px w-px border align-top">
                                                <div class="py-1 text-center">
                                                    <span class="block px-1 text-sm font-base text-gray-800 dark:text-gray-200">
                                                        {{item.balita.umur}}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="h-px w-px border align-top">
                                                <div class="py-1 text-center">
                                                    <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
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
                                            <td class="h-px w-px border align-top">
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
                                        </template>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <template>
            <Modal :show="modalVisible" @close="closeModal">
                <div class="bg-white border border-gray-200 rounded-t-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <div class="p-4 sm:p-7">
                        <div class="text-center">
                            <h2 class="block text-2xl font-bold text-gray-800 dark:text-gray-200">Tambah Data Balita</h2>
                        </div>

                        <div class="mt-5">
                            <!-- Form -->
                            <form @submit.prevent="submit" class="mt-6 space-y-5">
                                <div>
                                    <InputLabel for="balita" value="Nama Balita" />

                                    <v-select :options="masterBalita" label="nama" :append-to-body="true" v-model="form.balita" required>
                                        <template #search="{attributes, events}">
                                            <input
                                                class="vs__search"
                                                :required="!form.balita"
                                                v-bind="attributes"
                                                v-on="events"
                                            />
                                        </template>
                                        <template #option="{ nama, tgl_lahir }">
                                            <div class="flex justify-between">
                                                <span>{{ nama }}</span><em>{{ dateFormat(tgl_lahir) }}</em>
                                            </div>
                                        </template>
                                    </v-select>
                                    
                                    <InputError class="mt-2" :message="form.errors.balita" />
                                </div>

                                <template v-if="croute == 'asuh'">
                                    <div>
                                        <InputLabel for="tgl_asuh" value="Tanggal Asuh" />

                                        <TextInput
                                            id="tgl_asuh"
                                            type="date"
                                            class="mt-1 block w-full"
                                            v-model="form.tgl_asuh"
                                            required
                                            
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
                                </template>

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
        </template>
    </section>
</template>
