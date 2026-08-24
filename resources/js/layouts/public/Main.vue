<script setup lang="ts">
    import Header from '@/layouts/public/Header.vue';

</script>

<template>

    <div class="w-full min-h-screen flex flex-col justify-start items-start bg-[#F7F4EC]">
        <Header />

        <!-- Empty page body for now -->
        <main class="w-full flex-1">
            <slot />
        </main>
    </div>

</template>