<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import _ from 'lodash';

const props = defineProps(['data', 'modelValue']);

defineEmits(['update:modelValue']);

const modalVisible = ref(false)
const refNama = ref('')
const start = ref(0)

const sdataOrtu = computed({
    get(){
        var result = props.data;
        if(refNama.value != ""){
            result = result.filter(o => (o.nama.toLowerCase()).includes(refNama.value.toLowerCase()));
            //console.log("A", refNama.value)
            console.log(result)
        }else{
            result = _.slice(result, start.value, start.value+10)    
        }
        
        return result
    }
})

function move(method){
    if(method == 'next'){
        if(props.data.length > (start.value+10) ){
            start.value = start.value +10    
        }
        
    }else{
        if((start.value-10) >= 0 ){
            start.value = start.value -10
        }
    }
}

const openModal = () =>{
	modalVisible.value = true;
}

const closeModal = () =>{
	modalVisible.value = false;
}

</script>
<template>
	<div>
		<PrimaryButton type="button" v-on:click="openModal" class="mt-1 gap-2">
			Cari dari Data Penduduk 
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
            </svg>
        </PrimaryButton>
	</div>
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
                            v-model="refNama"
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
                    		<tr v-for="(item, key) in sdataOrtu" :key="key" class="hover:bg-gray-300 hover:dark:bg-gray-700" v-on:click="$emit('update:modelValue', item.encid)">
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
                            <SecondaryButton v-on:click="move('prev')">Sebelumnya</SecondaryButton>
                            <SecondaryButton v-on:click="move('next')">Selanjutnya</SecondaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 bg-gray-300 rounded-b-xl">
            <div class="flex justify-end">
                <SecondaryButton v-on:click="closeModal">Tutup</SecondaryButton>
            </div>                
        </div>
    </Modal>
</template>