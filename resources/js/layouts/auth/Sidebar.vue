<script setup lang="ts">
    import { X, ChevronDown } from '@lucide/vue';
    import { Link, usePage } from '@inertiajs/vue3';
    import { computed } from 'vue';
    import DapayLogo from '@/assets/logo_purokalusugan_car.png';
    import { navigationGroups, navigate } from '@/lib/useNavigation';

    const open = defineModel<boolean>('open', { default: false });

    const page = usePage();
    const currentUrl = computed(() => page.url);
    const accessLevel = Number(page.props?.auth?.user?.access_level);

    const isChildActive = (route: string) => currentUrl.value === route;
    const isGroupActive = (children: { route: string }[]) => children.some((c) => isChildActive(c.route));
    const filteredGroups = navigationGroups.filter(group => {
        return group.accessLevels ? group.accessLevels.includes(accessLevel) : true;
    });

</script>

<template>
    <!-- Overlay -->
    <Transition enter-active-class="transition-opacity duration-500" leave-active-class="transition-opacity duration-500" enter-from-class="opacity-0" leave-to-class="opacity-0">
        <div v-if="open" @click="open = false" class="fixed inset-0 z-30 bg-stone-900/40 backdrop-blur-[2px]" />
    </Transition>

    <!-- Sidebar -->
    <aside
        class="fixed left-0 top-0 z-40 flex h-full w-72 flex-col border-r border-stone-200 bg-white shadow-2xl transition-transform duration-500 ease-out"
        :class="open ? 'translate-x-0' : '-translate-x-full'"
    >
        <!-- Brand -->
        <div class="flex h-20 shrink-0 items-center justify-between gap-3 border-b-2 border-[#3F4E2E] px-5">
            <div class="flex min-w-0 items-center gap-3">
                <img :src="DapayLogo" alt="Dap-ay" class="size-11 shrink-0 object-contain" />
                <div class="min-w-0">
                    <p class="truncate text-[15px] font-bold leading-tight tracking-tight text-stone-900">Dap-ay</p>
                    <p class="truncate text-[11px] font-medium uppercase tracking-wider text-stone-400">Reporting System</p>
                </div>
            </div>
            <button @click="open = false" class="flex size-8 shrink-0 items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700">
                <X class="size-5" />
            </button>
        </div>

        <!-- Nav -->
        <nav class="flex-1 space-y-4 overflow-y-auto px-3 py-5">
            <div v-for="group in filteredGroups" :key="group.name">
                <details :open="isGroupActive(group.children)" class="group/d">
                    <summary
                        class="flex h-10 w-full cursor-pointer list-none items-center  gap-3 px-3 text-[13.5px] font-semibold tracking-tight transition-colors marker:content-none [&::-webkit-details-marker]:hidden"
                        :class="isGroupActive(group.children)
                            ? 'text-[#3F4E2E]'
                            : 'text-stone-600 hover:bg-stone-100 hover:text-emerald-900'"
                    >
                        <component :is="group.icon" class="size-5 shrink-0" :class="isGroupActive(group.children) ? 'text-[#3F4E2E]' : 'text-stone-400'" />
                        <span class="flex-1 text-left">{{ group.name }}</span>
                        <ChevronDown class="size-4 shrink-0 text-stone-400 transition-transform duration-200 group-open/d:rotate-180" />
                    </summary>

                    <div class="relative mt-0.5 space-y-0.5 py-1 pl-[1.15rem]">
                        <!-- vertical guide line -->
                        <span class="absolute bottom-2 left-[1.15rem] top-1 w-px bg-stone-200" />

                        <Link
                            v-for="child in group.children"
                            :key="child.name"
                            :href="child.route"
                            @click="open = false"
                            class="relative flex items-center py-2 pl-6 pr-3 text-[13px] transition-colors"
                            :class="isChildActive(child.route)
                                ? 'font-semibold text-[#3F4E2E]'
                                : 'font-medium text-stone-500 hover:text-stone-900'"
                        >
                            <span
                                class="absolute left-0 top-1/2 size-1.5 -translate-y-1/2"
                                :class="isChildActive(child.route) ? 'bg-[#C9722E]' : 'bg-stone-300'"
                            />
                            {{ child.name }}
                        </Link>
                    </div>
                </details>
            </div>
        </nav>

        <!-- Footer -->
        <div class="shrink-0 border-t border-stone-200 px-5 py-4">
            <p class="text-xs text-stone-400">&copy; 2026 Dap-ay</p>
        </div>
    </aside>
</template>