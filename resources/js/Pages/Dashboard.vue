<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatsN1 from '@/Components/StatsN1.vue';
import Maps2 from '@/Components/Maps2.vue';
import { Head } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import _ from 'lodash';

const props = defineProps({
    dtBalita: {
        type: Object,
        default: [],
    },
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
    dtPenduduk: {
        type: Object,
        default: [],
    },
    dtKecamatan: {
        type: Object,
        default: [],
    },
    dtKelurahan: {
        type: Object,
        default: [],
    },
    dtBantuan:{
        type: Object,
        default: [],
    },
    petakelurahantpi:{
        type: Object,
        default: [],
    }
})

const balita_prev_stunting = computed({
    get(){
        //const result = _.filter(props.dtBalita, 'pengukuran_terakhir.tb_u')
        const result1 = _.filter(props.dtBalita, function(o) { 
            if ((o.pengukuran_terakhir.tb_u == 'Pendek' || o.pengukuran_terakhir.tb_u == 'Sangat Pendek') && (o.jk == 'L'))
                return o 
        });
        const result2 = _.filter(props.dtBalita, function(o) { 
            if ((o.pengukuran_terakhir.tb_u == 'Pendek' || o.pengukuran_terakhir.tb_u == 'Sangat Pendek') && (o.jk == 'P'))
                return o 
        });
        return [result1, result2]
    }
})

function getDataV(type, item) {
    let kec = item.id_kecamatan
    let desa_kel = item.id
    let result = _.filter(props.dtBalita, function(o) { 
        if (o.kec == kec && o.desa_kel == desa_kel && o.pengukuran_terakhir.tb_u == type) 
            return o 
        }
    ).length;
    return result
}

function NilaiBantuan(item){
    var result = _.sumBy(item, "nilai_bantuan")
    return "Rp. "+result.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

const series1Apex = computed({
    get(){
        var result = [
            _.sum(_.map(props.dtBalita, function(o) {return ((o.pengukuran_terakhir.tb_u == "Pendek" || o.pengukuran_terakhir.tb_u == 'Sangat Pendek') && o.jk == "L") *1})), 
            _.sum(_.map(props.dtBalita, function(o) {return ((o.pengukuran_terakhir.tb_u == "Pendek" || o.pengukuran_terakhir.tb_u == 'Sangat Pendek') && o.jk == "P") *1})), 
            ]

        return result
    }
})

const opt1Apex = computed({
    get(){
        var result = {
            labels: ['Laki-laki', 'Perempuan'],
            colors:['#4BC0C0', '#FF6384'],
            stroke: {
                show: false,
            },
            legend: {
                show: false,
            },
            dataLabels: {
                enabled: true,
                formatter: function(value, { seriesIndex, dataPointIndex, w }) {
                    return value.toFixed(0) + "% ("+w.config.series[seriesIndex] +")"
                },
            }
        };

        return result
    }
})

const series2Apex = computed({
    get(){
        var result = [{
            name    : 'Perempuan',
            data    : [_.sum(_.map(props.dtBalita, function(o) {return (o.pengukuran_terakhir.tb_u == "Pendek" && o.jk == "P") *1})), _.sum(_.map(props.dtBalita, function(o) {return (o.pengukuran_terakhir.tb_u == "Sangat Pendek" && o.jk == "P") *1}))],
        },{
            name    : 'Laki-laki',
            data    : [_.sum(_.map(props.dtBalita, function(o) {return (o.pengukuran_terakhir.tb_u == "Pendek" && o.jk == "L") *1})), _.sum(_.map(props.dtBalita, function(o) {return (o.pengukuran_terakhir.tb_u == "Sangat Pendek" && o.jk == "L") *1}))],
        }];

        return result
    }
})

const opt2Apex = computed({
    get(){
        var result = {
            /*chart   : {
                stacked: true,
            },*/
            colors:['#4BC0C0', '#FF6384'],
            xaxis   : {
                categories: ['Pendek', 'Sangat Pendek'],
                labels:{
                    style:{
                        colors: ['#808080', '#808080']
                    }
                },
            },
            yaxis   : {
                labels:{
                    style:{
                        colors: ['#808080', '#808080']
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
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <StatsN1 
            :balita="dtBalita.length" 
            :balitaStunting="balita_prev_stunting[0].length + balita_prev_stunting[1].length" 
            :dtRemaja="dtRemaja"
            :dtCatin="dtCatin"
            :dtHamil="dtHamil"
        />

        <div class="pt-8">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="scale-100 p-4 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex"
                >
                    <div class="w-full">
                        <h2 class="text-3xl font-semibold text-center text-gray-900 dark:text-white mb-4">Intervensi Penanganan Stunting</h2>
                        <div class="flex flex-row justify-evenly gap-4">
                            <div v-if="dtBantuan" v-for="(item, key) in dtBantuan" class="flex flex-col text-center pr-6" :key="key">
                                <h2 class="text-4xl font-semibold text-red-900 dark:text-white number" :style="{'--to': item.bantuan.length}"></h2>
                                <h2 class="text-sm font-base text-gray-500 dark:text-gray-400">{{item.nama}}</h2>
                                <h2 class="text-sm font-base text-gray-500 dark:text-gray-400">{{NilaiBantuan(item.bantuan)}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-8">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-5 gap-x-6">
                    <div class="col-span-3">
                        <Maps2 :petakelurahantpi="petakelurahantpi" :dtBalita="dtBalita"/>
                    </div>
                    <div class="col-span-2 w-full">
                        <div
                            class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex flex-col mb-4"
                        >
                            <h2 class="text-sm text-center uppercase tracking-wide font-medium text-gray-800 dark:text-gray-200 mb-4">Balita dengan Prevelensi Stunting Per Jenis Kelamin</h2>

                            
                            <div class="flex flex-row">
                                <apexchart width="100%" height="200" type="donut" :options="opt1Apex" :series="series1Apex"></apexchart>
                                <apexchart width="100%" height="200" type="bar" :options="opt2Apex" :series="series2Apex"></apexchart>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <template v-if="dtKecamatan" v-for="(kec, id) in dtKecamatan" :key="id">
                                <div
                                    class="scale-100 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none pb-2"
                                >
                                    <h2 class="text-sm uppercase tracking-wide font-medium text-gray-800 dark:text-gray-200 py-2 px-2">{{kec.nama}}</h2>
                                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="px-2 py-1 text-start text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">&nbsp;</th>
                                                <th scope="col" class="px-2 py-1 text-end text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Pendek</th>
                                                <th scope="col" class="px-2 py-1 text-end text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Sangat Pendek</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                                            <tr  v-if="kec.kelurahan" v-for="(kel, idkel) in kec.kelurahan" :key="idkel">
                                                <td class="px-2 py-1 whitespace-nowrap text-xs font-medium text-gray-800 dark:text-gray-200">{{kel.nama}}</td>
                                                <td class="px-2 py-1 whitespace-nowrap text-end text-xs text-gray-800 dark:text-gray-200">{{getDataV('Pendek', kel)}}</td>
                                                <td class="px-2 py-1 whitespace-nowrap text-end text-xs text-gray-800 dark:text-gray-200">{{getDataV('Sangat Pendek', kel)}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
