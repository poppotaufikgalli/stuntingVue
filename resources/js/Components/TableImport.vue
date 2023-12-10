<script setup>
import { ref, computed, reactive, watch, inject } from 'vue';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import _ from 'lodash';

const props = defineProps({
    head: {
    	type: Array,
	    default: [],
	},
	data:{
		type: Object,
	    default: [],	
	},
	actionLink:{
		'edit': String,
		'delete': String,
	},
	actionBtn:{
		'edit': Function,
		'delete': Function,
	}
});

console.log(props.data.data)

const columnHead = computed({
    get() {
        return props.head && (props.head).length > 0 
        	? props.head 
        	: _.keys(props.data.data[0]);
    },
});

function isDisplay(key){
	//console.log(columnHead.value)
	return _.includes(columnHead.value, key);
}

function displayItem(items, item)
{
	return _.get(items, item)
}
</script>

<template>
	<div class="flex flex-col">
  		<div class="-m-1.5 overflow-x-auto">
    		<div class="p-1.5 min-w-full inline-block align-middle">
      			<div class="overflow-hidden">
					<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
						<thead class="bg-gray-50 dark:bg-slate-800">
							<tr>
								<th scope="col" class="p-3 text-center" colspan="2">
				  					<div class="flex justify-center items-center gap-x-2">
				    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
				      						Aksi
				    					</span>
				  					</div>
								</th>

								<th v-for="(item, index) in columnHead" :key="index" scope="col" class="p-3 text-center">
				  					<div class="flex justify-center items-center gap-x-2">
				    					<span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
				      						{{item}}
				    					</span>
				  					</div>
								</th>	
							</tr>
						</thead>

						<tbody v-if="true" v-for="(items, index) in data.data" class="divide-y divide-gray-200 dark:divide-gray-700">
							<tr>
								<td class="h-px w-px whitespace-nowrap">
				  					<div class="p-2 flex justify-center items-center gap-x-2">
				    					<Link 
				    						v-for="(item, key) in actionLink"
				    						:href="item" :data="{id : items.encid ?? items.id}"
				    						class="inline-flex items-center gap-x-1.5 text-sm decoration-2 hover:underline font-medium capitalize"
				    						:class="key == 'delete' ? 'text-red-600' : 'text-blue-600' "
				    					>
				      						{{key}}
				    					</Link>
				  					</div>
								</td>
								<td class="h-px w-px whitespace-nowrap">
				  					<div class="p-2 flex justify-center items-center gap-x-2">
				    					<PrimaryButton v-for="(item, key) in actionBtn" v-on:click="item(index)">
				      						{{key}}
				    					</PrimaryButton>
				  					</div>
								</td>
								<template v-for="(item, key) in columnHead" :key="key">
									<td class="h-px w-px whitespace-nowrap">
					  					<div class="p-2" :class="_.isNumber(displayItem(items, item)) ? 'text-center' : 'text-left' ">
					    					<span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
												{{displayItem(items, item)}}
											</span>
					  					</div>
									</td>	
								</template>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
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
		<div>
			<div class="inline-flex gap-x-2">
	    		<template v-for="(v, k) in data.links">
	    			<Link 
	        			:href="v.url == null ? '#' : v.url" 
	        			class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border font-medium shadow-sm align-middle focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:border-gray-700 dark:focus:ring-offset-gray-800"
	        			:class=" k == data.current_page ? 'text-gray-100 dark:text-gray-700 dark:hover:text-white-300 dark:bg-white dark:hover:bg-gray-50 bg-slate-900 hover:bg-slate-800' : 'text-gray-700 dark:text-gray-400 dark:hover:text-white bg-white hover:bg-gray-50 dark:bg-slate-900 dark:hover:bg-slate-800' "
	        			>
	          			<span v-html="v.label" />
	        		</Link>
	    		</template>
			</div>
		</div>
	</div>
	<!-- End Footer -->
</template>
	