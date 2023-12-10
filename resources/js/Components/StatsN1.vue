<script setup>
import { computed, ref, watch } from 'vue';
const props = defineProps({
	balita: Number,
	dtRemaja: {
		type: Object,
		default: [],
	},
	dtCatin: {
		type: Object,
		default: [],
	},
	dtHamil: {
		type: Object,
		default: [],
	},
    balitaStunting: Number,
})	
const persen1 = computed({
	get(){
		var result = (props.balitaStunting / props.balita)
		console.log(result)
		return result;
	}
})

const cttd = computed({
    get(){
        let result = _.filter(props.dtRemaja, function(o) { if (o.remaja_terakhir.ttd == 1) return o }).length;
        return result;
    }
})

const catin_lila = computed({
    get(){
        let result = _.filter(props.dtCatin, function(o) { if (o.catin_terakhir.lila_stunting == 'Ya') return o }).length;
        return result;
    }
})

const hamil_lila = computed({
    get(){
        //let jml = _.groupBy(props.dtBalita.pengukuran_terakhir, 'tb_u');
        let result = _.filter(props.dtHamil, function(o) { if (o.hamil_terakhir.lila_stunting == 'Ya') return o }).length;
        return result;
    }
})

</script>

<template>
	<!-- Card Section -->
	<div class="max-w-9xl mx-auto sm:px-6 lg:px-8 mt-6 mx-auto">
		<!-- Grid -->
		<div class="grid md:grid-cols-4 border border-gray-200 shadow-sm rounded-xl overflow-hidden dark:border-gray-700">
			<!-- Card -->
			<a class="block p-4 md:p-5 relative bg-white hover:bg-gray-50 before:absolute before:top-0 before:start-0 before:w-full before:h-px md:before:w-px md:before:h-full before:bg-gray-200 before:first:bg-transparent dark:bg-slate-900 dark:hover:bg-slate-800 dark:before:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
				<div class="flex md:grid lg:flex gap-y-3 gap-x-5">
					<svg class="flex-shrink-0 w-5 h-5 text-gray-400 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>

					<div class="grow">
						<p class="text-xs uppercase tracking-wide font-medium text-gray-800 dark:text-gray-200">
							Balita Stunting
						</p>
						<div class="flex flex-row justify-between items-center">
							<h3 class="mt-1 text-xl sm:text-2xl font-semibold text-red-600 dark:text-red-500 number" :style="{'--to': balitaStunting}"></h3>
							<span class="ms-1 inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-gray-200 text-gray-800 dark:bg-gray-800 dark:text-gray-200 number-d" :style="{'--percent': persen1}"></span>
						</div>
						<p class="text-sm text-gray-500">
							dari <span class="font-semibold text-blue-600 dark:text-blue-500 number" :style="{'--to': balita}"></span> Balita diukur
						</p>
					</div>
				</div>
			</a>
			<!-- End Card -->

			<!-- Card -->
			<a class="block p-4 md:p-5 relative bg-white hover:bg-gray-50 before:absolute before:top-0 before:start-0 before:w-full before:h-px md:before:w-px md:before:h-full before:bg-gray-200 before:first:bg-transparent dark:bg-slate-900 dark:hover:bg-slate-800 dark:before:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
				<div class="flex md:grid lg:flex gap-y-3 gap-x-5">
					<svg class="flex-shrink-0 w-5 h-5 text-gray-400 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4.5 12.5l8 -8a4.94 4.94 0 0 1 7 7l-8 8a4.94 4.94 0 0 1 -7 -7" /><path d="M8.5 8.5l7 7" /></svg>

					<div class="grow">
						<p class="text-xs uppercase tracking-wide font-medium text-gray-800 dark:text-gray-200">
							Pemberian TTD
						</p>
						<div class="flex flex-row justify-between items-center">
							<h3 class="mt-1 text-xl sm:text-2xl font-semibold text-blue-600 dark:text-blue-500 number" :style="{'--to': dtRemaja.length}"></h3>
							<span class="ms-1 inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-gray-200 text-gray-800 dark:bg-gray-800 dark:text-gray-200 number-d" :style="{'--percent': persen1}"></span>
						</div>
						<p class="text-sm text-gray-500">
							dari <span class="font-semibold text-blue-600 dark:text-blue-500 number" :style="{'--to': cttd}"></span> Remaja Putri diukur
						</p>
					</div>
				</div>
			</a>
			<!-- End Card -->

			<!-- Card -->
			<a class="block p-4 md:p-5 relative bg-white hover:bg-gray-50 before:absolute before:top-0 before:start-0 before:w-full before:h-px md:before:w-px md:before:h-full before:bg-gray-200 before:first:bg-transparent dark:bg-slate-900 dark:hover:bg-slate-800 dark:before:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
				<div class="flex md:grid lg:flex gap-y-3 gap-x-5">
					<svg class="flex-shrink-0 w-5 h-5 text-gray-400 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20.475 15.029a9 9 0 1 0 -7.962 5.957" /><path d="M16 19h6" /></svg>

					<div class="grow">
						<p class="text-xs uppercase tracking-wide font-medium text-gray-800 dark:text-gray-200">
							Lingkar Lengan Catin &lt;= 23,5
						</p>
						<div class="flex flex-row justify-between items-center">
							<h3 class="mt-1 text-xl sm:text-2xl font-semibold text-red-600 dark:text-red-500 number" :style="{'--to': dtCatin.length}"></h3>
							<span class="ms-1 inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-gray-200 text-gray-800 dark:bg-gray-800 dark:text-gray-200 number-d" :style="{'--percent': persen1}"></span>
						</div>
						<p class="text-sm text-gray-500">
							dari <span class="font-semibold text-blue-600 dark:text-blue-500 number" :style="{'--to': catin_lila}"></span> Calon Pengantin diukur
						</p>
					</div>
				</div>
			</a>
			<!-- End Card -->

			<!-- Card -->
			<a class="block p-4 md:p-5 relative bg-white hover:bg-gray-50 before:absolute before:top-0 before:start-0 before:w-full before:h-px md:before:w-px md:before:h-full before:bg-gray-200 before:first:bg-transparent dark:bg-slate-900 dark:hover:bg-slate-800 dark:before:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
				<div class="flex md:grid lg:flex gap-y-3 gap-x-5">
					<svg class="flex-shrink-0 w-5 h-5 text-gray-400 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12s2.545-5 7-5c4.454 0 7 5 7 5s-2.546 5-7 5c-4.455 0-7-5-7-5z"/><path d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/><path d="M21 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2"/><path d="M21 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2"/></svg>

					<div class="grow">
						<p class="text-xs uppercase tracking-wide font-medium text-gray-800 dark:text-gray-200">
							Lingkar Lengan Ibu Hamil &lt;= 23,5
						</p>
						<div class="flex flex-row justify-between items-center">
							<h3 class="mt-1 text-xl sm:text-2xl font-semibold text-red-600 dark:text-red-500 number" :style="{'--to': dtHamil.length}"></h3>
							<span class="ms-1 inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-gray-200 text-gray-800 dark:bg-gray-800 dark:text-gray-200 number-d" :style="{'--percent': persen1}"></span>
						</div>
						<p class="text-sm text-gray-500">
							dari <span class="font-semibold text-blue-600 dark:text-blue-500 number" :style="{'--to': hamil_lila}"></span> Ibu Hamil diukur
						</p>
					</div>
				</div>
			</a>
			<!-- End Card -->
		</div>
		<!-- End Grid -->
	</div>
	<!-- End Card Section -->
</template>