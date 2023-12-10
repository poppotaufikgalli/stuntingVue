<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import _ from 'lodash';

const props = defineProps({
    data: {
        type: Object,
        default: [],
    },
});

function defRoute(){
    if(_.size(props.data.sanitasi) > 0){
        return route('sanitasi.update', {'id' : props.data.sanitasi.id_penduduk})    
    }else{
        return route('sanitasi.store')
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
    id_penduduk: props.data.id,
    jamban_keluarga: props.data.sanitasi?.jamban_keluarga,
    status_jamban: props.data.sanitasi?.status_jamban,
    kebutuhan_bab: props.data.sanitasi?.kebutuhan_bab,
    tempat_sampah: props.data.sanitasi?.tempat_sampah,
    penanganan_sampah: props.data.sanitasi?.penanganan_sampah,
    air_limbah: props.data.sanitasi?.air_limbah,
    jarak_air_bersih: props.data.sanitasi?.jarak_air_bersih,
});

const submit = () => {
    form.post(defRoute(), {
        preserveScroll: true,
        onFinish: () => {
            console.log("update sanitasi")
        },
    });
};
</script>

<template>
    <section>
        <form @submit.prevent="submit" class="mt-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <InputLabel for="jamban_keluarga" value="Apakah Tersedia Jamban Keluarga" />

                    <SelectInput
                        id="jamban_keluarga"
                        class="mt-1 block w-full"
                        v-model="form.jamban_keluarga"
                        :options="{
                            'Ya':'Ya', 
                            'Tidak':'Tidak'
                        }"
                    />

                    <InputError class="mt-2" :message="form.errors.jamban_keluarga" />
                </div>

                <div>
                    <InputLabel for="status_jamban" value="Status Kepemilikan Jamban" />

                    <SelectInput
                        id="status_jamban"
                        class="mt-1 block w-full"
                        v-model="form.status_jamban"
                        :options="{'Milik Pribadi':'Milik Pribadi', 'Milik Umum': 'Milik Umum'}"
                    />

                    <InputError class="mt-2" :message="form.errors.status_jamban" />
                </div>

                <div>
                    <InputLabel for="kebutuhan_bab" value="Keluarga Memenuhi Kebutuhan BAB" />

                    <SelectInput
                        id="kebutuhan_bab"
                        class="mt-1 block w-full"
                        v-model="form.kebutuhan_bab"
                        :options="{'Milik Pribadi':'Milik Pribadi', 'Milik Umum': 'Milik Umum'}"
                    />

                    <InputError class="mt-2" :message="form.errors.kebutuhan_bab" />
                </div>

                <div>
                    <InputLabel for="tempat_sampah" value="Kondisi Tempat Sampah" />

                    <SelectInput
                        id="tempat_sampah"
                        class="mt-1 block w-full"
                        v-model="form.tempat_sampah"
                        :options="{'Ada':'Ada', 'Tidak Ada':'Tidak Ada'}"
                    />

                    <InputError class="mt-2" :message="form.errors.tempat_sampah" />
                </div>

                <div>
                    <InputLabel for="penanganan_sampah" value="Penanganan Sampah Rumah Tangga" />

                    <SelectInput
                        id="penanganan_sampah"
                        class="mt-1 block w-full"
                        v-model="form.penanganan_sampah"
                        :options="{'Diangkat petugas atau dibuang sendiri ke TPS':'Diangkat petugas atau dibuang sendiri ke TPS', 'Dibakar Sendiri':'Dibakar Sendiri'}"
                    />

                    <InputError class="mt-2" :message="form.errors.penanganan_sampah" />
                </div>

                <div>
                    <InputLabel for="air_limbah" value="Tempat Pembuangan Air Limbah Kamar Mandi" />

                    <SelectInput
                        id="air_limbah"
                        class="mt-1 block w-full"
                        v-model="form.air_limbah"
                        :options="{'Septic Tank':'Septic Tank', 'Sungai/Selokan':'Sungai/Selokan'}"
                    />

                    <InputError class="mt-2" :message="form.errors.air_limbah" />
                </div>

                <div>
                    <InputLabel for="jarak_air_bersih" value="Jarak Pembuangan Akhir Tinja atau Septic Tank Dengan Sumber Air Bersih" />

                    <SelectInput
                        id="jarak_air_bersih"
                        class="mt-1 block w-full"
                        v-model="form.jarak_air_bersih"
                        :options="{'Lebih dari 10 Meter': 'Lebih dari 10 Meter', 'Kurang dari 10 Meter': 'Kurang dari 10 Meter'}"
                    />

                    <InputError class="mt-2" :message="form.errors.jarak_air_bersih" />
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
