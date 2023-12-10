<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    data: {
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

function defRoute(){
    if(props.data != []){
        return route('balita.update', {'id' : props.data.id})    
    }else{
        return route('balita.store')
    }
}

const form = useForm({
    nik: props.data.nik,
    nama: props.data.nama,
    tgl_lahir: props.data.tgl_lahir,
    jk: props.data.jk,
    bb_lahir: props.data.bb_lahir,
    tb_lahir: props.data.tb_lahir,
    nik_ortu: props.data.nik_ortu,
    nama_ortu: props.data.nama_ortu,
    alamat: props.data.alamat,
    rt: props.data.rt,
    rw: props.data.rw,
    desa_kel: props.data.desa_kel,
    kec: props.data.kec,
    kab_kota: props.data.kab_kota,
    prov: props.data.prov,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Dasar</h2>
        </header>

        <form @submit.prevent="form.post(defRoute())" class="mt-6 space-y-5">
            <div class="flex flex-row gap-4">
                <div class="basis-1/4">
                    <InputLabel for="tgl_lahir" value="Tanggal Pengukuran" />

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
                    <InputLabel for="jk" value="Nama Posyandu" />

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
                    <InputLabel for="bb_lahir" value="Berat Badan" />

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
                    <InputLabel for="tb_lahir" value="Tinggi Badan" />

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

            <div class="flex flex-row">
                <div class="flex items-center gap-4">
                    <PrimaryButton :disabled="form.processing">Simpan</PrimaryButton>

                    <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                        <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Tersimpan.</p>
                    </Transition>
                </div>
            </div>
        </form>
    </section>
</template>
