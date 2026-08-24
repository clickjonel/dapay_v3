<script setup lang="ts">
    import { ChevronDown, User, Settings, LogOut } from '@lucide/vue';
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { notifySuccess, notifyError } from '@/composables/useNotify'

    const open = ref(false);
    const toggle = () => (open.value = !open.value);
    const close = () => (open.value = false);

    interface Props {
        name?: string;
        email?: string;
        initials?: string;
    }

    withDefaults(defineProps<Props>(), {
        name: 'Jane Doe',
        email: 'jane@acme.com',
        initials: 'JD',
    });

    const logout = () => {
        router.post('/logout',{}, {
            onSuccess: () => {
                notifySuccess('Logged Out Successfully')
            }
        });
    };
    
</script>

<template>
    <div class="relative">
        <button
            @click="toggle"
            class="flex items-center gap-2.5 py-1.5 pl-1.5 pr-2.5 transition-colors"
            :class="open ? 'bg-stone-100' : 'hover:bg-stone-100'"
        >
            <div class="flex size-7 items-center justify-center bg-[#3F4E2E] text-[11px] font-semibold tracking-wide text-white">
                {{ initials }}
            </div>
            <span class="hidden text-sm font-medium text-stone-800 sm:inline">{{ name }}</span>
            <ChevronDown class="hidden size-4 text-stone-400 transition-transform duration-500 sm:inline" :class="open ? 'rotate-180' : ''" />
        </button>

        <!-- Click-away catcher -->
        <button v-if="open" @click="close" class="fixed inset-0 z-30 cursor-default" />

        <!-- Popover -->
        <Transition enter-active-class="transition duration-500 ease-out" leave-active-class="transition duration-100 ease-in" enter-from-class="opacity-0 -translate-y-1" leave-to-class="opacity-0 -translate-y-1">
            <div v-if="open" class="absolute right-0 top-full z-40 mt-2 w-60 border border-stone-200 bg-white shadow-lg">
                <div class="border-b border-stone-100 px-4 py-3">
                    <p class="text-sm font-medium text-stone-900">{{ name }}</p>
                    <p class="text-xs text-stone-400">{{ email }}</p>
                </div>
                <div class="py-1">
                    <a href="#" class="flex items-center gap-2.5 px-4 py-2 text-sm text-stone-600 transition-colors hover:bg-stone-50 hover:text-stone-900">
                        <User class="size-4 text-stone-400" /> View profile
                    </a>
                    <a href="#" class="flex items-center gap-2.5 px-4 py-2 text-sm text-stone-600 transition-colors hover:bg-stone-50 hover:text-stone-900">
                        <Settings class="size-4 text-stone-400" /> Settings
                    </a>
                </div>
                <div class="border-t border-stone-100 py-1">
                    <button @click="logout" class="flex w-full items-center gap-2.5 px-4 py-2 text-sm text-[#C9722E] transition-colors hover:bg-stone-50">
                        <LogOut class="size-4" /> Log out
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>