<template>
    <App>
        <div class="home">
            <h1>Welcome to the Home Page <span class="text-green-400">{{ ($page.props.auth.user) ? $page.props.auth.user.name : '' }}</span></h1>
        </div>
    </App>
</template>
<script setup>
    import App from '../Layouts/App.vue';
</script>
