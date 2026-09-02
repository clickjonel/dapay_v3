<script setup>
import { ClipboardList, Check, X, Building2, MessageSquare } from '@lucide/vue';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    data: {
        type: Object,
        required: true
    }
})

const selectedReports = ref([])
const remarks = ref('')

const allSelected = computed(() => {
    const reports = props.data.reports || [];
    return reports.length > 0 && selectedReports.value.length === reports.length;
});

function isSelected(id) {
    return selectedReports.value.includes(id);
}

function toggleReport(id) {
    if (isSelected(id)) {
        selectedReports.value = selectedReports.value.filter(r => r !== id);
    } else {
        selectedReports.value.push(id);
    }
}

function toggleAll() {
    if (allSelected.value) {
        selectedReports.value = [];
    } else {
        selectedReports.value = (props.data.reports || []).map(r => r.id);
    }
}

function approve() {
    router.post('/reports/approve', {
        reports: selectedReports.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            selectedReports.value = [];
            remarks.value = '';
        },
    });
}

function reject() {
    router.post('/reports/reject', {
        reports: selectedReports.value,
        remarks: remarks.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            selectedReports.value = [];
            remarks.value = '';
        },
    });
}

function getTotalGida(mun) {
    return (mun.barangays || []).filter(brgy => brgy.is_gida === 1).length;
}

function getTotalPKSites(mun) {
    return (mun.barangays || []).filter(brgy => brgy.is_pk_site === 1).length;
}

function getTotalPKTeams(mun) {
    return (mun.barangays || []).filter(brgy => Boolean(brgy.team)).length;
}
</script>

<template>
     <!-- Access Level 4: DMO Dashboard -->
     <div class="space-y-6">

        <!-- Section 1: Pending Approvals -->
        <div class="border border-stone-200 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-stone-200 px-6 py-4 bg-stone-50">
                <div class="flex items-center gap-2.5">
                    <div class="flex size-8 items-center justify-center border border-[#3F4E2E]/20 bg-[#3F4E2E]/10 text-[#3F4E2E]">
                        <ClipboardList class="size-4" />
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-stone-900">Pending Approvals</h2>
                        <p class="text-xs text-stone-500">Review and act on recently submitted field reports</p>
                    </div>
                </div>
                <span class="border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-800">
                    {{ props.data.reports?.length || 0 }} Action Required
                </span>
            </div>

            <!-- Bulk selection bar: only shows once something is checked -->
            <div v-if="selectedReports.length" class="border-b border-[#3F4E2E]/20 bg-[#3F4E2E]/5 px-6 py-4">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="border border-[#3F4E2E]/30 bg-[#3F4E2E] px-2 py-0.5 text-xs font-bold text-white">
                                {{ selectedReports.length }}
                            </span>
                            <span class="text-sm font-semibold text-stone-900">
                                report{{ selectedReports.length > 1 ? 's' : '' }} selected
                            </span>
                        </div>
                        <label class="mt-2 flex items-start gap-1.5 text-xs text-stone-500">
                            <MessageSquare class="mt-0.5 size-3.5 shrink-0 text-stone-400" />
                            <span>
                                Remarks
                                <span class="text-stone-400">(required to reject)</span>
                            </span>
                        </label>
                        <textarea
                            v-model="remarks"
                            rows="2"
                            placeholder="Add a note explaining this decision…"
                            class="mt-1 w-full max-w-xl border border-stone-300 bg-white px-3 py-2 text-sm text-stone-700 placeholder:text-stone-400 focus:border-[#3F4E2E] focus:outline-none"
                        />
                    </div>

                    <div class="flex shrink-0 gap-2">
                        <button
                            @click="approve"
                            class="inline-flex items-center gap-1.5 border border-[#3F4E2E] bg-[#3F4E2E] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#323f25] active:scale-95 transition-all"
                        >
                            <Check class="size-3.5" /> Approve Selected
                        </button>
                        <button
                            @click="reject"
                            :disabled="!remarks.trim()"
                            class="inline-flex items-center gap-1.5 border border-rose-300 bg-white px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-50 active:scale-95 transition-all disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:bg-white"
                        >
                            <X class="size-3.5" /> Reject Selected
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50 text-xs font-semibold text-stone-500 uppercase tracking-wider">
                            <th scope="col" class="px-6 py-3.5 w-10">
                                <input
                                    type="checkbox"
                                    :checked="allSelected"
                                    @change="toggleAll"
                                    class="size-4 border-stone-300 text-[#3F4E2E] focus:ring-[#3F4E2E]"
                                />
                            </th>
                            <th scope="col" class="px-4 py-3.5">Date</th>
                            <th scope="col" class="px-4 py-3.5">Submitted By</th>
                            <th scope="col" class="px-4 py-3.5">Barangay</th>
                            <th scope="col" class="px-4 py-3.5">Activity</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <tr
                            v-for="report in props.data.reports"
                            :key="report.id"
                            class="transition-colors cursor-pointer"
                            :class="isSelected(report.id) ? 'bg-[#3F4E2E]/5 hover:bg-[#3F4E2E]/10' : 'hover:bg-stone-50'"
                            @click="toggleReport(report.id)"
                        >
                            <td class="px-6 py-4" @click.stop>
                                <input
                                    type="checkbox"
                                    :checked="isSelected(report.id)"
                                    @change="toggleReport(report.id)"
                                    class="size-4 border-stone-300 text-[#3F4E2E] focus:ring-[#3F4E2E]"
                                />
                            </td>
                            <td class="px-4 py-4 font-medium text-stone-900 whitespace-nowrap">{{ report.date }}</td>
                            <td class="px-4 py-4 text-stone-600">
                                {{ report.users?.map(u => u.name).join(', ') }}
                            </td>
                            <td class="px-4 py-4 text-stone-600 font-medium">{{ report.barangay.name }}</td>
                            <td class="px-4 py-4 text-stone-600">
                                <span class="inline-flex items-center border border-stone-200 bg-stone-100 px-2 py-1 text-xs font-medium text-stone-700">
                                    {{ report.activity }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="!props.data.reports?.length">
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-stone-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <ClipboardList class="size-8 text-stone-300" />
                                    <p class="font-medium text-stone-600">No pending approvals</p>
                                    <p class="text-xs text-stone-400">All submitted reports have been processed.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>