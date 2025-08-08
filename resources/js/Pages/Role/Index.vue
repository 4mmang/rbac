<script setup>
    import App from '@/Layouts/App.vue';
    import {
        Link,
        useForm
    } from '@inertiajs/vue3';
    import {
        ref
    } from 'vue';
    import Modal from '@/Components/Modal.vue';

    import TextInput from '@/Components/TextInput.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import InputError from '@/Components/InputError.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';

    const showModal = ref(false);   
    const form = useForm({
        name: ''
    });

    const submit = () => {
        form.post(route('role.store'), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            }
        });
    };
</script>
<template>
    <App>
        <div class="container p-6 shadow-lg py-8">
            <button v-if="$page.props.auth.permissions.includes('create role')" @click="showModal = true"
                class="inline-block mb-6 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Create New Role
            </button>
            <div class="overflow-x-auto">
                <table class="min-w-1/2 divide-y-2 divide-gray-200 border border-gray-200">
                    <thead class="ltr:text-left rtl:text-right">
                        <tr class="*:font-medium *:text-gray-900 text-center">
                            <th class="px-3 py-2 whitespace-nowrap">Name</th>
                            <th class="px-3 py-2 whitespace-nowrap">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200" v-for="role in $page.props.roles">
                        <tr class="*:text-gray-900 *:first:font-medium text-center">
                            <td class="px-3 py-2 whitespace-nowrap">{{ role.name }}</td>
                            <td class="px-3 py-2 whitespace-nowrap flex gap-2 justify-center">
                                <Link v-if="$page.props.auth.permissions.includes('edit role')" href="/role/create"
                                    class="inline-block bg-yellow-500 text-white px-4 py-1 rounded hover:bg-yellow-600">
                                Edit
                                </Link>
                                <Link v-if="$page.props.auth.permissions.includes('delete role')" href="/role/create"
                                    class="inline-block bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">
                                Delete
                                </Link>
                                <Link v-if="$page.props.auth.permissions.includes('edit permission')"
                                    href="/role/create"
                                    class="inline-block bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">
                                Permission
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </App>
    <Modal :show="showModal" @close="showModal = false" maxWidth="md">

        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Create New Role</h2>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <InputLabel for="role-name" value="Role Name" />
                    <TextInput id="role-name" type="text" v-model="form.name"
                        class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Enter role name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <div class="flex justify-end">
                    <PrimaryButton class="mt-2 px-5 py-3" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        Create Role
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
