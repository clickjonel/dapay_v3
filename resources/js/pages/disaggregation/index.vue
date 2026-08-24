<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { SquarePen } from '@lucide/vue';
    import { Link, router } from '@inertiajs/vue3';

    defineOptions({
        layout: Main
    })

    defineProps({
        list: Object
    })
    

    function create(){
        router.visit('/disaggregations/create')
    }


</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-4 p-4">

        <!-- header -->
        <section class="w-full flex justify-between items-center gap-3">
            <span class="text-lg font-bold uppercase tracking-tight text-stone-900">Disaggregations</span>
            <button @click="create" class="p-2 md:py-2 md:px-4 border bg-[#3F4E2E] text-white text-sm md:text-base cursor-pointer hover:bg-green-900 shadow-md">Create</button>
        </section>

        <!-- table (md and up) -->
        <section class="hidden md:flex w-full flex-col justify-start items-start border border-stone-200 divide-y divide-stone-200 overflow-x-auto">
            <div class="w-full grid grid-cols-12 divide-x divide-white/10 bg-[#3F4E2E] text-white">
                <span class="p-3 col-span-1 text-[11px] font-semibold uppercase tracking-wide">#</span>
                <span class="p-3 col-span-5 text-[11px] font-semibold uppercase tracking-wide">Name</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">Group</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide">Status</span>
                <span class="p-3 col-span-2 text-[11px] font-semibold uppercase tracking-wide text-center">Action</span>
            </div>
            <div v-for="(dis, index) in list" :key="dis.id" class="w-full grid grid-cols-12 divide-x divide-stone-200 text-sm text-stone-700 hover:bg-stone-50 transition-colors">
                <span class="p-3 col-span-1 text-stone-500 tabular-nums">{{ index + 1 }}</span>
                <span class="p-3 col-span-5 font-medium text-stone-900">{{ dis.name }}</span>
                <span class="p-3 col-span-2">{{ dis.group }}</span>
                <span class="p-3 col-span-2">{{ dis.active ? 'Active' : 'Disabled' }}</span>
                <span class="p-3 col-span-2 flex items-center justify-start gap-2">
                    <Link
                        :href="`/disaggregations/${dis.id}/edit`"
                        title="Edit Disaggregation"
                        class="flex size-7 border border-stone-200 items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700"
                    >
                        <SquarePen class="size-4" />
                    </Link>
                </span>
            </div>

        </section>

        <!-- cards (below md) -->
        <section class="flex md:hidden w-full flex-col gap-2">
            <div v-for="(dis, index) in list" :key="dis.id" class="w-full flex items-center justify-between gap-3 border border-stone-200 p-3">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="shrink-0 text-xs text-stone-400 tabular-nums">{{ index + 1 }}</span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-stone-900 truncate">{{ dis.name }}</p>
                        <p class="text-sm font-medium text-stone-900 truncate">Group: {{ dis.group }}</p>
                        <p class="text-xs" :class="dis.active ? 'text-emerald-600' : 'text-rose-600'">{{ dis.active ? 'Active' : 'Disabled' }}</p>
                    </div>
                </div>
                <Link
                    :href="`/disaggregations/${dis.id}/edit`"
                    title="Edit Disaggregation"
                    class="flex size-8 shrink-0 border border-stone-200 items-center justify-center text-stone-400 transition-colors hover:bg-stone-100 hover:text-stone-700"
                >
                    <SquarePen class="size-4" />
                </Link>
            </div>
        </section>

    </main>
</template>