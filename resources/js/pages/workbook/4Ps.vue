<script setup>
    import { ref, computed } from 'vue'
    import { Inbox, MapPin, ListChecks, TrendingUp } from '@lucide/vue'
    import Main from '@/layouts/auth/Main.vue'

    defineOptions({
        layout:Main
    })

    const props = defineProps({
        provinces: {
            type: Array,
            required: true,
            default: () => []
        }
    })

    // Precompute each province's total once — reused by tabs (secondary text)
    // and by the active province's summary card, no repeated reduces on render.
    const provincesWithTotals = computed(() => {
        return props.provinces.map(prov => ({
            ...prov,
            total: prov.indicators.reduce((sum, ind) => sum + (ind.total_4ps_value ?? 0), 0),
        }))
    })

    const selectedProvinceId = ref(props.provinces[0]?.province_id ?? null)

    const activeProvince = computed(() => {
        return provincesWithTotals.value.find(p => p.province_id === selectedProvinceId.value) ?? null
    })

    // Attach each indicator's share of that province's largest indicator,
    // for the mini progress bars.
    const activeIndicators = computed(() => {
        if (!activeProvince.value) return []
        const maxValue = Math.max(...activeProvince.value.indicators.map(ind => ind.total_4ps_value ?? 0), 1)

        return activeProvince.value.indicators.map(ind => ({
            ...ind,
            sharePct: Math.round(((ind.total_4ps_value ?? 0) / maxValue) * 100),
        }))
    })
</script>

<template>
  <div class="w-full flex flex-col gap-5">

    <!-- header -->
    <div class="w-full flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <span class="flex items-center justify-center size-9 bg-[#3F4E2E]/8 text-[#3F4E2E] shrink-0">
                <MapPin class="size-4.5" />
            </span>
            <div class="flex flex-col">
                <span class="text-base font-bold uppercase tracking-tight text-stone-900">Indicator Breakdown</span>
                <span class="text-xs text-stone-400">By province, 4Ps disaggregation</span>
            </div>
        </div>

        <div v-if="activeProvince" class="flex items-center gap-4 bg-[#3F4E2E] px-4 py-2.5 shadow-sm">
            <div class="flex flex-col leading-tight">
                <span class="text-[10px] font-semibold uppercase tracking-wider text-white/70">{{ activeProvince.province_name }} Total</span>
                <span class="text-xl font-extrabold text-white">{{ activeProvince.total.toLocaleString() }}</span>
            </div>
        </div>
    </div>

    <!-- tabs -->
    <div class="w-full flex flex-wrap gap-1 border-b border-stone-200 overflow-x-auto">
        <button
            v-for="prov in provincesWithTotals"
            :key="prov.province_id"
            type="button"
            @click="selectedProvinceId = prov.province_id"
            class="shrink-0 flex flex-col items-start gap-0.5 px-4 py-2.5 border-b-2 -mb-px transition-colors"
            :class="prov.province_id === selectedProvinceId
                ? 'border-[#3F4E2E]'
                : 'border-transparent hover:border-stone-300'"
        >
            <span
                class="text-sm font-semibold uppercase tracking-wide transition-colors"
                :class="prov.province_id === selectedProvinceId ? 'text-[#3F4E2E]' : 'text-stone-500'"
            >
                {{ prov.province_name }}
            </span>
            <span class="text-[11px] font-medium text-stone-400">{{ prov.total.toLocaleString() }} total</span>
        </button>
    </div>

    <!-- indicators grid -->
    <div v-if="activeProvince" class="w-full">
        <div v-if="activeIndicators.length" class="w-full grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            <div
                v-for="ind in activeIndicators"
                :key="ind.indicator_id"
                class="flex flex-col gap-2.5 bg-white border border-stone-200 shadow-sm hover:shadow-md transition-shadow duration-200 p-4"
            >
                <span class="inline-flex items-center gap-1.5 min-w-0">
                    <ListChecks class="size-3.5 text-stone-400 shrink-0" />
                    <span class="text-[11px] font-medium uppercase tracking-wide text-stone-500 truncate" :title="ind.indicator_name">{{ ind.indicator_name }}</span>
                </span>
                <span class="text-2xl font-extrabold text-[#3F4E2E] leading-none">{{ (ind.total_4ps_value ?? 0).toLocaleString() }}</span>
                <div class="w-full h-1.5 bg-stone-100">
                    <div
                        class="h-1.5 bg-[#3F4E2E] transition-all duration-300"
                        :style="{ width: ind.sharePct + '%' }"
                    ></div>
                </div>
            </div>
        </div>

        <div v-else class="w-full flex flex-col items-center gap-2 text-stone-400 py-12">
            <TrendingUp class="size-7 text-stone-300" />
            <span class="text-sm">No indicators recorded for this province.</span>
        </div>
    </div>

    <div v-else class="w-full flex flex-col items-center gap-3 text-stone-400 py-12">
        <Inbox class="size-8 text-stone-300" />
        <span class="text-sm">No provinces available.</span>
    </div>

  </div>
</template>