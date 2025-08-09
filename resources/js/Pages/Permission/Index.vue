<script setup>
    import App from '@/Layouts/App.vue';

    import {
        reactive,
        computed
    } from 'vue';

    import {
        router
    } from '@inertiajs/vue3';

    const props = defineProps({
        permissions: {
            type: Array,
            required: true
        },
        role: {
            type: Object,
            required: true
        }
    });

    const myPermissionIds = reactive(
        props.role.permissions.map(p => p.id)
    );

    const groupedPermissions = computed(() => {
        const groups = {};
        props.permissions.forEach(p => {
            if (!groups[p.scope]) {
                groups[p.scope] = [];
            }
            groups[p.scope].push({
                id: p.id,
                name: p.name
            });
        });
        return groups;
    });

    const selectedPermissions = reactive({});

    for (const scope in groupedPermissions.value) {
        groupedPermissions.value[scope].forEach(perm => {
            if (myPermissionIds.includes(perm.id)) {
                selectedPermissions[perm.id] = true;
            } else {
                selectedPermissions[perm.id] = false;
            }
        });
    }

    function toggleAll(scope, checked) {
        groupedPermissions.value[scope].forEach(perm => {
            selectedPermissions[perm.id] = checked;
        });
    }

    function isAllActive(scope) {
        return groupedPermissions.value[scope].every(perm => selectedPermissions[perm.id]);
    }

    let permissionId = null;
    let roleId = null;
    let status = true

    function savePermissions(perm) {
        if (selectedPermissions[perm.id]) {
            this.permissionId = perm.id;
            this.roleId = props.role.id;
            this.status = true;
        } else {
            this.permissionId = perm.id;
            this.roleId = props.role.id;
            this.status = false;
        }

        router.put('permission.store', {
            permission_id: permissionId,
            role_id: roleId,
            status: status
        }, {
            preserveScroll: true,
            onSuccess: () => {
                console.log('Permission updated successfully');
            },
            onError: (error) => {
                console.error('Error updating permission:', error);
            }
        });
    }
</script>
<template>
    <App>
        <div class="container p-6 overflow-x-auto">
            <h1 class="text-2xl font-bold mb-7">Setting Permission Role <span
                    class="text-green-400">{{ $page . props . role . name }}</span>
            </h1>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8">
                <div v-for="(permissions, scope) in groupedPermissions" :key="scope"
                    class="block rounded-md border border-gray-300 p-4 shadow-sm sm:p-6">
                    <!-- Header scope -->
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">{{ scope }}</h3>
                        <div class="flex items-center">
                            <p class="mr-2 text-sm">Activate All</p>
                            <label :for="'AcceptConditions' + scope"
                                class="relative block h-8 w-14 rounded-full bg-gray-300 transition-colors has-checked:bg-green-500">
                                <input type="checkbox" :id="'AcceptConditions' + scope" class="peer sr-only"
                                    :checked="isAllActive(scope)" @change="toggleAll(scope, $event.target.checked)" />
                                <span
                                    class="absolute inset-y-0 start-0 m-1 size-6 rounded-full bg-white transition-[inset-inline-start] peer-checked:start-6"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Daftar permission -->
                    <ul class="mt-4 space-y-2">
                        <li v-for="perm in permissions" :key="perm.id" class="flex items-center">
                            <label :for="'perm-' + perm.id"
                                class="relative block h-8 w-14 rounded-full bg-gray-300 transition-colors has-checked:bg-green-500">
                                <input @change="savePermissions(perm)" type="checkbox" :id="'perm-' + perm.id"
                                    class="peer sr-only" v-model="selectedPermissions[perm.id]" />
                                <span
                                    class="absolute inset-y-0 start-0 m-1 size-6 rounded-full bg-white transition-[inset-inline-start] peer-checked:start-6"></span>
                            </label>
                            <span class="ml-2">{{ perm . name }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </App>
</template>
