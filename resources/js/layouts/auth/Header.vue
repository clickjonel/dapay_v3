<script setup lang="ts">
    import { Menu, Bell } from '@lucide/vue';
    import { ref, inject, computed } from 'vue';
    import Sidebar from '@/layouts/auth/Sidebar.vue';
    import ProfileMenu from '@/layouts/auth/ProfileMenu.vue';

    interface User {
        name: string;
        email: string;
    }

    const sidebarOpen = ref(false);

    const user = inject<User>('user');

    const initials = computed(() => {
        if (!user?.name) return '';

        return user.name
            .trim()
            .split(/\s+/)
            .map((part) => part[0])
            .slice(0, 2)
            .join('')
            .toUpperCase();
    });
</script>

<template>
    <Sidebar v-model:open="sidebarOpen" />

    <!-- Header -->
    <header class="relative z-20 flex h-16 w-full items-center justify-between border-b border-stone-200 bg-white px-4 shadow-sm md:px-6">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = true" class="flex size-9 items-center justify-center text-stone-500 transition-colors hover:bg-[#3F4E2E] hover:text-white">
                <Menu class="size-5" />
            </button>
            <div>
                <h1 class="text-[15px] font-semibold leading-tight tracking-tight text-stone-900">Purokalusugan</h1>
                <p class="text-xs text-stone-400">Reporting System</p>
            </div>
        </div>

        <div class="flex items-center gap-1">
            <button class="relative flex size-9 items-center justify-center text-stone-500 transition-colors hover:bg-stone-100 hover:text-[#3F4E2E]">
                <Bell class="size-5" />
                <span class="absolute right-2 top-2 size-2 bg-[#C9722E]" />
            </button>

            <ProfileMenu class="ml-1" :name="user?.name" :email="user?.email" :initials="initials" />
        </div>
    </header>
</template>