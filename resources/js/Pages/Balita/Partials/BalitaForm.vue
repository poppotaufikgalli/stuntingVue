<script setup>
import { computed, ref } from 'vue';
import { Link, useForm, usePage, router } from '@inertiajs/vue3';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Modal from '@/Components/Modal.vue';
import _ from 'lodash';

const props = defineProps({
    data: {
        type: Object,
        default: []
    },
    masterOrtu: {
        type: Object,
        default: []
    },
    masterKecamatan: {
        type: Object,
        default: [],
    },
    masterKelurahan: {
        type: Object,
        default: [],
    },
});

const modalVisible = ref(false)

function defRoute(){
    if(_.size(props.data) > 0){
        return route('balita.update', {'id' : props.data.id})    
    }else{
        return route('balita.store')
    }
}

//console.log(props.masterOrtu)

async function reset(){
    form.reset()
    usePage().props.flash = {
        type: 'info',
        message: 'Data Telah Di Reset',
    }
    //console.log()
}

const form = useForm({
    nik: props.data.nik,
    nama: props.data.nama,
    tgl_lahir: props.data.tgl_lahir,
    jk: props.data.jk,
    bb_lahir: props.data.bb_lahir,
    tb_lahir: props.data.tb_lahir,
    id_ortu: props.data.id_ortu,
    nama_ortu: props.data.nama_ortu,
    alamat: props.data.alamat,
    rt: props.data.rt,
    rw: props.data.rw,
    desa_kel: props.data.desa_kel,
    kec: props.data.kec,
    kab_kota: props.data.kab_kota ?? "TANJUNGPINANG",
    prov: props.data.prov ?? "KEPULAUAN RIAU",
});

const dtKelurahan = computed({
    get() {
        //console.log(form.kec)
        let result1 = _.keyBy(_.filter(props.masterKelurahan, {id_kecamatan : form.kec}), 'id');
        let result2 = _.mapValues(_.keyBy(result1, 'id'), 'nama')
        //console.log(result2)
        return result2;
    }
});

//--start modal function--//

const refNamaOrtu = ref('')
const start = ref(0)

const sdataOrtu = computed({
    get(){
        var result = props.masterOrtu;
        if(refNamaOrtu.value != ""){
            result = result.filter(o => (o.nama.toLowerCase()).includes(refNamaOrtu.value.toLowerCase()));
            //console.log("A", refNamaOrtu.value)
            //console.log(result)
        }else{
            result = _.slice(result, start.value, start.value+10)    
        }
        
        return result
    }
})

function move(method){
    if(method == 'next'){
        if(props.masterOrtu.length > (start.value+10) ){
            start.value = start.value +10    
        }
        
    }else{
        if((start.value-10) >= 0 ){
            start.value = start.value -10
        }
    }
}

function setID(item){
    form.id_ortu = item.id;
    form.nama_ortu = item.nama
    closeModal()
}

const openModal = () => {
    modalVisible.value = true
}

const closeModal = () => {
    modalVisible.value = false
}

//--end modal function--//
</script>

<template>
    <section>
        <form @submit.prevent="form.post(defRoute())" class="mt-6 space-y-5">
            <div class="flex flex-row gap-4">
                <div class="basis-1/4">
                    <InputLabel for="nik" value="NIK" />

                    <TextInput
                        id="nik"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.nik"
                        required
                        autofocus
                        :disabled="_.size(props.data) > 0"
                    />

                    <InputError class="mt-2" :message="form.errors.nik" />
                </div>

                <div class="basis-2/4">
                    <InputLabel for="nama" value="Nama" />

                    <TextInput
                        id="nama"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.nama"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.nama" />
                </div>

                <div class="basis-1/4">
                    <InputLabel for="nama_ortu" value="Nama Orang Tua" />

                    <div class="flex gap-2">
                        <TextInput
                            id="nama_ortu"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.nama_ortu"
                            :disabled="true"
                        />

                        <PrimaryButton type="button" class="mt-1 gap-x-2" v-on:click="openModal">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                        </PrimaryButton>
                    </div>

                    <InputError class="mt-2" :message="form.errors.nama_ortu" />
                </div>
            </div>

            <div class="flex flex-row gap-4">
                <div class="basis-1/4">
                    <InputLabel for="tgl_lahir" value="Tanggal Lahir" />

                    <TextInput
                        id="tgl_lahir"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.tgl_lahir"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.tgl_lahir" />
                </div>

                <div class="basis-1/4">
                    <InputLabel for="jk" value="Jenis Kelamin" />

                    <SelectInput
                        id="jk"
                        class="mt-1 block w-full"
                        v-model="form.jk"
                        :options="{
                            'L' : 'Laki-laki',
                            'P' : 'Perempuan',
                        }"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.jk" />
                </div>

                <div class="basis-1/4">
                    <InputLabel for="bb_lahir" value="Berat Badan Lahir" />

                    <TextInput
                        id="bb_lahir"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.bb_lahir"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.bb_lahir" />
                </div>

                <div class="basis-1/4">
                    <InputLabel for="tb_lahir" value="Tinggi Badan Lahir" />

                    <TextInput
                        id="tb_lahir"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.tb_lahir"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.tb_lahir" />
                </div>
            </div>

            <div class="flex flex-row gap-4">

                <div class="basis-4/6">
                    <InputLabel for="alamat" value="Alamat" />

                    <TextInput
                        id="alamat"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.alamat"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.alamat" />
                </div>

                <div class="basis-1/6">
                    <InputLabel for="rt" value="RT" />

                    <TextInput
                        id="rt"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.rt"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.rt" />
                </div>

                <div class="basis-1/6">
                    <InputLabel for="rw" value="RW" />

                    <TextInput
                        id="rw"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.rw"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.rw" />
                </div>
            </div>

            <div class="flex flex-row gap-4">
                <div class="basis-1/4">
                    <InputLabel for="prov" value="Provinsi" />

                    <TextInput
                        id="prov"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.prov"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.prov" />
                </div>

                <div class="basis-1/4">
                    <InputLabel for="kab_kota" value="Kabupaten/Kota" />

                    <TextInput
                        id="kab_kota"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.kab_kota"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.kab_kota" />
                </div>

                <div class="basis-1/4">
                    <InputLabel for="kec" value="Kecamatan" />

                    <SelectInput
                        id="kec"
                        class="mt-1 block w-full"
                        v-model="form.kec"
                        :options="masterKecamatan"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.kec" />
                </div>

                <div class="basis-1/4">
                    <InputLabel for="desa_kel" value="Kelurahan" />

                    <SelectInput
                        id="desa_kel"
                        class="mt-1 block w-full"
                        v-model="form.desa_kel"
                        :options="dtKelurahan"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.desa_kel" />
                </div>
            </div>

            <div class="flex flex-row">
                <div class="flex items-center gap-4">
                    <PrimaryButton :disabled="form.processing">Simpan</PrimaryButton>
                    <SecondaryButton :disabled="form.processing" @click="reset">Reset</SecondaryButton>
                </div>
            </div>
        </form>
        <Modal :show="modalVisible" @close="closeModal">
            <div class="bg-white border border-gray-200 rounded-t-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="p-4 sm:p-7">
                    <div class="text-center">
                        <h2 class="block text-2xl font-bold text-gray-800 dark:text-gray-200">Cari Data Orang Tua</h2>
                    </div>

                    <div class="mt-5">
                        <div class="flex flex-row gap-4 mb-3">
                            <TextInput
                                id="kec"
                                type="search"
                                class="mt-1 block w-full"
                                v-model="refNamaOrtu"
                                placeholder="Cari Nama"
                            />

                            <Link 
                                :href="route('penduduk.create')"
                                class="inline-flex mt-1 py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800 hs-dropdown-toggle" data-hs-overlay="#hs-modal-penduduk"
                            >
                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                Tambah
                            </Link>
                        </div>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-slate-800">
                                <tr>
                                    <th scope="col" class="p-3 text-center w-1/5">
                                        <div class="flex justify-center items-center gap-x-2">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                NIK
                                            </span>
                                        </div>
                                    </th>
                                    <th scope="col" class="p-3 text-center w-2/5">
                                        <div class="flex justify-center items-center gap-x-2">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                Nama
                                            </span>
                                        </div>
                                    </th>
                                    <th scope="col" class="p-3 text-center w-3/5">
                                        <div class="flex justify-center items-center gap-x-2">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                                Alamat
                                            </span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="(item, key) in sdataOrtu" :key="key" v-on:click="setID(item)" class="hover:bg-gray-300 hover:dark:bg-gray-700">
                                    <td class="h-px w-px">
                                        <div class="p-1 text-center">
                                            <span class="block text-xs font-base text-gray-800 dark:text-gray-200">
                                                {{item.nik}}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="h-px w-px">
                                        <div class="p-1 text-left">
                                            <span class="block text-sm font-base text-gray-800 dark:text-gray-200">
                                                {{item.nama}}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="h-px w-px">
                                        <div class="p-1 text-left">
                                            <span class="max-w-[18rem] truncate whitespace-nowrap inline-block rounded-md text-xs font-medium text-gray-800 dark:text-gray-200">{{item.alamat}} {{item.rt !== "" ? "RT/RW " +item.rt +"/"+item.rw : ""}} {{item.desa_kel}} {{item.kec}}</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="py-4 grid gap-3 md:flex md:justify-end md:items-center border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-end gap-2">
                                <SecondaryButton @click="move('prev')">Sebelumnya</SecondaryButton>
                                <SecondaryButton @click="move('next')">Selanjutnya</SecondaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Modal>
    </section>
</template>
