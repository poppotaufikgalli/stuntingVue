<script setup>
import { watch, ref, inject } from 'vue';
import { Head, Link, router, usePage, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime';
import _ from 'lodash';

const swal = inject('$swal');

const props = defineProps({
	listPenduduk: {
    	type: Object,
	    default: [],
	},
	jns: String,
	njns : String,
	croute: String,
});

const form = useForm({})

let search=ref('');
let perPage=ref(props.listPenduduk.per_page);

watch(search,(value)=>{
    //console.log(props.croute)
    router.get('/'+props.croute ,{perPage:perPage.value, search:value},{
        preserveState:true,
        replace:true    
    });
})

watch(perPage,(value)=>{
    router.get('/'+props.croute ,{perPage:value, search:search.value},{
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

function canDelete(item){
	if(props.croute == 'penduduk'){
		return item.balita.length == 0 ? 'text-red-600' : 'text-gray-700' ;
	}

	if(props.croute == 'asuh'){
		return item.asuh.length == 0 ? 'text-red-600' : 'text-gray-700' ;
	}	
}

function hapusData(item)
{
	let data = props.croute == 'asuh' ? item.asuh : item.balita
	let encid = props.croute == 'asuh' ? item.asuh.encid : item.encid
	//console.log(citem)
	if(data.length == 0){
		swal({
	        title: 'Yakin untuk Menghapus Data?',
	        text: "Data "+ props.njns + " an. "+ item.nama,
	        icon: 'warning',
	        showCancelButton: true,
	        cancelButtonText: "Batal",
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: 'Ya, hapus!'
	    }).then((result) => {
	        if (result.isConfirmed) {
	            //router.visit(route('pengguna.destroy', {id: item.encid}))
	            form.delete(route(props.croute + '.destroy', {id: encid}))
	        }  
	    })	
	}else{
		usePage().props.flash = {
	        type: 'error',
	        message: 'Data '+props.njns+' an. '+item.nama+' Tidak Bisa Dihapus. Jumlah anak '+props.croute+' terdaftar : '+data.length,
	    }
	}
}

</script>

<template>
	<Head :title="'Data ' + njns" />

	<AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Data {{njns}}</h2>
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
        								<div class="flex inline-flex gap-x-4">
        									<SelectInput
							                    id="perPage"
							                    v-model="perPage"
							                    :options="{5:5,10:10,20:20,50:50,100:100}"
							                    placeholder="Cari Nama / NIK"
							                />
							                <TextInput
							                    id="search"
							                    type="search"
							                    v-model="search"
							                    placeholder="Cari Nama / NIK"
							                />
							            </div>
						            </div>

            						<Link 
            							:href="route(croute +'.create')"
            							class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                  						<svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    						<path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  						</svg>
                  						Tambah Data
                					</Link>
  								</div>
		          				<!-- End Header -->

		          				<!-- Table -->
			          			<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
			            			<thead class="bg-gray-50 dark:bg-slate-800">
			              				<tr>
			                				<th scope="col" class="p-3 text-center">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						No
			                    					</span>
			                  					</div>
			                				</th>

			                				<th scope="col" class="p-3 text-center" width="10%">
			                  					<div class="flex justify-center items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						NIK
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="p-3 text-center" width="20%">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Nama
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="p-3 text-center" width="20%">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Pekerjaan
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="p-3 text-center" width="30%">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Alamat
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="p-3 text-center" width="20%">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Kecamatan/ Kelurahan
			                    					</span>
			                  					</div>
			                				</th>
			                				<template v-if="_.includes(['asuh', 'penduduk'], croute)">
			                					<th scope="col" class="p-3 text-center" width="10%">
				                  					<div class="flex items-center gap-x-2">
				                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
				                      						Jumlah Anak {{njns == 'Orang Tua Asuh' ? 'Asuh' : ''}}
				                    					</span>
				                  					</div>
				                				</th>
			                				</template>
			                				<template v-if="_.includes(['remaja'], croute)">
			                					<th scope="col" class="p-3 text-center" width="10%">
				                  					<div class="flex items-center gap-x-2">
				                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
				                      						TTD
				                    					</span>
				                  					</div>
				                				</th>
				                				<th scope="col" class="p-3 text-center" width="10%">
				                  					<div class="flex items-center gap-x-2">
				                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
				                      						LiLa
				                    					</span>
				                  					</div>
				                				</th>
			                				</template>
			                				<template v-if="_.includes(['catin'], croute)">
				                				<th scope="col" class="p-3 text-center" width="10%">
				                  					<div class="flex items-center gap-x-2">
				                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
				                      						LiLa
				                    					</span>
				                  					</div>
				                				</th>
			                				</template>
			                				<template v-if="_.includes(['hamil'], croute)">
				                				<th scope="col" class="p-3 text-center" width="10%">
				                  					<div class="flex items-center gap-x-2">
				                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
				                      						LiLa
				                    					</span>
				                  					</div>
				                				</th>
			                				</template>
			                				<th scope="col" class="p-3 text-center"></th>
			              				</tr>
			            			</thead>

			            			<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
			              				<tr v-for="(item, index) in listPenduduk.data">
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1 text-center">
			                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
	                        							{{ (listPenduduk.current_page -1) * listPenduduk.per_page + index +1 }}
	                        						</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1 text-center">
			                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
	                        							{{item.nik}}
	                        						</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1">
			                  						<div class="grow">
		                        						<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
			                        						{{item.nama}}
			                        					</span>
		                      						</div>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1">
			                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
			                    						{{item.pekerjaan}}
			                    					</span>
			                    					<span class="block text-sm font-base text-gray-800 dark:text-gray-400">
			                    						{{item.instansi}}
			                    					</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1">
			                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
			                    						{{item.alamat}}
			                    					</span>
			                    					<span class="block text-sm font-base text-gray-800 dark:text-gray-400">
			                    						{{item.rt ? "RT/RW " + item.rt+"/"+item.rw : ""}}
			                    					</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1">
			                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{item.nama_kec}} /<br> {{item.nama_desa_kel}}</span>
			                  					</div>
			                				</td>
			                				<template v-if="_.includes(['asuh', 'penduduk'], croute)">
				                				<td class="h-px w-px align-top">
				                  					<div class="p-1 text-center">
				                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
				                    						{{croute == 'asuh' ? item.asuh.length : item.balita.length }}
				                    					</span>
				                  					</div>
				                				</td>
				                			</template>
				                			<template v-if="_.includes(['remaja'], croute)">
				                				<td class="h-px w-px align-top">
				                  					<div class="p-1 text-center">
				                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
				                    						{{item.remaja_terakhir.ttd == 1 ? 'Ya' : 'Tidak' }}
				                    					</span>
				                  					</div>
				                				</td>
				                				<td class="h-px w-px align-top">
				                  					<div class="p-1 text-center">
				                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
				                    						{{item.remaja_terakhir.lila}}
				                    					</span>
				                  					</div>
				                				</td>
				                			</template>
				                			<template v-if="_.includes(['catin'], croute)">
				                				<td class="h-px w-px align-top">
				                  					<div class="p-1 text-center">
				                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
				                    						{{item.catin_terakhir.lila}}
				                    					</span>
				                  					</div>
				                				</td>
				                			</template>
				                			<template v-if="_.includes(['hamil'], croute)">
				                				<td class="h-px w-px align-top">
				                  					<div class="p-1 text-center">
				                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
				                    						{{item.hamil_terakhir.lila}}
				                    					</span>
				                  					</div>
				                				</td>
				                			</template>
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1">
			                  						<div class="flex justify-between gap-4">
			                  							<Link 
				                    						:href="route(croute +'.edit', {id: item.encid })"
				                    						class="inline-flex items-center gap-x-1.5 text-sm text-blue-600 decoration-2 hover:underline font-medium">
				                      						Edit
				                    					</Link>
				                    					<template v-if="_.includes(['asuh', 'penduduk'], croute)">
					                    					<button 
					                    						v-on:click="hapusData(item)"
					                    						class="inline-flex items-center gap-x-1.5 text-sm decoration-2 hover:underline font-medium" 
					                    						:class="canDelete(item)"
					                    					>
					                      						Hapus
					                    					</button>
					                    				</template>
					                    				<template v-else>
					                    					<span>&nbsp</span>
					                    				</template>
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
			                				{{listPenduduk.from}} Sampai {{listPenduduk.to}} Dari {{listPenduduk.total}} Data</span>
			              				</p>
			            			</div>
					            	<div>
			              				<div class="inline-flex gap-x-2">
					                		<template v-for="(v, k) in listPenduduk.links">
					                			<Link 
						                			:href="v.url == null ? '#' : v.url" 
						                			class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border font-medium shadow-sm align-middle focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:border-gray-700 dark:focus:ring-offset-gray-800"
	        										:class=" v.active == true ? 'text-gray-400 dark:text-gray-700 dark:hover:text-white-300 dark:bg-white dark:hover:bg-gray-50 bg-slate-900 hover:bg-slate-800' : 'text-gray-700 dark:text-gray-400 dark:hover:text-white bg-white hover:bg-gray-50 dark:bg-slate-900 dark:hover:bg-slate-800' "
						                		>
						                  			<span v-html="v.label" />
						                		</Link>
					                		</template>
			              				</div>
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