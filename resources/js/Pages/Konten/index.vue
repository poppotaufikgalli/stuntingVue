<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TableStunting from '@/Components/TableStunting.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, computed, watch, ref } from 'vue';
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime';
import _ from 'lodash';

const props = defineProps({
    data: {
    	type: Object,
	    default: [],
	}
});

let search=ref('');

watch(search,(value)=>{
    // console.log('Changed:'+value)
    router.get('/konten',{search:value},{
        preserveState:true,
        replace:true    
    });
})

function dateFormat(dateString) {
	if(dayjs(dateString, 'YYYY-MM-DD').isValid()){
		return dayjs(dateString, 'YYYY-MM-DD').format('DD-MM-YYYY')	
	}
	return ""
}

</script>

<template>
	<Head title="Konten" />

	<AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Konten</h2>
        </template>

        <div class="py-12">
        	<div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        		<!-- Table Section -->
				<!-- Card -->
				<div class="flex flex-col">
					<div class="-m-1.5 overflow-x-auto">
  						<div class="p-1.5 min-w-full inline-block align-middle">
    						<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-slate-900 dark:border-gray-700">
      							<!-- Header -->
      							<div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-gray-700">
        							<div>
						                <TextInput
						                    id="search"
						                    type="search"
						                    class="mt-1 block w-full"
						                    v-model="search"
						                    required
						                    placeholder="Cari Nama / NIK"
						                />
						            </div>

            						<Link 
            							:href="route('konten.create')"
            							class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                  						<svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    						<path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  						</svg>
                  						Tambah Data
                					</Link>
  								</div>
		          				<!-- End Header -->

			          			<!-- Table -->
			          			<TableStunting 
			          				:head="[
			          					'gambar',
			          					'judul',
			          					'isi',
			          					'kategori'
			          				]"
			          				:data="data"
			          				:actionBtn="{
			          					'edit' : 'konten/edit',
			          					'delete' : 'konten/delete'
			          				}"
			          			/>
			          			<!-- End Table -->
    						</div>
  						</div>
					</div>
				</div>
				<!-- End Card -->
			<!-- End Table Section -->
			</div>
        </div>
    </AuthenticatedLayout>
</template>