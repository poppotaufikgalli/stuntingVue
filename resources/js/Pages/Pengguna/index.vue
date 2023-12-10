<script setup>
import { watch, ref, inject } from 'vue';
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
const user = usePage().props.auth.user;

let search=ref('');

watch(search,(value)=>{
    // console.log('Changed:'+value)
    router.get('/pengguna',{search:value},{
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

function hapusPengguna(item){
    if(item.id != user.id){
    	swal({
	        title: 'Yakin untuk Menghapus Data?',
	        text: "Data Pengguna an. "+ item.name,
	        icon: 'warning',
	        showCancelButton: true,
	        cancelButtonText: "Batal",
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: 'Ya, hapus!'
	    }).then((result) => {
	        if (result.isConfirmed) {
	            //router.visit(route('pengguna.destroy', {id: item.encid}))
	            form.delete(route('pengguna.destroy', {id: item.encid}))
	        }  
	    })	
    }else{
    	usePage().props.flash = {
	        type: 'error',
	        message: 'Tidak dapat menghapus diri sendiri',
	    }
    }
}
</script>

<template>
	<Head title="Data Pengguna" />

	<AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Data Pengguna</h2>
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
                						:href="route('pengguna.create')" 
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
			                				<th scope="col" class="px-6 py-3 text-left">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Nama
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="pl-6 lg:pl-3 xl:pl-0 pr-6 py-3 text-left">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Email
			                    					</span>
			                  					</div>
			                				</th>

			                				<th scope="col" class="px-6 py-3 text-left">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Hak Akses
			                    					</span>
			                  					</div>
			                				</th>

			                				<th scope="col" class="px-6 py-3 text-left">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Status
			                    					</span>
			                  					</div>
			                				</th>

			                				<th scope="col" class="px-6 py-3 text-left">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Created
			                    					</span>
			                  					</div>
			                				</th>

			                				<th scope="col" class="px-6 py-3 text-right"></th>
			              				</tr>
			            			</thead>

			            			<tbody v-if="data" class="divide-y divide-gray-200 dark:divide-gray-700">
			              				<tr v-for="(item, key) in data.data" :key="key">
			                				<td class="h-px w-px whitespace-nowrap">
			                  					<div class="px-6 py-3">
			                    					<div class="flex items-center gap-x-3">
			                      						<img class="inline-block h-[2.375rem] w-[2.375rem] rounded-full" :src="'/file/'+item.fileUpload" alt="Image Description" />
			                      						<div class="w-full">
			                        						<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{item.name}}</span>
			                      						</div>
			                    					</div>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px whitespace-nowrap">
			                  					<div class="px-6 py-3">
			                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{item.email}}</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px whitespace-nowrap">
			                  					<div class="px-6 py-3">
			                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{item.akses?.nama}}</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px whitespace-nowrap">
			                  					<div class="px-6 py-3">
			                  						<template v-if="item.status_user ==1">
			                  							<span class="inline-flex items-center gap-1.5 py-0.5 px-2 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
				                      						Aktif
				                    					</span>	
			                  						</template>
			                    					<template v-else>
			                  							<span class="inline-flex items-center gap-1.5 py-0.5 px-2 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
				                      						Tidak Aktif
				                    					</span>	
			                  						</template>
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
				                    						:href="route('pengguna.edit', {id: item.encid})"
				                    						class="inline-flex items-center gap-x-1.5 text-sm text-blue-600 decoration-2 hover:underline font-medium" 
				                    					>
				                      						Edit
				                    					</Link>
				                    					<button 
				                    						v-on:click="hapusPengguna(item)"
				                    						class="inline-flex items-center gap-x-1.5 text-sm decoration-2 hover:underline font-medium" 
				                    						:class="item.id != user.id ? 'text-red-600' : 'text-gray-700' "
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