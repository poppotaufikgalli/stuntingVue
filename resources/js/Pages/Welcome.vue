<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, watch, inject, onMounted } from 'vue';
import Maps from '@/Components/Maps.vue';
import ChartDashboard from '@/Components/ChartDashboard.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    nbalitakec: Object,
    nbalitakeckel: Object,
    petakelurahantpi: Object,
});

const refTheme = ref(null)

const changeTheme = (to) => {
    console.log("a=>", to)

    if(to == 'dark'){
        document.querySelector('html').classList.add('dark')
        refTheme.value = 'dark'
    }else{
        document.querySelector('html').classList.remove('dark')
        refTheme.value = 'light'
    }
}

const getPreferredScheme = () => window?.matchMedia?.('(prefers-color-scheme:dark)')?.matches ? 'dark' : 'light';

onMounted(() => {
    const curTheme = getPreferredScheme()
    changeTheme(curTheme)
})

</script>

<template>
    <Head title="Dashboard Kolaborasi Penanganan Stunting" />

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right flex justify-between gap-4">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Log in</Link
                >

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Register</Link
                >
            </template>
            <button type="button" class="group flex items-center text-gray-600 hover:text-blue-600 font-medium dark:text-gray-400 dark:hover:text-gray-500" v-on:click="changeTheme(refTheme == 'light' ? 'dark' : 'light')">
                <template v-if="refTheme == 'light' ">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#FFD700" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>    
                </template>
                <template  v-if="refTheme == 'dark' ">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FFD700" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 8a2 2 0 1 0 4 4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                </template>
            </button>
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center">
                <img src="img/logo-tpi.png" class="object-contain h-48 w-full">
            </div>
            
            <div class="flex justify-center">
                <h2 class="mt-6 text-3xl font-semibold text-gray-900 dark:text-white text-center">
                    Dashboard Kolaborasi Penanganan Stunting Kota Tanjungpinang
                </h2>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div v-for="(item, index) in nbalitakec" >
                        <div
                            class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex"
                        >
                            <div class="w-full">
                                <div class="flex flex-row justify-between">
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{item.nama_kec}}</h2>
                                    <h2 class="text-3xl font-semibold text-gray-900 dark:text-white number" :style="{'--to': item.jml}"></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8">
                    <Maps :petakelurahantpi="petakelurahantpi"/>
                </div>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8">
                    <ChartDashboard :nbalitakeckel="nbalitakeckel"/>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-2 bg-gray-800 py-6 md:pt-12">
        <div class="container px-4 mx-auto">
            <div class="md:flex md:-mx-4 md:items-center">
                <div class="md:flex-1 md:px-4 text-sm text-center md:text-left">
                    <p class="text-white">&copy; <strong>Diskominfo Kota Tanjungpinang</strong></p>
                </div>
                <div class="md:flex-1 md:px-4 text-center md:text-right">
                    <Link
                        :href="route('login')"
                        class="ml-4 font-semibold text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >Log in</Link
                    >
                    <Link
                        
                        class="ml-4 font-semibold text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >Dashboard</Link
                    >
                    <Link
                        
                        class="ml-4 font-semibold text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >Infografis</Link
                    >
                    <Link
                        
                        class="ml-4 font-semibold text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >Hubungi Kami</Link
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
