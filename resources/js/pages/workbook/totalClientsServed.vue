<script setup>
import { computed } from 'vue'
import { MapPin, TrendingUp, Target, Users, CheckCircle2 } from '@lucide/vue'
import Main from '@/layouts/auth/Main.vue'

defineOptions({
  layout: Main
})

const props = defineProps({
  provinces: {
    type: Array,
    required: true,
    default: () => []
  }
})

// Calculate percentage for a single province
const calculatePercentage = (served, target) => {
  if (!target || target <= 0) return 0
  const rate = (served / target) * 100
  return Number(rate.toFixed(1))
}

// Sharp green-themed badge logic based on accomplishment percentage
const getStatusBadgeClass = (percentage) => {
  if (percentage >= 100) return 'bg-[#3F4E2E]/10 text-[#3F4E2E] border-[#3F4E2E]/30'
  if (percentage >= 75) return 'bg-emerald-50 text-emerald-800 border-emerald-300'
  return 'bg-stone-100 text-stone-700 border-stone-300'
}

const getProgressBarClass = (percentage) => {
  if (percentage >= 100) return 'bg-[#3F4E2E]'
  if (percentage >= 75) return 'bg-emerald-600'
  return 'bg-stone-400'
}

// Summary Metrics across all provinces
const totals = computed(() => {
  return props.provinces.reduce(
    (acc, prov) => {
      acc.served += Number(prov.total_served || 0)
      acc.targets += Number(prov.total_targets || 0)
      return acc
    },
    { served: 0, targets: 0 }
  )
})

const overallPercentage = computed(() => {
  return calculatePercentage(totals.value.served, totals.value.targets)
})
</script>

<template>
  <div class="w-full flex flex-col gap-5 p-4 sm:p-6 bg-stone-100/60 min-h-screen">
    
    <!-- Page Header -->
    <div class="w-full flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 border border-stone-300 shadow-2xs">
      <div class="flex items-center gap-3">
        <span class="flex items-center justify-center size-10 bg-[#3F4E2E] text-white shrink-0">
          <MapPin class="size-5" />
        </span>
        <div class="flex flex-col">
          <h1 class="text-base font-bold uppercase tracking-tight text-stone-900">Total Clients Served Breakdown</h1>
          <p class="text-xs text-stone-500">Comparative provincial target vs. actual accomplishment report</p>
        </div>
      </div>
    </div>

    <!-- KPI Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="bg-white p-4 border border-stone-300 border-l-4 border-l-stone-500 shadow-2xs flex items-center justify-between">
        <div>
          <span class="text-[11px] font-bold text-stone-500 uppercase tracking-wider block">Total Target</span>
          <span class="text-2xl font-black text-stone-900">{{ totals.targets.toLocaleString() }}</span>
        </div>
        <div class="p-2.5 bg-stone-100 text-stone-700">
          <Target class="size-5" />
        </div>
      </div>

      <div class="bg-white p-4 border border-stone-300 border-l-4 border-l-[#3F4E2E] shadow-2xs flex items-center justify-between">
        <div>
          <span class="text-[11px] font-bold text-[#3F4E2E] uppercase tracking-wider block">Total Served</span>
          <span class="text-2xl font-black text-[#3F4E2E]">{{ totals.served.toLocaleString() }}</span>
        </div>
        <div class="p-2.5 bg-[#3F4E2E]/10 text-[#3F4E2E]">
          <Users class="size-5" />
        </div>
      </div>

      <div class="bg-white p-4 border border-stone-300 border-l-4 border-l-emerald-600 shadow-2xs flex items-center justify-between">
        <div>
          <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider block">Overall Rate</span>
          <span class="text-2xl font-black text-emerald-900">{{ overallPercentage }}%</span>
        </div>
        <div class="p-2.5 bg-emerald-50 text-emerald-700">
          <TrendingUp class="size-5" />
        </div>
      </div>
    </div>

    <!-- Province Data Table -->
    <div class="w-full bg-white border border-stone-300 shadow-2xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-stone-100 border-b border-stone-300 text-xs font-bold text-stone-700 uppercase tracking-wider">
              <th class="py-3.5 px-5 border-r border-stone-200">Province</th>
              <th class="py-3.5 px-5 text-right border-r border-stone-200">Target</th>
              <th class="py-3.5 px-5 text-right border-r border-stone-200">Served</th>
              <th class="py-3.5 px-5 min-w-[240px]">Accomplishment Rate</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-stone-200 text-sm">
            <!-- Empty State -->
            <tr v-if="!props.provinces || props.provinces.length === 0">
              <td colspan="4" class="py-12 text-center text-stone-400 uppercase tracking-wider font-semibold text-xs">
                No provincial report data available.
              </td>
            </tr>

            <!-- Province Rows -->
            <tr 
              v-for="prov in props.provinces" 
              :key="prov.id || prov.name" 
              class="hover:bg-stone-50 transition-colors duration-100"
            >
              <!-- Province Name -->
              <td class="py-3.5 px-5 font-bold text-stone-900 border-r border-stone-100">
                {{ prov.name }}
              </td>

              <!-- Target Value -->
              <td class="py-3.5 px-5 text-right font-medium text-stone-600 border-r border-stone-100">
                {{ Number(prov.total_targets || 0).toLocaleString() }}
              </td>

              <!-- Served Value -->
              <td class="py-3.5 px-5 text-right font-bold text-[#3F4E2E] border-r border-stone-100">
                {{ Number(prov.total_served || 0).toLocaleString() }}
              </td>

              <!-- Percentage & Progress Bar -->
              <td class="py-3.5 px-5">
                <div class="flex flex-col gap-1.5">
                  <div class="flex items-center justify-between text-xs">
                    <span 
                      class="px-2 py-0.5 font-bold border text-[11px] tracking-tight uppercase"
                      :class="getStatusBadgeClass(calculatePercentage(prov.total_served, prov.total_targets))"
                    >
                      {{ calculatePercentage(prov.total_served, prov.total_targets) }}%
                    </span>
                    <span class="text-stone-500 font-medium text-[11px]">
                      {{ Number(prov.total_served || 0).toLocaleString() }} / {{ Number(prov.total_targets || 0).toLocaleString() }}
                    </span>
                  </div>

                  <!-- Sharp Visual Progress Bar -->
                  <div class="w-full h-2 bg-stone-200 overflow-hidden">
                    <div 
                      class="h-full transition-all duration-300"
                      :class="getProgressBarClass(calculatePercentage(prov.total_served, prov.total_targets))"
                      :style="{ width: `${Math.min(calculatePercentage(prov.total_served, prov.total_targets), 100)}%` }"
                    ></div>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</template>