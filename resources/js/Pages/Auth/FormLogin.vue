<script setup>
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

</script>
<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="grid grid-cols-1 sm:w-full md:w-1/2 lg:w-1/3">
            <div class="block rounded-md border border-gray-300 p-4 shadow-sm sm:p-6">
                <div class="sm:flex sm:justify-between sm:gap-4 lg:gap-6">
                    <div class="mt-4 sm:mt-0">
                        <h3 class="text-2xl font-medium text-pretty text-gray-900">
                            System Role Base Access Control
                        </h3>
                        <p class="mt-1 text-sm text-gray-700 mb-5">By Arman Umar</p>
                    </div>
                </div>
                <p class="mb-3 text-red-500 text-center" v-if="form.errors.message">{{ form.errors.message }}</p>

                <form @submit.prevent="submit">

                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="email" class="w-full" v-model="form.email" />
                    <InputError class="mt-2" :message="form.errors.email" />

                    <InputLabel for="password" class="mt-5" value="Password" />
                    <TextInput id="password" type="password" class="w-full" v-model="form.password" />
                    <InputError class="mt-2" :message="form.errors.password" />

                    <PrimaryButton class="mt-5 px-5 py-3" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        Log in
                    </PrimaryButton>

                </form>
            </div>
        </div>
    </div>
</template>
