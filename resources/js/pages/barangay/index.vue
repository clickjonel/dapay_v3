<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { SquarePen, ListChecks, Target, CircleChevronLeft, CircleChevronRight } from '@lucide/vue';
    import { Link, router } from '@inertiajs/vue3';
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
        router.get('/barangays', { search: filters.value.search }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }

</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-4 p-4">

        <!-- header -->
        <section class="w-full flex flex-col gap-3">
            <span class="text-lg font-bold uppercase tracking-tight text-stone-900">Barangays</span>

            <div class="w-full flex justify-between items-center">
                <div class="flex">
                    <input v-model="filters.search" type="text" placeholder="Search" class="h-9 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors">
                    <button @click="search" class="h-9 px-4 bg-[#3F4E2E] text-white text-xs font-semibold uppercase tracking-wide hover:bg-[#334126] cursor-pointer">Search</button>
                </div>
            </div>
        </section>

        <!-- xl version -->
        <section class="w-full hidden xl:flex flex-col justify-start items-start border border-stone-200 divide-y divide-stone-200 overflow-x-auto">
            <div class="w-full grid grid-cols-12 divide-x divide-white/10 bg-[#3F4E2E] text-white">
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">PSGC Code</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">Barangay</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">Municipality</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">Province</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">PK Status</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">PK Site</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">GIDA Area</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide text-right">Population</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide text-right">Puroks</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide text-right">Households</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide text-center">Action</span>
            </div>
            <div v-for="brgy in list.data" :key="brgy.id" class="w-full grid grid-cols-12 divide-x divide-stone-200 text-sm text-stone-700 hover:bg-stone-50 transition-colors">
                <span class="p-3 col-span-1 text-stone-500">{{ brgy.psgc_code }}</span>
                <span class="p-3 col-span-2 font-medium text-stone-900">{{ brgy.name }}</span>
                <span class="p-3 col-span-1">{{ brgy.municipality?.name }}</span>
                <span class="p-3 col-span-1">{{ brgy.province?.name }}</span>
                <span class="p-3 col-span-1 wrap-break-word">{{ brgy.pk_status }}</span>
                <span class="p-3 col-span-1">{{ brgy.pk_site }}</span>
                <span class="p-3 col-span-1">{{ brgy.is_gida }}</span>
                <span class="p-3 col-span-1 text-right tabular-nums">{{ brgy.total_population }}</span>
                <span class="p-3 col-span-1 text-right tabular-nums">{{ brgy.total_puroks }}</span>
                <span class="p-3 col-span-1 text-right tabular-nums">{{ brgy.total_households }}</span>
                <span class="p-3 col-span-1 flex items-center justify-center gap-2">
                    <Link
                        :href="`/barangays/${brgy.id}/edit`"
                        title="Edit barangay"
                        class="flex size-6 border border-gray-200 shadow-xs items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700"
                    >
                        <SquarePen class="size-4" />
                    </Link>
                    <Link
                        :href="`/barangays/${brgy.id}/organizational-indicators/manage`"
                        title="Manage organizational indicators"
                        class="flex size-6 border border-gray-200 shadow-xs items-center justify-center text-stone-400 transition-colors hover:bg-[#3F4E2E]/10 hover:text-[#3F4E2E]"
                    >
                        <ListChecks class="size-4" />
                    </Link>
                    <Link
                        :href="`/barangays/${brgy.id}/priority-programs/manage`"
                        title="Set priority programs"
                        class="flex size-6 border border-gray-200 shadow-xs items-center justify-center text-stone-400 transition-colors hover:bg-[#C9722E]/10 hover:text-[#C9722E]"
                    >
                        <Target class="size-4" />
                    </Link>
                </span>
            </div>
        </section>


        <!-- Mobile Version -->
        <section class="w-full flex flex-col gap-2 xl:hidden">
            <div v-for="brgy in list.data" :key="brgy.id" class="w-full flex flex-col gap-3 border border-stone-200 p-2">
                <!-- name + psgc code -->
                <div class="w-full flex items-start justify-between gap-2 bg-[#3F4E2E] text-white p-2">
                    <span class="text-[12px] font-semibold leading-tight uppercase">{{ brgy.name }}</span>
                    <span class="shrink-0 text-xs">{{ brgy.psgc_code }}</span>
                </div>

                <!-- location -->
                <div class="w-full grid grid-cols-2 gap-x-3 gap-y-2 pt-2 border-t border-stone-100">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Municipality</span>
                        <span class="text-sm text-stone-700 truncate font-medium">{{ brgy.municipality?.name ?? '—' }}</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Province</span>
                        <span class="text-sm text-stone-700 truncate font-medium">{{ brgy.province?.name ?? '—' }}</span>
                    </div>
                </div>

                <!-- status flags -->
                <div class="w-full grid grid-cols-3 gap-x-3 gap-y-2 pt-2 border-t border-stone-100">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">PK Status</span>
                        <span class="text-xs text-stone-700">{{ brgy.pk_status ?? '—' }}</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">PK Site</span>
                        <span class="text-sm text-stone-700">{{ brgy.pk_site ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">GIDA Area</span>
                        <span class="text-sm text-stone-700">{{ brgy.is_gida ? 'Yes' : 'No' }}</span>
                    </div>
                </div>

                <!-- figures -->
                <div class="w-full grid grid-cols-3 gap-x-3 gap-y-2 pt-2 border-t border-stone-100">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Population</span>
                        <span class="text-sm font-medium text-stone-900 tabular-nums">{{ brgy.total_population ?? '—' }}</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Puroks</span>
                        <span class="text-sm font-medium text-stone-900 tabular-nums">{{ brgy.total_puroks ?? '—' }}</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Households</span>
                        <span class="text-sm font-medium text-stone-900 tabular-nums">{{ brgy.total_households ?? '—' }}</span>
                    </div>
                </div>

                <!-- actions -->
                <div class="w-full flex items-center justify-end gap-1 pt-2 border-t border-stone-100">
                    <Link
                        :href="`/barangays/${brgy.id}/edit`"
                        title="Edit barangay"
                        class="flex size-8 items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700"
                    >
                        <SquarePen class="size-4" />
                    </Link>
                    <Link
                        :href="`/barangays/${brgy.id}/organizational-indicators/manage`"
                        title="Set indicators"
                        class="flex size-8 items-center justify-center text-stone-400 transition-colors hover:bg-[#3F4E2E]/10 hover:text-[#3F4E2E]"
                    >
                        <ListChecks class="size-4" />
                    </Link>
                    <Link
                        :href="`/barangays/${brgy.id}/priority-programs/manage`"
                        title="Set priority programs"
                        class="flex size-8 items-center justify-center text-stone-400 transition-colors hover:bg-[#C9722E]/10 hover:text-[#C9722E]"
                    >
                        <Target class="size-4" />
                    </Link>
                </div>
            </div>
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