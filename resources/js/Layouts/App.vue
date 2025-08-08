<template>
    <Navbar/>
    <main class="flex-1 p-4">
        <slot/>
    </main>
    <Footer/>
</template>
<script setup>
import Navbar from './Navbar.vue';
import Footer from './Footer.vue';
</script>