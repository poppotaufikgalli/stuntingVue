<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, computed, watch, ref } from 'vue';
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime';
import _ from 'lodash';

const props = defineProps({
    listBalita: {
    	type: Object,
	    default: [],
	},
	listFilter: {
    	type: Object,
	    default: [],
	},
});

let search=ref('');
let perPage=ref(props.listBalita.per_page);
let sbbu=ref('')
let stbu=ref('')
let s_bb_tb=ref('')

watch(search,(value)=>{
    // console.log('Changed:'+value)
    router.get('/balita', {perPage:perPage.value, search:value, stbu: stbu.value},{
        preserveState:true,
        replace:true    
    });
})

watch(perPage,(value)=>{
    // console.log('Changed:'+value)
    router.get('/balita', {perPage:value, search:search.value, stbu: stbu.value, },{
        preserveState:true,
        replace:true    
    });
})

watch(stbu,(value)=>{
    // console.log('Changed:'+value)
    router.get('/balita', {perPage: perPage.value, search:search.value, stbu: value},{
        preserveState:true,
        replace:true    
    });
})

function dateFormat(dateString) {
	//console.log(dateString)
	if(dayjs(dateString, 'YYYY-MM-DD').isValid() && dateString != undefined){
		return dayjs(dateString, 'YYYY-MM-DD').format('DD-MM-YYYY')	
	}
	return ""
}

function getData(jns){
	return _.fromPairs(_.filter(props.listFilter, o => o.jns == jns).map(o => [o.caption, o.caption]));
}
//console.log(getData('tb_u'))
</script>

<template>
	<Head title="Data Balita" />

	<AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Data Balita</h2>
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
							                />
							                <TextInput
							                    id="search"
							                    type="search"
							                    v-model="search"
							                    placeholder="Cari Nama / NIK"
							                />
							                <SelectInput
							                    id="stbu"
							                    v-model="stbu"
							                    placeholder="Semua tb/u"
							                    :options="getData('tb_u')"
							                />
							            </div>
						            </div>
						            <div class="flex gap-x-2">
						            	<a 
	            							:href="route('balita.show')"
	            							class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-green-500 text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
	                  						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
 	 											<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
											</svg>
	                  						Export Data
	                					</a>
	            						<Link 
	            							:href="route('balita.create')"
	            							class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
	                  						<svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
	                    						<path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
	                  						</svg>
	                  						Tambah Data
	                					</Link>
	                				</div>
  								</div>
		          				<!-- End Header -->

		          				<!-- Table -->
			          			<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
			            			<thead class="bg-gray-50 dark:bg-slate-800">
			              				<tr>
			                				<th scope="col" class="p-3 text-center" width="5%">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						No
			                    					</span>
			                  					</div>
			                				</th>

			                				<th scope="col" class="p-3 text-center"  width="10%">
			                  					<div class="flex justify-center items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						NIK
			                    					</span>
			                  					</div>
			                				</th>

			                				<th scope="col" class="p-3 text-center" width="25%">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Nama<br>Tanggal Lahir / Jenis Kelamin<br>Nama Orang Tua
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="p-3 text-center"  width="10%">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Kecamatan<br>Kelurahan
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="p-3 text-center"  width="5%">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Tinggi
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="p-3 text-center"  width="5%">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Berat
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="p-3"  width="10%">
			                  					<div class="flex justify-center items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						TB/U
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="p-3"  width="10%">
			                  					<div class="flex justify-center items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						BB/U
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="p-3"  width="10%">
			                  					<div class="flex justify-center items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						TB/BB
			                    					</span>
			                  					</div>
			                				</th>
			                				<th scope="col" class="p-3"  width="15%">
			                  					<div class="flex items-center gap-x-2">
			                    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
			                      						Tanggal Pengukuran Terakhir
			                    					</span>
			                  					</div>
			                				</th>

			                				<th scope="col" class="p-3" width="5%"></th>
			              				</tr>
			            			</thead>

			            			<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
			              				<tr v-for="(item, index) in listBalita.data">
			                				<td class="h-px w-px whitespace-nowrap align-top">
			                  					<div class="py-1 text-center">
			                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
	                        							{{ (listBalita.current_page -1) * listBalita.per_page + index +1 }}
	                        						</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px whitespace-nowrap align-top">
			                  					<div class="p-1 ">
			                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
	                        							{{item.nik}}
	                        						</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px whitespace-nowrap align-top">
			                  					<div class="p-1">
			                  						<div class="grow">
		                        						<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{item.nama}}</span>
		                        						<span class="block text-sm text-gray-400">
		                        							{{dateFormat(item.tgl_lahir)}} / {{item.jk}}
		                        						</span>
		                        						<span class="block text-sm text-gray-400">
		                        							{{item.nama_ortu}}
		                        						</span>
		                      						</div>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px whitespace-nowrap align-top">
			                  					<div class="p-1">
			                    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">{{item.kecamatan.nama}} /<br> {{item.nama_desa_kel}}</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1">
			                    					<span class="block text-sm text-end font-semibold text-gray-800 dark:text-gray-200">{{item?.pengukuran_terakhir?.tinggi}}</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1">
			                    					<span class="block text-sm text-end font-semibold text-gray-800 dark:text-gray-200">{{item?.pengukuran_terakhir?.berat}}</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1">
			                    					<span 
			                    						class="block text-sm font-base text-center"
			                    						:class="item?.pengukuran_terakhir?.tb_u == 'Pendek' ? 'text-red-500' : (item?.pengukuran_terakhir?.tb_u == 'Sangat Pendek' ? 'text-red-700' : 'text-gray-800 dark:text-gray-200') "
			                    					>
			                    						{{item?.pengukuran_terakhir?.tb_u}}
			                    					</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1">
			                    					<span class="block text-sm text-center font-base text-gray-800 dark:text-gray-200">{{item?.pengukuran_terakhir?.bb_u}}</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1 text-right">
			                    					<span class="block text-sm text-center font-semibold text-gray-800 dark:text-gray-200">{{item?.pengukuran_terakhir?.bb_tb}}</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px align-top">
			                  					<div class="p-1 text-center">
			                    					<span class="text-sm text-gray-400">
			                    						{{dateFormat(item?.pengukuran_terakhir?.tgl_pengukuran)}}
			                    					</span>
			                  					</div>
			                				</td>
			                				<td class="h-px w-px whitespace-nowrap align-top">
			                  					<div class="p-1 px-2">
			                    					<Link 
			                    						:href="route('balita.edit', { id: item.encid })"
			                    						class="inline-flex items-center gap-x-1.5 text-sm text-blue-600 decoration-2 hover:underline font-medium">
			                      						Edit
			                    					</Link>
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
			                				{{listBalita.from}} Sampai {{listBalita.to}} Dari {{listBalita.total}} Data</span>
			              				</p>
			            			</div>
					            	<div>
			              				<div class="inline-flex gap-x-2">
					                		<template v-for="(v, k) in listBalita.links">
					                			<Link 
						                			:href="v.url == null ? '#' : v.url" 
						                			class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border font-medium shadow-sm align-middle focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:border-gray-700 dark:focus:ring-offset-gray-800"
	        										:class=" k == listBalita.current_page ? 'text-gray-100 dark:text-gray-700 dark:hover:text-white-300 dark:bg-white dark:hover:bg-gray-50 bg-slate-900 hover:bg-slate-800' : 'text-gray-700 dark:text-gray-400 dark:hover:text-white bg-white hover:bg-gray-50 dark:bg-slate-900 dark:hover:bg-slate-800' "
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