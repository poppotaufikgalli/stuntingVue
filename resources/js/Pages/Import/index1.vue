<script setup>
import { watch, ref, inject  } from 'vue';
import { Head, router, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime';

const swal = inject('$swal')

const props = defineProps({
    data: {
    	type: Object,
	    default: [],
	}
});

const form = useForm({})

let search=ref('');

watch(search,(value)=>{
    // console.log('Changed:'+value)
    router.get('/akses',{search:value},{
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

function hapusRole(item)
{
	if(item.users.length == 0){
		swal({
	        title: 'Yakin untuk Menghapus Data?',
	        text: "Data Group Hak Akses "+ item.nama,
	        icon: 'warning',
	        showCancelButton: true,
	        cancelButtonText: "Batal",
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: 'Ya, hapus!'
	    }).then((result) => {
	        if (result.isConfirmed) {
	            //router.visit(route('pengguna.destroy', {id: item.encid}))
	            form.delete(route('akses.destroy', {id: item.encid}))
	        }  
	    })	
	}else{
		usePage().props.flash = {
	        type: 'error',
	        message: 'Data Hak Akses Tidak Bisa Dihapus. Jumlah user terdaftar : '+item.users.length,
	    }
	}
}
</script>

<template>
	<Head title="Import Data" />

	<AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Import Data</h2>
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
						                    placeholder="Cari Nama"
						                />
                					</div>

                					<Link
                						:href="route('import.create')" 
                						class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800" href="#">
                  						<svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    						<path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  						</svg>
                  						Tambahkan
                					</Link>
								</div>
		          				<!-- End Header -->

		          				<!-- Table -->
			          			<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
			            			<thead class="bg-gray-50 dark:bg-slate-800">
			              				<tr>
			                				<th scope="col" class="pl-6 py-3 text-left">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Nama
			                    					</span>
			                  					</div>
			                				</th>

			                				<th scope="col" class="px-6 py-3 text-left">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Keterangan
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="px-6 py-3 text-left">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Jumlah User
			                    					</span>
			                  					</div>
			                				</th>

			                				<th scope="col" class="px-6 py-3 text-left">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Created At
			                    					</span>
			                  					</div>
			                				</th>

			                				<th scope="col" class="px-6 py-3 text-right"></th>
			              				</tr>
			            			</thead>

			            			<tbody v-if="data" class="divide-y divide-gray-200 dark:divide-gray-700">
			              				<tr v-for="(item, key) in data.data" :key="key">
			                				<td class="h-px w-px whitespace-nowrap">
			                  					<div class="pl-6 py-3">
			                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{item.filename}}</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-72 whitespace-nowrap">
			                  					<div class="px-6 py-3">
			                    					<span class="block text-sm text-gray-500">{{item.ket}}</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-72 whitespace-nowrap">
			                  					<div class="px-6 py-3">
			                    					<span class="block text-sm text-gray-500"></span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px whitespace-nowrap">
			                  					<div class="px-6 py-3">
			                    					<span class="text-sm text-gray-500">{{dateFormat(item.created_at)}}</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px whitespace-nowrap">
			                  					<div class="px-3 py-1.5">
			                  						<div class="flex justify-between gap-4">
				                    					<Link 
				                    						:href="route('import.edit', {id: item.encid})"
				                    						class="inline-flex items-center gap-x-1.5 text-sm text-blue-600 decoration-2 hover:underline font-medium" 
				                    					>
				                      						Edit
				                    					</Link>
				                    					<button 
				                    						v-on:click="hapusRole(item)"
				                    						class="inline-flex items-center gap-x-1.5 text-sm decoration-2 hover:underline font-medium" 
				                    						:class="item.length == 0 ? 'text-red-600' : 'text-gray-700' "
				                    					>
				                      						Hapus
				                    					</button>
				                    				</div>
			                  					</div>
			                				</td>
			              				</tr>
			            			</tbody>
			          			</table>
		          				<!-- End Table -->

			          			<!-- Footer -->
			          			<div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-gray-700">
			            			<div>
			              				<p class="text-sm text-gray-600 dark:text-gray-400">
			                				<span class="font-semibold text-gray-800 dark:text-gray-200">
			                					{{data.from}} Sampai {{data.to}} Dari {{data.total}} Data
			                				</span>
			              				</p>
			            			</div>
			            			<div class="inline-flex gap-x-2">
				                		<template v-for="(v, k) in data.links">
				                			<Link 
					                			:href="v.url == null ? '#' : v.url" 
					                			:class="{ 'active': v.active == true }"
					                			class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:bg-slate-900 dark:hover:bg-slate-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
					                  			<span v-html="v.label" />
					                		</Link>
				                		</template>
		              				</div>
		          				</div>
								<!-- End Footer -->
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