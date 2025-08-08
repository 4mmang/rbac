<template>
  <header class="bg-white shadow p-4 flex justify-between">
    <h1 class="text-lg font-bold">System RBAC (Laravel + Vue + Inertia + Tailwind)</h1>
    <nav v-if="$page.props.auth.user">
      <Link href="/dashboard" class="mr-4">Dashboard</Link>
      <Link v-if="$page.props.auth.permissions.includes('view role')" href="/role" class="mr-4">Manage Role</Link>
      <Link v-if="$page.props.auth.permissions.includes('view user')" href="/user" class="mr-4">Manage User</Link>
      <Link href="/logout" class="text-red-400">Logout</Link>
    </nav>
    <nav v-else>
      <Link href="/" class="mr-4">Home</Link>
      <Link href="/login" class="text-red-400">Login</Link>
    </nav>
  </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
