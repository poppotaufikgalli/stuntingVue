<script setup>
import { ref, computed, watch } from 'vue';
import { useForm, usePage, Link, router } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import ModalOrtu from '@/Components/ModalOrtu.vue';
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
    croute: String,
    njns: String,
});

const selID = ref('')

watch(selID,(value)=>{
    //console.log(value)
    router.visit('/'+props.croute+'/edit/' + value);
})

function defRoute(){
    //console.log(props.data)
    if(_.size(props.data) > 0){
        return route('penduduk.update', {'id' : props.data.id})    
    }else{
        return route('penduduk.store')
    }
}

async function reset(){
    form.reset()
    usePage().props.flash = {
        type: 'info',
        message: 'Data Telah Di Reset',
    }
}

const form = useForm({
    nik: props.data.nik,
    nama: props.data.nama,
    tgl_lahir: props.data.tgl_lahir,
    jns: props.croute,
    pekerjaan: props.data.pekerjaan,
    instansi: props.data.instansi,
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
        let result1 = _.keyBy(_.filter(props.masterKelurahan, {id_kecamatan : form.kec}), 'id');
        let result2 = _.mapValues(_.keyBy(result1, 'id'), 'nama')
        return result2;
    }
});
</script>

<template>
    <section>
        <div class="flex justify-end">
            <template v-if="croute != 'penduduk' && _.size(props.data) == 0">
                <ModalOrtu 
                    :data="masterOrtu"
                    v-model="selID"
                />
            </template>
        </div>
        <form @submit.prevent="form.post(defRoute())" class="mt-6 space-y-5">
            <div class="flex flex-row gap-4">
                <div class="basis-2/4">
                    <InputLabel for="nama" value="Nama" />

                    <div class="flex gap-2">
                        <div class="w-full">
                            <TextInput
                                id="nama"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.nama"
                                required
                            />
                        </div>
                    </div>

                    <InputError class="mt-2" :message="form.errors.nama" />
                </div>

                <div class="basis-1/4">
                    <InputLabel for="tgl_lahir" value="Tanggal Lahir" />

                    <TextInput
                        id="tgl_lahir"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.tgl_lahir"
                    />

                    <InputError class="mt-2" :message="form.errors.nama" />
                </div>

                <div class="basis-1/4">
                    <InputLabel for="nik" value="NIK" />

                    <TextInput
                        id="nik"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.nik"
                    />

                    <InputError class="mt-2" :message="form.errors.nik" />
                </div>
            </div>

            <div class="flex flex-row gap-4">
                <div class="basis-1/3">
                    <InputLabel for="pekerjaan" value="Pekerjaan" />

                    <TextInput
                        id="pekerjaan"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.pekerjaan"
                    />

                    <InputError class="mt-2" :message="form.errors.pekerjaan" />
                </div>

                <div class="basis-2/3">
                    <InputLabel for="instansi" value="Instansi" />

                    <TextInput
                        id="instansi"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.instansi"
                    />

                    <InputError class="mt-2" :message="form.errors.instansi" />
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
    </section>
</template>
