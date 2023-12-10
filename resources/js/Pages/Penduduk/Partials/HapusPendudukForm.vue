<script setup>
import { ref } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    data: {
        type: Object,
        default: [],
    },
    croute: String,
    njns: String,

})

const confirmingModal = ref(false);

const form = useForm({
    jns: '',
});

const confirmUserDeletion = () => {
    confirmingModal.value = true;
};

const deleteUser = () => {
    if(props.croute == 'penduduk'){
        form.delete(route('penduduk.destroy', {id: props.data.encid}), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => { console.log("Error")},
            onFinish: () => form.reset(),
        });    
    }

    if(props.croute == 'asuh'){
        form.post(route('penduduk.update', {id: props.data.id}), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal()
                //router.visit(props.croute)
            },
            onError: () => { console.log("Error")},
            onFinish: () => form.reset(),
        });    
    }
    
};

const closeModal = () => {
    confirmingModal.value = false;
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Menghapus Data {{njns}}</h2>
        </header>

        <template v-if="(njns == 'Orang Tua Asuh' && data.asuh.length <= 0)">
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, silakan unduh data atau informasi apa pun yang ingin Anda simpan.
            </p>
            <DangerButton @click="confirmUserDeletion" class="disabled">Hapus Data</DangerButton>
        </template>

        <template v-else-if="(njns !== 'Orang Tua Asuh' && data.balita.length <= 0)">
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, silakan unduh data atau informasi apa pun yang ingin Anda simpan.
            </p>
            <DangerButton @click="confirmUserDeletion" class="disabled">Hapus Data</DangerButton>
        </template>

        <template v-else>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Data belum dapat dihapus karena terkait dengan data Balita
            </p>
            <div class="flex flex-row">
                <div class="basis-1/2" v-if="data.balita.length > 0">
                    <ol class="list-none hover:list-disc">
                        <li v-for="(item, key) in data.balita" :key="key"
                            class="ms-6 text-sm text-gray-800 dark:text-gray-200"
                        >
                            <Link :href="route('balita.edit', {id: item.encid})">
                                {{item.nama}}
                            </Link>
                        </li>
                    </ol>
                </div>

                <div class="basis-1/2" v-if="data.asuh.length > 0">
                    <ol class="list-none hover:list-disc">
                        <li v-for="(item, key) in data.asuh" :key="key"
                            class="ms-6 text-sm text-gray-800 dark:text-gray-200"
                        >
                            <Link :href="route('balita.edit', {id: item.balita.encid})">
                                {{item.balita.nama}}
                            </Link>
                        </li>
                    </ol>
                </div>
            </div>
        </template>
        

        <Modal :show="confirmingModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Apakah Anda yakin ingin menghapus akun Anda?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan
                    masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Batal </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Hapus Data
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
