<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import _ from "lodash";
import "leaflet/dist/leaflet.css";
import L from "leaflet";

const props = defineProps({
	petakelurahantpi: {
		type: Object,
		default: [],
	},
	dtBalita: {
		type: Object,
		default: [],
	}
});

const sebaran = (properties) => {
	var kd_wil = properties.kd_wil
	let P = _.filter(props.dtBalita, function(o){
		return o.kec + o.desa_kel == kd_wil && o.pengukuran_terakhir.tb_u == 'Pendek'
	})

	let SP = _.filter(props.dtBalita, function(o){
		return o.kec + o.desa_kel == kd_wil && o.pengukuran_terakhir.tb_u == 'Sangat Pendek'
	})
	//console.log(result)
	properties['sebaran'] = P.length + SP.length;
	properties['pendek'] = P.length;
	properties['sangat_pendek'] = SP.length;
}

onMounted(() => {
	var nulllayer = L.tileLayer('')
	var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '© OpenStreetMap'
	});

	var googleRoadMap = L.tileLayer('http://mt0.google.com/vt/lyrs=m&hl=en&x={x}&y={y}&z={z} ', {
		maxZoom: 19,
		attribution: '© Google Road Map'
	});

	var baseMaps = {
		"Google RoadMap": googleRoadMap,
		"OpenStreetMap": osm,
		"No Layer": nulllayer,
	};

	var map = new L.map('mapContainer', {
		center: new L.LatLng(0.916492,104.479722),
		zoom: 13,
		//scrollWheelZoom: false,
		layers: [nulllayer]
	});

	var layerControl = L.control.layers(baseMaps, null, {position: 'topleft'}).addTo(map);
	var info = L.control();

	info.onAdd = function (map) {
		this._div = L.DomUtil.create('div', 'rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-600 ring-1 ring-inset ring-red-600/10'); // create a div with a class "info"
		this.update();
		return this._div;
	};

	info.update = function (props) {
		this._div.innerHTML = '<h2 class="text-xl font-semibold text-gray-800">Data Stunting Kelurahan</h2>' +  (props ?
		'<div class="flex font-semibold text-gray-500"><div class="w-3/4">'+ props.KELURAHAN +'</div><div class="w-1/4">'+ props.sebaran +'</div></div>'+
		'<div class="flex font-semibold text-gray-500"><div class="w-3/4">Pendek</div><div class="w-1/4">'+ props.pendek +'</div></div>'+
		'<div class="flex font-semibold text-gray-500"><div class="w-3/4">Sangat Pendek</div><div class="w-1/4">'+ props.sangat_pendek +'</div></div>'
		: '<h4 class="text-sm text-gray-400">Tunjuk Kelurahan</h4>');
	};

	getPetaKelurahan(map, info);

	info.addTo(map)
});

function getColor2(d) {
	return d > 1000 ? '#800026' :
		d > 500  ? '#BD0026' :
		d > 200  ? '#E31A1C' :
		d > 100  ? '#FC4E2A' :
		d > 50   ? '#FD8D3C' :
		d > 20   ? '#FEB24C' :
		d > 10   ? '#FED976' :
		d > 0    ? '#ffeec2' :
		'#FFEDA0';
}

function getPetaKelurahan(map, info) {
	var odata = L.geoJSON(props.petakelurahantpi, {
		style: function(feature){
			var opacity = 0.6
			var kd_wil = feature.properties.KELURAHAN.replace(" ", "_")
			sebaran(feature.properties);
			var nsebaran = feature.properties.sebaran
			//feature.properties.sebaran = nsebaran;

			if (nsebaran === 0) {
				opacity = 0.3
			}
			var kec = feature.properties.KECAMATAN;
			return {
				weight: 2,
				//opacity: 1,
				//color: getColor(kec),
				className: 'focus:outline-none',
				color: getColor2(nsebaran),
				dashArray: '3',
				fillOpacity: opacity,
			}
		},
		onEachFeature: function (feature, layer) {
			var kd_wil = feature.properties.KELURAHAN.replace(" ", "_")
			var nsebaran = feature.properties.sebaran;

			if (nsebaran === 0) {
				nsebaran = 0
			}else{
				var label = L.marker(layer.getBounds().getCenter(), {
					icon: L.divIcon({
						iconSize: "auto",
						className: 'inline-flex items-center rounded-md bg-red-200 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10',
						html: nsebaran,
					})
				}).addTo(map);
			}

			//feature.properties.jml = nsebaran;

			layer.bindTooltip(feature.properties.KELURAHAN);

			layer.on({
				mouseover: function(e){
					info.update(layer.feature.properties);
				},
				mouseout: function(e){
					info.update();
				},
			});
		}
	}).addTo(map);

	odata.on('click', function(e) { 
		//var kelurahan = e.feature.properties.KELURAHAN;
		e.layer.options.stroke = false
		//modalDataBalita.show(e.layer.feature.properties)
		console.log(e)
	});
}
</script>

<template>
	<div class="scale-100 p-3 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex">
		<div id="mapContainer" style="width: 100vw; height: 100vh;"></div>
	</div>
</template>
