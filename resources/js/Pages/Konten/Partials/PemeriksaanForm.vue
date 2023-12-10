<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    masterEppgbm: {
        type: Object,
        default: []
    },
});

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Data Pengukuran</h2>
                <Link 
                    :href="route('eppgbm.create')"
                    class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Tambah Data
                </Link>
            </div>
        </header>

        <div class="mt-6">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col" class="p-3 text-center border border-right">
                            <div class="flex justify-center items-center gap-x-2">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                    No.
                                </span>
                            </div>
                        </th>
                        <th scope="col" class="p-3 text-center border border-right">
                            <div class="flex justify-center items-center gap-x-2">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                    Posyandu
                                </span>
                            </div>
                        </th>
                        <th scope="col" class="p-3 text-center border border-right">
                            <div class="flex justify-center items-center gap-x-2">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                    Tanggal Pemeriksaan
                                </span>
                            </div>
                        </th>
                        <th scope="col" class="p-3 text-center border border-right">
                            <div class="flex justify-center items-center gap-x-2">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                    Berat Badan (BB)
                                </span>
                            </div>
                        </th>
                        <th scope="col" class="p-3 text-center border border-right">
                            <div class="flex justify-center items-center gap-x-2">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                    Tinggi Badan (TB)
                                </span>
                            </div>
                        </th>
                        <th scope="col" class="p-3 text-center border border-right">
                            <div class="flex justify-center items-center gap-x-2">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                    BB/U
                                </span>
                            </div>
                        </th>
                        <th scope="col" class="p-3 text-center border border-right">
                            <div class="flex justify-center items-center gap-x-2">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                    TB/U
                                </span>
                            </div>
                        </th>
                        <th scope="col" class="p-3 text-center border border-right">
                            <div class="flex justify-center items-center gap-x-2">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                    BB/TB
                                </span>
                            </div>
                        </th>
                        <th scope="col" class="p-3 text-center border border-right"></th>
                    </tr>
                </thead>            
                <tbody v-if="masterEppgbm" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="(item, index) in masterEppgbm">
                        <td class="h-px w-px whitespace-nowrap border">
                            <div class="py-1 text-center">
                                <div class="flex items-center gap-x-3">
                                    <div class="grow">
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                            {{index+1}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="h-px w-px whitespace-nowrap border">
                            <div class="py-1 text-center">
                                <div class="flex items-center gap-x-3">
                                    <div class="grow">
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                            {{item.posyandu}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="h-px w-px whitespace-nowrap border">
                            <div class="py-1 text-center">
                                <div class="flex items-center gap-x-3">
                                    <div class="grow">
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                            {{item.tgl_pengukuran}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="h-px w-px whitespace-nowrap border">
                            <div class="py-1 text-center">
                                <div class="flex items-center gap-x-3">
                                    <div class="grow">
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                            {{item.berat}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="h-px w-px whitespace-nowrap border">
                            <div class="py-1 text-center">
                                <div class="flex items-center gap-x-3">
                                    <div class="grow">
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                            {{item.tinggi}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="h-px w-px whitespace-nowrap border">
                            <div class="py-1 text-center">
                                <div class="flex items-center gap-x-3">
                                    <div class="grow">
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                            {{item.bb_u}} | {{item.zz_bb_u}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="h-px w-px whitespace-nowrap border">
                            <div class="py-1 text-center">
                                <div class="flex items-center gap-x-3">
                                    <div class="grow">
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                            {{item.tb_u}} | {{item.zz_tb_u}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="h-px w-px whitespace-nowrap border">
                            <div class="py-1 text-center">
                                <div class="flex items-center gap-x-3">
                                    <div class="grow">
                                        <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                            {{item.bb_tb}} | {{item.zz_bb_tb}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="h-px w-px whitespace-nowrap border">
                            <div class="p-2 text-center">
                                <Link 
                                    :href="route('eppgbm.edit', { id: item.id })"
                                    class="inline-flex items-center gap-x-1.5 text-sm text-blue-600 decoration-2 hover:underline font-medium">
                                    Edit
                                </Link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>
