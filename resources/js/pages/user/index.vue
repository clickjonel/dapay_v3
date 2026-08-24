<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { SquarePen, CircleUser,CircleChevronLeft, CircleChevronRight } from '@lucide/vue';
    import { Link, router } from '@inertiajs/vue3';
    import { getAccessLevelLabel } from '@/lib/useAccessLevel';
    import { ref } from 'vue';

    defineOptions({
        layout: Main
    })

    defineProps({
        list: Object
    })

    const filters = ref({
        search: '',
        pkSites: null,
        gida: null
    })

    function previousPage(url) {
        if (!url) return;

        router.visit(url, {
            preserveScroll: true,
            preserveState: true,
        });
    }

    function nextPage(url) {
        if (!url) return;

        router.visit(url, {
            preserveScroll: true,
            preserveState: true,
        });
    }

    function search(){
        router.get('/users', { search: filters.value.search }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }

    function create(){
        router.visit('/users/create')
    }

</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-4 p-4 bg-stone-50">

         <!-- header -->
         <section class="w-full flex flex-col gap-3">
            <span class="text-lg font-bold uppercase tracking-tight text-stone-900">Users</span>

            <div class="w-full flex justify-between items-center">
                <div class="flex">
                    <input v-model="filters.search" type="text" placeholder="Search" class="h-9 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors">
                    <button @click="search" class="h-9 px-4 bg-[#3F4E2E] text-white text-xs font-semibold uppercase tracking-wide hover:bg-[#334126] cursor-pointer">Search</button>
                </div>
                <button @click="create" class="p-2 md:py-2 md:px-4 border bg-[#3F4E2E] text-white text-sm md:text-base cursor-pointer hover:bg-green-900 shadow-md">Add New</button>
            </div>
        </section>

        <!-- table (md and up) -->
        <section class="hidden md:flex w-full flex-col justify-start items-start bg-white border border-stone-200 divide-y divide-stone-200 shadow-sm overflow-x-auto">
            <div class="w-full grid grid-cols-12 divide-x divide-white/10 bg-[#3F4E2E] text-white">
                <span class="p-3 col-span-3 text-[11px] font-semibold uppercase tracking-wide">Name</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">Email</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">PDOHO</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">Access Level</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">Status</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide text-center">Action</span>
            </div>
            <div v-for="user in list.data" :key="user.id" class="w-full grid grid-cols-12 divide-x divide-stone-200 text-sm text-stone-700 hover:bg-stone-50 transition-colors">
                <span class="p-3 col-span-3 text-[11px] font-semibold uppercase tracking-wide truncate">{{ user.name }}</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide truncate">{{ user.email }}</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide truncate">{{ user.province?.name ?? '—' }}</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide truncate">{{ getAccessLevelLabel(user.access_level) }}</span>
                <span class="p-3 col-span-2 flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide">
                    <span class="size-1.5 rounded-full shrink-0" :class="user.is_online ? 'bg-emerald-500' : 'bg-stone-300'"></span>
                    <span :class="user.is_online ? 'text-emerald-600' : 'text-stone-400'">{{ user.is_online ? 'Online' : 'Offline' }}</span>
                </span>
                <span class="p-3 col-span-1 flex items-center justify-start gap-2">
                    <Link
                        :href="`/users/${user.id}/edit`"
                        title="Edit User"
                        class="flex size-7 border border-stone-200 items-center justify-center text-stone-400 shadow-sm transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md"
                    >
                        <SquarePen class="size-4" />
                    </Link>
                </span>
            </div>

            <p v-if="!list.data.length" class="w-full text-center text-xs text-stone-400 py-8">
                No users found.
            </p>
        </section>

        <!-- cards (below md) -->
        <section class="flex md:hidden w-full flex-col gap-3">
            <div
                v-for="user in list.data"
                :key="user.id"
                class="w-full flex flex-col gap-3 bg-white border border-stone-200 shadow-sm p-4"
            >
                <div class="w-full flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3 min-w-0">
                        <span class="flex items-center justify-center size-9 shrink-0 rounded-full bg-[#3F4E2E]/10 text-[#3F4E2E]">
                            <CircleUser class="size-5" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-stone-900 truncate">{{ user.name }}</p>
                            <p class="text-xs text-stone-500 truncate">{{ user.email }}</p>
                        </div>
                    </div>
                    <Link
                        :href="`/users/${user.id}/edit`"
                        title="Edit User"
                        class="flex size-8 shrink-0 border border-stone-200 items-center justify-center text-stone-400 shadow-sm transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md"
                    >
                        <SquarePen class="size-4" />
                    </Link>
                </div>

                <div class="w-full grid grid-cols-2 gap-3 pt-3 border-t border-stone-100">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-stone-400">PDOHO</span>
                        <span class="text-xs font-medium text-stone-700 truncate">{{ user.province?.name ?? '—' }}</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-stone-400">Access Level</span>
                        <span class="text-xs font-medium text-stone-700 truncate">{{ getAccessLevelLabel(user.access_level) }}</span>
                    </div>
                    <div class="flex flex-col gap-0.5 col-span-2">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-stone-400">Status</span>
                        <span class="flex items-center gap-1.5 text-xs font-medium">
                            <span class="size-1.5 rounded-full shrink-0" :class="user.is_online ? 'bg-emerald-500' : 'bg-stone-300'"></span>
                            <span :class="user.is_online ? 'text-emerald-600' : 'text-stone-400'">{{ user.is_online ? 'Online' : 'Offline' }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <p v-if="!list.data.length" class="w-full text-center text-xs text-stone-400 py-8 bg-white border border-stone-200">
                No users found.
            </p>
        </section>

         <!-- pagination -->
         <section class="w-full flex justify-between items-center border border-stone-200 px-4 py-3">
            <span class="text-xs text-stone-400">Showing <span class="font-medium text-stone-600">{{ list.data.length }}</span> records per page</span>
            <div class="flex items-center gap-3">
                <CircleChevronLeft
                    @click="previousPage(list.prev_page_url)"
                    class="size-6 transition-colors"
                    :class="list.prev_page_url ? 'text-stone-500 cursor-pointer hover:text-[#3F4E2E]' : 'text-stone-200 cursor-not-allowed'"
                />
                <CircleChevronRight
                    @click="nextPage(list.next_page_url)"
                    class="size-6 transition-colors"
                    :class="list.next_page_url ? 'text-stone-500 cursor-pointer hover:text-[#3F4E2E]' : 'text-stone-200 cursor-not-allowed'"
                />
            </div>
        </section>

    </main>
</template>