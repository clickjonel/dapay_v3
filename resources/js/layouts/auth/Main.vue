<script setup lang="ts">
    import Header from '@/layouts/auth/Header.vue';
    import { usePage } from '@inertiajs/vue3';
    import { provide } from 'vue';

    const page = usePage();
    const user = page.props.auth.user

    //provide user data to child components
    provide('user',user)
    
</script>

<template>
    <div class="min-h-screen w-full bg-stone-50">
        <Header />
        
        <div class="w-full flex flex-col justify-start items-start md:p-4">
            <slot />
        </div>
    </div>
</template>