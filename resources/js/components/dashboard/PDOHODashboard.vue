<script setup>
import { Home, Building2, ExternalLink, Tag } from '@lucide/vue';

const props = defineProps({
    data: {
        type: Object,
        required: true
    }
});

function getMunicipalityTotalPkSites(municipality){
    const total = municipality.barangays.reduce((sum, barangay) => {
        return sum + (barangay.pk_site === 1 ? 1 : 0);
    }, 0);

    return total;
}

function getMunicipalityTotalGida(municipality){
    const total = municipality.barangays.reduce((sum, barangay) => {
        return sum + (barangay.is_gida === 1 ? 1 : 0);
    }, 0);
    
    return total;
}

function getMunicipalityTotalTargetPopulation(municipality){
    const total = municipality.barangays.reduce((sum, barangay) => {
        return sum + (Number(barangay.total_population) || 0);
    }, 0);
    
    return total;
}

function getMunicipalityTotalTargetPuroks(municipality){
    const total = municipality.barangays.reduce((sum, barangay) => {
        return sum + (Number(barangay.total_puroks) || 0);
    }, 0);
    
    return total;
}

function getMunicipalityTotalTargetHouseholds(municipality){
    const total = municipality.barangays.reduce((sum, barangay) => {
        return sum + (Number(barangay.total_households) || 0);
    }, 0);
    
    return total;
}

function getMunicipalityTotalTeams(municipality){
    return municipality.barangays.filter(barangay => Boolean(barangay.team)).length;
}

function getMunicipalityTeamsWithKit(municipality) {
    return municipality.barangays.filter(
        barangay => barangay.team?.pk_kit === 1
    ).length;
}

</script>

<template>
    <div class="w-full space-y-6 font-sans">

        <!-- Municipality Loop -->
        <div
            v-for="mun in props.data?.municipalities"
            :key="mun.id"
            class="w-full overflow-hidden border border-stone-200 bg-white shadow-sm"
        >
            <!-- Municipality Header -->
            <div class="flex items-center justify-between border-b border-stone-200 bg-[#3F4E2E] px-6 py-4">
                <div class="flex items-center gap-2.5">
                    <div class="flex size-8 items-center justify-center border border-white/20 bg-white/10 text-white">
                        <Building2 class="size-4" />
                    </div>
                    <h2 class="text-base font-bold text-white">{{ mun.name }}</h2>
                </div>
                <span class="border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold text-white">
                    {{ mun.barangays?.length || 0 }} Barangays
                </span>
            </div>

            <!-- Aggregate Stat Cards -->
            <div class="grid grid-cols-2 gap-px border-b border-stone-200 bg-stone-200/70 sm:grid-cols-4 lg:grid-cols-7">
                <div class="bg-white p-4">
                    <p class="text-xl font-bold leading-none text-stone-900 tabular-nums">{{ getMunicipalityTotalPkSites(mun) }}</p>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-wider text-stone-400">PK Sites</p>
                </div>
                <div class="bg-white p-4">
                    <p class="text-xl font-bold leading-none text-stone-900 tabular-nums">{{ getMunicipalityTotalGida(mun) }}</p>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-wider text-stone-400">GIDA</p>
                </div>
                <div class="bg-white p-4">
                    <p class="text-xl font-bold leading-none text-stone-900 tabular-nums">{{ getMunicipalityTotalTargetPopulation(mun) }}</p>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-wider text-stone-400">Pop. Target</p>
                </div>
                <div class="bg-white p-4">
                    <p class="text-xl font-bold leading-none text-stone-900 tabular-nums">{{ getMunicipalityTotalTargetHouseholds(mun)}}</p>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-wider text-stone-400">HH Target</p>
                </div>
                <div class="bg-white p-4">
                    <p class="text-xl font-bold leading-none text-stone-900 tabular-nums">{{ getMunicipalityTotalTargetPuroks(mun) }}</p>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-wider text-stone-400">Puroks Target</p>
                </div>
                <div class="bg-white p-4">
                    <p class="text-xl font-bold leading-none text-stone-900 tabular-nums">{{ getMunicipalityTotalTeams(mun) }}</p>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-wider text-stone-400">Teams</p>
                </div>
                <div class="bg-[#3F4E2E]/5 p-4">
                    <p class="text-xl font-bold leading-none text-[#3F4E2E] tabular-nums">{{ getMunicipalityTeamsWithKit(mun) }}</p>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-wider text-stone-400">Teams w/ Kit</p>
                </div>
            </div>

            <!-- Barangays Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50 text-[11px] font-semibold uppercase tracking-wider text-stone-500">
                            <th class="px-6 py-3.5">Barangay</th>
                            <th class="px-4 py-3.5">Assigned Team</th>
                            <th class="px-4 py-3.5">Demographics (Total / Target)</th>
                            <th class="px-4 py-3.5">Priority Programs</th>
                            <th class="px-6 py-3.5 text-center">PK Site</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <tr
                            v-for="(brgy, i) in mun.barangays"
                            :key="brgy.id"
                            class="hover:bg-stone-50 transition-colors"
                            :class="i % 2 === 1 ? 'bg-stone-50/50' : 'bg-white'"
                        >
                            <!-- Barangay Name -->
                            <td class="px-6 py-4 font-semibold text-stone-900 whitespace-nowrap border-l-2 border-transparent">
                                <div class="flex items-center gap-2">
                                    <Home class="size-4 text-stone-400 shrink-0" />
                                    <span>{{ brgy.name }}</span>
                                </div>
                            </td>

                            <!-- Team & Link -->
                            <td class="px-4 py-4 text-stone-700">
                                <div v-if="brgy.team" class="flex flex-col gap-1">
                                    <span class="font-medium text-stone-900">{{ brgy.team.name }}</span>
                                    
                                    <a v-if="brgy.team.eo_link"
                                        :href="brgy.team.eo_link"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-[#3F4E2E] hover:underline"
                                    >
                                        View EO Link <ExternalLink class="size-3" />
                                    </a>
                                </div>
                                <span v-else class="text-xs text-stone-400 italic">No Team Assigned</span>
                            </td>

                            <!-- Demographics Breakdown -->
                            <td class="px-4 py-4">
                                <div class="grid grid-cols-3 gap-3 text-xs min-w-[280px]">
                                    <!-- Population -->
                                    <div class="border border-stone-200 bg-stone-50 p-2">
                                        <p class="font-semibold text-stone-500 text-[10px] uppercase">Population</p>
                                        <p class="text-stone-800 font-bold tabular-nums">
                                            {{ brgy.total_population || 0 }} <span class="font-normal text-stone-400">/ {{ brgy.target_population || 0 }}</span>
                                        </p>
                                    </div>

                                    <!-- Puroks -->
                                    <div class="border border-stone-200 bg-stone-50 p-2">
                                        <p class="font-semibold text-stone-500 text-[10px] uppercase">Puroks</p>
                                        <p class="text-stone-800 font-bold tabular-nums">
                                            {{ brgy.total_puroks || 0 }} <span class="font-normal text-stone-400">/ {{ brgy.target_puroks || 0 }}</span>
                                        </p>
                                    </div>

                                    <!-- Households -->
                                    <div class="border border-stone-200 bg-stone-50 p-2">
                                        <p class="font-semibold text-stone-500 text-[10px] uppercase">Households</p>
                                        <p class="text-stone-800 font-bold tabular-nums">
                                            {{ brgy.total_households || 0 }} <span class="font-normal text-stone-400">/ {{ brgy.target_households || 0 }}</span>
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Priority Programs -->
                            <td class="px-4 py-4">
                                <div v-if="brgy.priority_programs?.length" class="flex flex-wrap gap-1.5 max-w-xs">
                                    <span
                                        v-for="(prog, idx) in brgy.priority_programs"
                                        :key="idx"
                                        class="inline-flex items-center gap-1 border border-amber-200 bg-amber-50 px-2 py-1 text-xs font-medium text-amber-800"
                                    >
                                        <Tag class="size-3 text-amber-500" />
                                        {{ prog.name || prog }}
                                    </span>
                                </div>
                                <span v-else class="text-xs text-stone-400 italic">—</span>
                            </td>

                            <!-- PK Site Badge -->
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span
                                    class="inline-flex items-center border px-2.5 py-0.5 text-xs font-semibold"
                                    :class="brgy.pk_site === 1
                                        ? 'border-[#3F4E2E]/20 bg-[#3F4E2E]/10 text-[#3F4E2E]'
                                        : 'border-stone-200 bg-stone-100 text-stone-500'"
                                >
                                    {{ brgy.pk_site === 1 ? 'Yes' : 'No' }}
                                </span>
                            </td>
                        </tr>

                        <!-- Empty Barangay State -->
                        <tr v-if="!mun.barangays?.length">
                            <td colspan="5" class="px-6 py-8 text-center text-xs font-medium text-stone-400">
                                No barangay records available for {{ mun.name }}.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty Municipality State -->
        <div v-if="!props.data?.municipalities?.length" class="border border-stone-200 bg-white p-12 text-center text-sm text-stone-500">
            <Building2 class="mx-auto size-10 text-stone-300" />
            <p class="mt-2 font-medium text-stone-700">No municipality data available</p>
            <p class="text-xs text-stone-400">Please check back later or update your filter settings.</p>
        </div>

    </div>
</template>