<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import _ from 'lodash';

const props = defineProps({
	nbalitakeckel :{
		type: Object,
		default: () => {}
	}
});

const seriesApex = computed({
	get(){
		var result = [{
			name 	: 'Pendek',
			data	: _.map(props.nbalitakeckel, 'Pendek'),
		},{
			name 	: 'Sangat Pendek',
			data 	: _.map(props.nbalitakeckel, 'Sangat_Pendek'),
		}];

		return result
	}
})

const optApex = computed({
	get(){
		var result = {
			chart 	: {
				stacked: true,
			},
			colors:['#FF6384', '#AA6384'],
			xaxis	: {
				categories: _.map(props.nbalitakeckel, 'nama_desa_kel'),
				labels:{
                    style:{
                        colors: '#808080'
                    }
                },
			},
			legend: {
                labels: {
                    colors: '#808080',
                },
            },
		};

		return result
	}
})

const series1Apex = computed({
	get(){
		var result = [{
			name 	: 'Pendek',
			data	: [_.sum(_.map(props.nbalitakeckel, function(o) {return (o.L_P) *1})), _.sum(_.map(props.nbalitakeckel, function(o) {return (o.P_P) *1}))],
		},{
			name 	: 'Sangat Pendek',
			data	: [_.sum(_.map(props.nbalitakeckel, function(o) {return (o.L_SP) *1})), _.sum(_.map(props.nbalitakeckel, function(o) {return (o.P_SP) *1}))],
		}];

		return result
	}
})

const opt1Apex = computed({
	get(){
		var result = {
			chart 	: {
				stacked: true,
			},
			colors:['#FF6384', '#AA6384'],
			xaxis	: {
				categories: ['Laki-laki','Perempuan'],
				labels:{
                    style:{
                        colors: '#808080'
                    }
                },
			},
			legend: {
                labels: {
                    colors: '#808080',
                },
            },
		};

		return result
	}
})

</script>

<template>
	<div class="flex flex-col sm:flex-row gap-8">
		<div class="basis-3/4">
			<div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none">
				<h2 class="text-xl font-semibold text-gray-900 dark:text-white text-center">Sebaran Data Stunting Per Kelurahan</h2>
				<div class="mt-6">
	  				<apexchart width="100%" height="500" type="bar" :options="optApex" :series="seriesApex"></apexchart>
	  			</div>
			</div>
		</div>
		<div class="basis-1/4">
			<div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none">
				<h2 class="text-xl font-semibold text-gray-900 dark:text-white text-center">Sebaran Per Jenis Kelamin</h2>
				<div class="mt-6">
	  				<apexchart width="100%" height="500" type="bar" :options="opt1Apex" :series="series1Apex"></apexchart>
	  			</div>
			</div>
		</div>
	</div>
</template>