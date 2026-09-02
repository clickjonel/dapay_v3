<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import {
        SquarePen,
        ListChecks,
        Target,
        CircleChevronLeft,
        CircleChevronRight,
        FilePlus2,
        Users,
        UserRoundCog,
        Search,
        CalendarDays,
        MapPin,
        UserRoundCheck,
        ExternalLink  
    } from '@lucide/vue';
    import { Link, router } from '@inertiajs/vue3';
    import { computed, ref, onMounted } from 'vue';
    import dayjs from 'dayjs'

    defineOptions({
        layout: Main
    })

    const props = defineProps({
        list: Object
    })

    const filters = ref({
        search: '',
        type: null,
    })

    // Laravel's paginator gives us from/to/total — fall back gracefully if it
    // doesn't for some reason so this never breaks the page.
    const paginationSummary = computed(() => {
        const { from, to, total } = props.list ?? {};
        if (from && to && total) {
            return `Showing ${from}–${to} of ${total} activities`;
        }
        return `Showing ${props.list?.data?.length ?? 0} activities`;
    });

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
        router.get('/pk-activities', { search: filters.value.search }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }

    function formatDate(date) {
        return date ? dayjs(date).format('MMM D, YYYY') : '—';
    }

    onMounted(()=>{
        console.log(props.list.data)
    })
</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-5 p-4 md:p-6 bg-stone-50">

        <!-- header -->
        <section class="w-full flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <span class="text-xl font-bold uppercase tracking-tight text-stone-900">Purokalusugan Activities</span>
                <span class="text-xs text-stone-400">Track community health activities, participating HRH, and reach per barangay.</span>
            </div>

            <div class="w-full flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex w-full sm:w-auto">
                    <div class="relative flex-1 sm:flex-none">
                        <Search class="absolute left-2.5 top-1/2 -translate-y-1/2 size-3.5 text-stone-400 pointer-events-none" />
                        <input
                            v-model="filters.search"
                            type="text"
                            placeholder="Search activity..."
                            class="h-9 w-full sm:w-64 pl-8 pr-3 border border-stone-300 bg-white text-sm text-stone-800 placeholder:text-stone-400 outline-none focus:border-[#3F4E2E] focus:ring-1 focus:ring-[#3F4E2E]/20 transition-colors"
                            @keyup.enter="search"
                        >
                    </div>
                    <button
                        @click="search"
                        class="h-9 px-4 bg-[#3F4E2E] text-white text-xs font-semibold uppercase tracking-wide shadow-sm hover:shadow-md hover:bg-[#334126] active:shadow-none cursor-pointer transition-all shrink-0"
                    >
                        Search
                    </button>
                </div>
                <button @click="create" class="p-2 md:py-2 md:px-4 border bg-[#3F4E2E] text-white text-sm md:text-base cursor-pointer hover:bg-green-900 shadow-md">Add New</button>
            </div>
        </section>

        <!-- xl version -->
        <section class="w-full hidden xl:flex flex-col justify-start items-start bg-white border border-stone-200 divide-y divide-stone-200 shadow-sm overflow-x-auto">
            <div class="w-full grid grid-cols-12 divide-x divide-white/10 bg-[#3F4E2E] text-white">
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">Activity Name</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">Date</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">Type</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">HRH</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">Reports</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">Programs</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">Clients</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">Barangays</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide text-center">Action</span>
            </div>
            <div v-for="act in list.data" :key="act.id" class="w-full grid grid-cols-12 divide-x divide-stone-200 text-sm text-stone-700 hover:bg-stone-50 transition-colors">
                <span class="p-3 col-span-2 flex items-center text-xs font-semibold text-stone-800 wrap-break-word">{{ act.activity_name }}</span>
                <span class="p-3 col-span-1 flex items-center gap-1.5 text-xs text-stone-600">
                    <CalendarDays class="size-3.5 text-stone-400 shrink-0" />
                    {{ formatDate(act.date_start) }}
                </span>
                <span class="p-3 col-span-1 flex items-center">
                    <span class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide bg-[#3F4E2E]/8 text-[#3F4E2E] border border-[#3F4E2E]/15">
                        <Target class="size-3" />
                        {{ act.type }}
                    </span>
                </span>
                <span class="p-3 col-span-1 flex items-center gap-1.5 text-xs font-medium text-stone-700">
                    <Users class="size-3.5 text-stone-400 shrink-0" />
                    {{ act.hrh_count }}
                </span>
                <span class="p-3 col-span-1 flex items-center gap-1.5 text-xs font-medium text-stone-700">
                    <Users class="size-3.5 text-stone-400 shrink-0" />
                    {{ act.reports_count }} Submissions
                </span>
                <span class="p-3 col-span-1 flex items-center gap-1.5 text-xs text-stone-600 truncate">
                    <ListChecks class="size-3.5 text-stone-400 shrink-0" />
                    {{ act.programs_count }}
                </span>
                <span class="p-3 col-span-1 flex items-center gap-1.5 text-xs font-medium text-stone-700">
                    <UserRoundCheck class="size-3.5 text-stone-400 shrink-0" />
                    {{ act.total_clients }}
                </span>
                <span class="p-3 col-span-2 flex items-center gap-1.5 text-xs text-stone-600 truncate">
                    <MapPin class="size-3.5 text-stone-400 shrink-0" />
                    {{ act.barangays_count }} Barangays
                </span>
                <span class="p-3 col-span-2 flex flex-wrap items-center justify-center gap-1">
                    <Link
                        :href="`/pk-activities/${act.id}/view`"
                        title="View activity"
                        class="flex size-6 border border-stone-200 items-center justify-center text-stone-400 shadow-sm transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md"
                    >
                        <ExternalLink class="size-3" />
                    </Link>

                    <Link
                        :href="`/pk-activities/${act.id}/edit`"
                        title="Edit activity"
                        class="flex size-6 border border-stone-200 items-center justify-center text-stone-400 shadow-sm transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md"
                    >
                        <SquarePen class="size-3" />
                    </Link>

                    <Link
                        :href="`/pk-activities/${act.id}/programs/manage`"
                        title="Edit programs"
                        class="flex size-6 border border-stone-200 items-center justify-center text-stone-400 shadow-sm transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md"
                    >
                        <ListChecks class="size-3" />
                    </Link>

                    <Link
                        :href="`/pk-activities/${act.id}/hrh/manage`"
                        title="Edit HRH"
                        class="flex size-6 border border-stone-200 items-center justify-center text-stone-400 shadow-sm transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md"
                    >
                        <UserRoundCog class="size-3" />
                    </Link>

                    <Link
                        :href="`/pk-activities/${act.id}/barangays/manage`"
                        title="Edit barangays"
                        class="flex size-6 border border-stone-200 items-center justify-center text-stone-400 shadow-sm transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md"
                    >
                        <MapPin class="size-3" />
                    </Link>

                    <Link
                        :href="`/pk-activities/${act.id}/report/create`"
                        title="Add report"
                        class="flex size-6 border border-stone-200 items-center justify-center text-stone-400 shadow-sm transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md"
                    >
                        <FilePlus2 class="size-3" />
                    </Link>
                </span>
            </div>

            <p v-if="!list.data.length" class="w-full text-center text-xs text-stone-400 py-8">
                No activities found.
            </p>
        </section>


        <!-- Mobile Version -->
        <section class="w-full flex flex-col gap-3 xl:hidden">
            <div v-for="act in list.data" :key="act.id" class="w-full flex flex-col bg-white border border-stone-200 shadow-sm overflow-hidden">

                <!-- name + type -->
                <div class="w-full flex items-start justify-between gap-2 bg-[#3F4E2E] text-white px-3 py-2.5">
                    <span class="text-xs font-semibold leading-snug uppercase tracking-wide">{{ act.activity_name }}</span>
                    <span class="shrink-0 inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide bg-white/10 px-2 py-0.5">
                        <Target class="size-3" />
                        {{ act.type }}
                    </span>
                </div>

                <div class="w-full flex flex-col gap-3 p-3">

                    <!-- date -->
                    <div class="w-full flex flex-col gap-0.5">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Date</span>
                        <span class="inline-flex items-center gap-1.5 text-sm text-stone-700 font-medium">
                            <CalendarDays class="size-3.5 text-stone-400" />
                            {{ formatDate(act.date_start) }}
                        </span>
                    </div>

                    <!-- barangays -->
                    <div class="w-full flex flex-col gap-0.5 pt-2 border-t border-stone-100">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Barangays</span>
                        <span class="inline-flex items-center gap-1.5 text-sm text-stone-700 truncate">
                            <MapPin class="size-3.5 text-stone-400 shrink-0" />
                            {{ act.barangays_count }} Barangays
                        </span>
                    </div>

                    <!-- hrh, clients, programs -->
                    <div class="w-full grid grid-cols-3 gap-x-3 gap-y-2 pt-2 border-t border-stone-100">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">HRH</span>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-stone-700">
                                <Users class="size-3.5 text-stone-400" />
                                {{ act.hrh_count }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Clients</span>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-stone-700">
                                <UserRoundCheck class="size-3.5 text-stone-400" />
                                {{ act.total_clients }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Programs</span>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-stone-700">
                                <ListChecks class="size-3.5 text-stone-400" />
                                {{ act.programs_count }}
                            </span>
                        </div>
                    </div>

                    <!-- actions -->
                    <div class="w-full flex items-center justify-end flex-wrap gap-1 pt-2 border-t border-stone-100">
                        <Link
                            :href="`/pk-activities/${act.id}/view`"
                            title="View activity"
                            class="flex size-8 items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700"
                        >
                            <ExternalLink class="size-4" />
                        </Link>

                        <Link
                            :href="`/pk-activities/${act.id}/edit`"
                            title="Edit activity"
                            class="flex size-8 items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700"
                        >
                            <SquarePen class="size-4" />
                        </Link>

                        <Link
                            :href="`/pk-activities/${act.id}/programs/manage`"
                            title="Edit programs"
                            class="flex size-8 items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700"
                        >
                            <ListChecks class="size-4" />
                        </Link>

                        <Link
                            :href="`/pk-activities/${act.id}/hrh/manage`"
                            title="Edit HRH"
                            class="flex size-8 items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700"
                        >
                            <UserRoundCog class="size-4" />
                        </Link>

                        <Link
                            :href="`/pk-activities/${act.id}/barangays/manage`"
                            title="Edit barangays"
                            class="flex size-8 items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700"
                        >
                            <MapPin class="size-4" />
                        </Link>

                        <Link
                            :href="`/pk-activities/${act.id}/report/create`"
                            title="Add report"
                            class="flex size-8 items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700"
                        >
                            <FilePlus2 class="size-4" />
                        </Link>
                    </div>
                </div>
            </div>

            <p v-if="!list.data.length" class="w-full text-center text-xs text-stone-400 py-8 bg-white border border-stone-200">
                No activities found.
            </p>
        </section>

        <!-- pagination -->
        <section class="w-full flex justify-between items-center bg-white border border-stone-200 shadow-sm px-4 py-3">
            <span class="text-xs text-stone-400">{{ paginationSummary }}</span>
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