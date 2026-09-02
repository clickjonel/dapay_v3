<script setup>
    import { FileText, Users, PackageCheck, Boxes } from '@lucide/vue';

    const props = defineProps({
        data: {
            type: Object,
            required: true,
        },
    });
</script>

<template>
    <!-- Access Level 1: Admin Overview -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
        <div
            v-for="p in data"
            :key="p.id"
            class="group flex flex-col justify-between border border-stone-200 bg-white transition-colors hover:border-[#3F4E2E]"
        >
            <div>
                <!-- Card Header -->
                <div class="flex items-center justify-between border-b border-stone-200 px-6 py-4">
                    <h2 class="text-base font-bold tracking-tight text-stone-900 transition-colors group-hover:text-[#3F4E2E] uppercase">
                        {{ p.name }}
                    </h2>
                    <span class="border border-stone-200 bg-stone-50 px-2.5 py-1 text-xs font-semibold text-stone-600">
                        {{ p.barangays_count }} Barangays
                    </span>
                </div>

                <!-- Hero Stat: Reports -->
                <div class="flex items-center gap-4 px-6 pt-5">
                    <div class="flex size-12 shrink-0 items-center justify-center border border-[#3F4E2E]/30 bg-[#3F4E2E]/10 text-[#3F4E2E]">
                        <FileText class="size-6" />
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold tracking-tight text-stone-900 tabular-nums">
                            {{ p.report_count || 0 }}
                        </p>
                        <p class="text-xs font-medium uppercase tracking-wide text-stone-500">Total Reports Filed</p>
                    </div>
                </div>

                <!-- Secondary Metrics Grid -->
                <div class="mt-6 grid grid-cols-3 divide-x divide-stone-200 border-y border-stone-200 bg-stone-50">
                    <div class="px-3 py-3.5 text-center">
                        <div class="mb-1 flex items-center justify-center gap-1 text-stone-400">
                            <Users class="size-3.5" />
                        </div>
                        <p class="text-base font-bold text-stone-900 tabular-nums">{{ p.team_count || 0 }}</p>
                        <p class="text-[11px] font-medium uppercase tracking-wide text-stone-500">Teams</p>
                    </div>
                    <div class="px-3 py-3.5 text-center">
                        <div class="mb-1 flex items-center justify-center gap-1 text-stone-400">
                            <PackageCheck class="size-3.5" />
                        </div>
                        <p class="text-base font-bold text-stone-900 tabular-nums">{{ p.team_pk_kit_count || 0 }}</p>
                        <p class="text-[11px] font-medium uppercase tracking-wide text-stone-500">Kits</p>
                    </div>
                    <div class="px-3 py-3.5 text-center">
                        <div class="mb-1 flex items-center justify-center gap-1 text-stone-400">
                            <Boxes class="size-3.5" />
                        </div>
                        <p class="text-base font-bold text-stone-900 tabular-nums">{{ p.pk_activities_count || 0 }}</p>
                        <p class="text-[11px] font-medium uppercase tracking-wide text-stone-500">Activities</p>
                    </div>
                </div>
            </div>

            <!-- Activity Split Bar -->
            <div class="px-6 py-4">
                <div class="mb-2 flex items-center justify-between text-xs font-semibold uppercase tracking-wide text-stone-600">
                    <span>Activity Scale Breakdown</span>
                </div>
                <div class="flex h-2 w-full overflow-hidden border border-stone-200 bg-stone-100">
                    <div
                        class="h-full bg-[#3F4E2E] transition-all duration-500"
                        :style="{ width: (p.pk_large_count / (p.pk_large_count + p.pk_small_count || 1) * 100) + '%' }"
                    />
                    <div
                        class="h-full bg-[#3F4E2E]/30 transition-all duration-500"
                        :style="{ width: (p.pk_small_count / (p.pk_large_count + p.pk_small_count || 1) * 100) + '%' }"
                    />
                </div>
                <div class="mt-2.5 flex items-center justify-between text-xs font-medium text-stone-500">
                    <span class="flex items-center gap-1.5">
                        <span class="size-2 bg-[#3F4E2E]"></span>
                        {{ p.pk_large_count || 0 }} Large
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="size-2 bg-[#3F4E2E]/30"></span>
                        {{ p.pk_small_count || 0 }} Small
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>