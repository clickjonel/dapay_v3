<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { SquarePen, ListChecks, Target, CircleChevronLeft, CircleChevronRight, ExternalLink, Users } from '@lucide/vue';
    import { Link, router } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import { UserRoundCog } from '@lucide/vue';

    defineOptions({
        layout: Main
    })

    defineProps({
        list: Object
    })

    const filters = ref({
        search: '',
        pkKit: null,
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
        router.get('/teams', { search: filters.value.search }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }

</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-4 p-4 bg-stone-50">

        <!-- header -->
        <section class="w-full flex flex-col gap-3">
            <span class="text-lg font-bold uppercase tracking-tight text-stone-900">Teams</span>

            <div class="w-full flex justify-between items-center">
                <div class="flex">
                    <input
                        v-model="filters.search"
                        type="text"
                        placeholder="Search teams..."
                        class="h-9 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors"
                        @keyup.enter="search"
                    >
                    <button @click="search" class="h-9 px-4 bg-[#3F4E2E] text-white text-xs font-semibold uppercase tracking-wide shadow-sm hover:shadow-md hover:bg-[#334126] active:shadow-none cursor-pointer transition-all">
                        Search
                    </button>
                </div>
            </div>
        </section>

        <!-- xl version -->
        <section class="w-full hidden xl:flex flex-col justify-start items-start bg-white border border-stone-200 divide-y divide-stone-200 shadow-sm overflow-x-auto">
            <div class="w-full grid grid-cols-12 divide-x divide-white/10 bg-[#3F4E2E] text-white">
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">Name</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">Active</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">PK Kit</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">EO Link</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">Created By</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">Members</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">Location</span>
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide text-center">Action</span>
            </div>
            <div v-for="team in list.data" :key="team.id" class="w-full grid grid-cols-12 divide-x divide-stone-200 text-sm text-stone-700 hover:bg-stone-50 transition-colors">
                <span class="p-3 col-span-2 flex items-center text-xs font-semibold text-stone-800 truncate">{{ team.name }}</span>

                <span class="p-3 col-span-1 flex items-center">
                    <span
                        class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide"
                        :class="team.active ? 'text-emerald-600' : 'text-stone-400'"
                    >
                        <span class="size-1.5 rounded-full shrink-0" :class="team.active ? 'bg-emerald-500' : 'bg-stone-300'"></span>
                        {{ team.active ? 'Active' : 'Inactive' }}
                    </span>
                </span>

                <span class="p-3 col-span-1 flex items-center">
                    <span
                        class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide"
                        :class="team.pk_kit ? 'text-emerald-600' : 'text-stone-400'"
                    >
                        <span class="size-1.5 rounded-full shrink-0" :class="team.pk_kit ? 'bg-emerald-500' : 'bg-stone-300'"></span>
                        {{ team.pk_kit ? 'Yes' : 'No' }}
                    </span>
                </span>

                <span class="p-3 col-span-2 flex items-center">
                    <a
                        v-if="team.eo_link"
                        :href="team.eo_link"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1 text-[11px] font-semibold uppercase tracking-wide text-[#3F4E2E] hover:text-[#2d3a20] hover:underline transition-colors"
                    >
                        View Link
                        <ExternalLink class="size-3" />
                    </a>
                    <span v-else class="text-[11px] text-stone-300">—</span>
                </span>

                <span class="p-3 col-span-2 flex items-center text-xs text-stone-600 truncate">{{ team.created_by?.name ?? '—' }}</span>

                <span class="p-3 col-span-1 flex items-center gap-1.5 text-xs font-medium text-stone-700">
                    <Users class="size-3.5 text-stone-400" />
                    {{ team.members.length }}
                </span>

                <span class="p-3 col-span-2 flex flex-col justify-center gap-0.5 min-w-0">
                    <span class="text-xs font-medium text-stone-700 truncate">{{ team.barangay?.name ?? '—' }}</span>
                    <span class="text-[11px] text-stone-400 truncate">
                        {{ team.barangay?.municipality?.name ?? '—' }}<span v-if="team.barangay?.province?.name">, {{ team.barangay.province.name }}</span>
                    </span>
                </span>

                <span class="p-3 col-span-1 flex items-center justify-center gap-2">
                    <Link
                        :href="`/teams/${team.id}/edit`"
                        title="Edit team"
                        class="flex size-7 border border-stone-200 items-center justify-center text-stone-400 shadow-sm transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md"
                    >
                        <SquarePen class="size-3.5" />
                    </Link>

                    <Link
                        :href="`/teams/${team.id}/members`"
                        title="Manage team Members"
                        class="flex size-7 border border-stone-200 items-center justify-center text-stone-400 shadow-sm transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md"
                    >
                        <UserRoundCog class="size-3.5" />
                    </Link>
                </span>
            </div>

            <p v-if="!list.data.length" class="w-full text-center text-xs text-stone-400 py-8">
                No teams found.
            </p>
        </section>


        <!-- Mobile Version -->
        <section class="w-full flex flex-col gap-3 xl:hidden">
            <div v-for="team in list.data" :key="team.id" class="w-full flex flex-col bg-white border border-stone-200 shadow-sm overflow-hidden">

                <!-- name + active status -->
                <div class="w-full flex items-start justify-between gap-2 bg-[#3F4E2E] text-white px-3 py-2.5">
                    <span class="text-xs font-semibold leading-tight uppercase tracking-wide">{{ team.name }}</span>
                    <span
                        class="shrink-0 inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide"
                        :class="team.active ? 'text-emerald-300' : 'text-white/50'"
                    >
                        <span class="size-1.5 rounded-full shrink-0" :class="team.active ? 'bg-emerald-400' : 'bg-white/40'"></span>
                        {{ team.active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="w-full flex flex-col gap-3 p-3">

                    <!-- location -->
                    <div class="w-full flex flex-col gap-0.5">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Location</span>
                        <span class="text-sm text-stone-700 font-medium truncate">{{ team.barangay?.name ?? '—' }}</span>
                        <span class="text-xs text-stone-400 truncate">
                            {{ team.barangay?.municipality?.name ?? '—' }}<span v-if="team.barangay?.province?.name">, {{ team.barangay.province.name }}</span>
                        </span>
                    </div>

                    <!-- created by -->
                    <div class="w-full flex flex-col gap-0.5 pt-2 border-t border-stone-100">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Created By</span>
                        <span class="text-sm text-stone-700 truncate font-medium">{{ team.created_by?.name ?? '—' }}</span>
                    </div>

                    <!-- pk kit, members, eo link -->
                    <div class="w-full grid grid-cols-3 gap-x-3 gap-y-2 pt-2 border-t border-stone-100">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">PK Kit</span>
                            <span
                                class="inline-flex items-center gap-1 text-xs font-semibold"
                                :class="team.pk_kit ? 'text-emerald-600' : 'text-stone-400'"
                            >
                                <span class="size-1.5 rounded-full shrink-0" :class="team.pk_kit ? 'bg-emerald-500' : 'bg-stone-300'"></span>
                                {{ team.pk_kit ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Members</span>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-stone-700">
                                <Users class="size-3.5 text-stone-400" />
                                {{ team.members.length }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">EO Link</span>
                            <a
                                v-if="team.eo_link"
                                :href="team.eo_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-[#3F4E2E] hover:underline"
                            >
                                View
                                <ExternalLink class="size-3" />
                            </a>
                            <span v-else class="text-xs text-stone-300">—</span>
                        </div>
                    </div>

                    <!-- actions -->
                    <div class="w-full flex items-center justify-end gap-1 pt-2 border-t border-stone-100">
                        <Link
                            :href="`/teams/${team.id}/edit`"
                            title="Edit team"
                            class="flex size-8 items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700"
                        >
                            <SquarePen class="size-4" />
                        </Link>

                        <Link
                            :href="`/teams/${team.id}/members`"
                            title="Manage team Members"
                            class="flex size-8 items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700"
                        >
                            <UserRoundCog class="size-4" />
                        </Link>
                    </div>
                </div>
            </div>

            <p v-if="!list.data.length" class="w-full text-center text-xs text-stone-400 py-8 bg-white border border-stone-200">
                No teams found.
            </p>
        </section>

        <!-- pagination -->
        <section class="w-full flex justify-between items-center bg-white border border-stone-200 shadow-sm px-4 py-3">
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