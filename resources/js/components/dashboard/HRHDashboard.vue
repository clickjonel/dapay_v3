<script setup>
import { Home, Users } from '@lucide/vue';

const props = defineProps({
    data: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <div class="w-full flex flex-col justify-start items-start border border-stone-200 bg-white">

        <!-- Header: handled barangays + teams -->
        <div class="w-full grid grid-cols-1 divide-y divide-stone-200 border-b border-stone-200 sm:grid-cols-2 sm:divide-x sm:divide-y-0">

            <!-- Barangays -->
            <div class="p-4">
                <div class="mb-3 flex items-center gap-2">
                    <div class="flex size-8 shrink-0 items-center justify-center border border-[#3F4E2E]/20 bg-[#3F4E2E]/10">
                        <Home class="size-4 text-[#3F4E2E]" />
                    </div>
                    <p class="text-sm font-semibold text-stone-900">
                        Barangays handled
                        <span class="ml-1 font-normal text-stone-400">({{ data?.handled_barangays?.length || 0 }})</span>
                    </p>
                </div>
                <div class="flex max-h-32 flex-wrap gap-1.5 overflow-y-auto pr-1">
                    <span
                        v-for="brgy in data?.handled_barangays"
                        :key="brgy.id"
                        class="border border-stone-200 bg-stone-50 px-2 py-1 text-xs text-stone-600"
                    >
                        {{ brgy.name }}
                    </span>
                    <span v-if="!data?.handled_barangays?.length" class="text-xs text-stone-400">None assigned.</span>
                </div>
            </div>

            <!-- Teams -->
            <div class="p-4">
                <div class="mb-3 flex items-center gap-2">
                    <div class="flex size-8 shrink-0 items-center justify-center border border-[#3F4E2E]/20 bg-[#3F4E2E]/10">
                        <Users class="size-4 text-[#3F4E2E]" />
                    </div>
                    <p class="text-sm font-semibold text-stone-900">
                        Teams handled
                        <span class="ml-1 font-normal text-stone-400">({{ data?.handled_teams?.length || 0 }})</span>
                    </p>
                </div>
                <div class="flex max-h-32 flex-wrap gap-1.5 overflow-y-auto pr-1">
                    <span
                        v-for="team in data?.handled_teams"
                        :key="team.id"
                        class="border border-stone-200 bg-stone-50 px-2 py-1 text-xs text-stone-600"
                    >
                        {{ team.name }}
                    </span>
                    <span v-if="!data?.handled_teams?.length" class="text-xs text-stone-400">None assigned.</span>
                </div>
            </div>
        </div>

        <!-- Columns -->
        <div class="w-full grid grid-cols-12 divide-x divide-stone-200 border-b border-stone-200 bg-stone-50 text-xs font-medium text-stone-500">
            <span class="col-span-1 p-3">Date</span>
            <span class="col-span-1 p-3">Clients</span>
            <span class="col-span-2 p-3">Barangay</span>
            <span class="col-span-3 p-3">PK Activity</span>
            <span class="col-span-3 p-3">Remarks</span>
            <span class="col-span-1 p-3">Status</span>
            <span class="col-span-1 p-3 text-right">Action</span>
        </div>

        <!-- Rows -->
        <div
            v-for="rep in props.data?.reports"
            :key="rep.id"
            class="w-full grid grid-cols-12 divide-x divide-stone-100 border-b border-stone-100 text-sm text-stone-600 hover:bg-stone-50"
        >
            <span class="col-span-1 p-3 tabular-nums">{{ rep.date }}</span>
            <span class="col-span-1 p-3 tabular-nums">{{ rep.total_clients }}</span>
            <span class="col-span-2 p-3 font-medium text-stone-900">{{ rep.barangay.name }}</span>
            <span class="col-span-3 p-3">{{ rep.activity?.name ?? 'N/A' }}</span>
            <span class="col-span-3 p-3 text-stone-500">{{ rep.remarks ?? 'N/A' }}</span>
            <span class="col-span-1 p-3">
                <span
                    class="inline-block border px-2 py-0.5 text-xs font-medium"
                    :class="{
                        'border-amber-200 bg-amber-50 text-amber-700': rep.status === 'Pending',
                        'border-emerald-200 bg-emerald-50 text-emerald-700': rep.status === 'Approved',
                        'border-red-200 bg-red-50 text-red-700': rep.status === 'Rejected',
                    }"
                >
                    {{ rep.status }}
                </span>
            </span>
            <span class="col-span-1 flex justify-end gap-2 p-2">
                <button class="border border-[#3F4E2E] bg-[#3F4E2E] px-2 py-1 text-xs font-medium text-white hover:bg-[#323f25]">
                    Edit
                </button>
            </span>
        </div>

        <!-- Empty state -->
        <div v-if="!props.data?.reports?.length" class="w-full p-10 text-center text-sm text-stone-500">
            No pending reports.
        </div>

    </div>
</template>