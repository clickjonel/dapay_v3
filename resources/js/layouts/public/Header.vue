<script setup lang="ts">
    import { Menu, X } from '@lucide/vue';
    import { ref } from 'vue';
    import PKLogo from '@/assets/logo_purokalusugan_car.png'

    const mobileOpen = ref(false);

    const navLinks = [
        { label: 'Programs', href: '#programs' },
        { label: 'Activities', href: '#activities' },
        { label: 'Dashboard', href: '#dashboard' },
    ];
    
</script>

<template>
    <header class="w-full sticky top-0 z-50">
        <div class="w-full bg-[#F7F4EC]/90 backdrop-blur-md">
            <div class="w-full max-w-7xl mx-auto h-20 px-5 md:px-8 flex justify-between items-center">
                <!-- Logo -->
                <a href="/" class="flex min-w-0 items-center gap-3 shrink-0">
                    <img :src="PKLogo" alt="Dap-ay" class="size-11 shrink-0 object-contain" />
                    <div class="min-w-0">
                        <p class="truncate font-serif font-semibold text-[19px] leading-none tracking-tight text-[#2B2825]">
                            Dap-ay
                        </p>
                        <p class="truncate text-[10px] font-semibold uppercase tracking-[0.16em] text-[#2B2825]/50 mt-1">
                            Purokalusugan Cordillera
                        </p>
                    </div>
                </a>

                <!-- Desktop nav -->
                <nav class="hidden md:flex items-center gap-9">
                    <a
                        v-for="link in navLinks"
                        :key="link.label"
                        :href="link.href"
                        class="relative text-[14px] font-medium text-[#2B2825]/75 hover:text-[#2B2825] transition-colors after:content-[''] after:absolute after:left-0 after:-bottom-1.5 after:h-[2px] after:w-full after:bg-[#C08A28] after:scale-x-0 after:origin-left after:transition-transform after:duration-200 hover:after:scale-x-100"
                    >
                        {{ link.label }}
                    </a>
                </nav>

                <!-- Desktop CTA -->
                <div class="hidden md:flex items-center gap-3 shrink-0">
                    <a
                        href="/login"
                        class="inline-flex items-center justify-center rounded-full bg-[#C08A28] px-6 py-2.5 text-[13px] font-semibold text-[#F7F4EC] tracking-wide hover:bg-[#A5731E] transition-colors"
                    >
                        Login
                    </a>
                </div>

                <!-- Mobile toggle -->
                <button
                    type="button"
                    class="md:hidden inline-flex items-center justify-center size-10 text-[#2B2825]"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen"
                    aria-label="Toggle menu"
                >
                    <Menu v-if="!mobileOpen" class="size-6" />
                    <X v-else class="size-6" />
                </button>
            </div>

            <!-- Mobile menu -->
            <Transition
                enter-active-class="transition-all duration-200 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition-all duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div v-if="mobileOpen" class="md:hidden w-full px-5 pb-6 flex flex-col gap-1">
                    <a
                        v-for="link in navLinks"
                        :key="link.label"
                        :href="link.href"
                        class="text-[15px] font-medium text-[#2B2825]/80 py-3 border-b border-[#E8E4D8]"
                        @click="mobileOpen = false"
                    >
                        {{ link.label }}
                    </a>
                    <a
                        href="/login"
                        class="mt-4 inline-flex items-center justify-center rounded-full bg-[#C08A28] px-6 py-3 text-[14px] font-semibold text-[#F7F4EC]"
                    >
                        Login
                    </a>
                </div>
            </Transition>
        </div>

        <!-- Woven-stripe signature accent -->
        <div class="w-full h-[3px] flex">
            <span class="flex-1 bg-[#1F3D2B]"></span>
            <span class="flex-1 bg-[#C08A28]"></span>
            <span class="flex-1 bg-[#8B3A2E]"></span>
            <span class="flex-1 bg-[#2B2825]"></span>
        </div>
    </header>
</template>