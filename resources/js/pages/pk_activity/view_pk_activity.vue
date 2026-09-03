<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { SquarePen, ListChecks, Target, CircleChevronLeft, CircleChevronRight, MapPin, Plus, Users, CalendarDays, FileText, ArrowLeft, FilePlus2, RefreshCw } from '@lucide/vue';
    import { Link, router, useForm } from '@inertiajs/vue3';
    import { onMounted, ref, computed } from 'vue';
    import { pkStatuses } from '@/lib/usePurokalusuganStatuses';
    import { notifyError, notifySuccess } from '@/composables/useNotify';
    import { hideLoader, showLoader } from '@/composables/useLoading';
    import { accessLevels, getAccessLevelLabel } from '@/lib/useAccessLevel';
    import Multiselect from 'vue-multiselect'
    import dayjs from 'dayjs';


    defineOptions({
        layout: Main
    })

    const props = defineProps({
        pk_activity: Object,
    })

    function formatDate(date) {
        return date ? dayjs(date).format('MMM D, YYYY') : '—';
    }

    function statusClasses(status) {
        const s = (status || '').toLowerCase()
        if (s === 'approved') return 'bg-emerald-50 text-emerald-700 border-emerald-200'
        if (s === 'rejected') return 'bg-red-50 text-red-700 border-red-200'
        if (s === 'pending' || s === 'submitted') return 'bg-amber-50 text-amber-700 border-amber-200'
        return 'bg-stone-100 text-stone-600 border-stone-200'
    }

    function resubmitReport(id){
        router.put(`/pk-activities/report/${id}/resubmit`, {}, {
            onStart: () => {
                showLoader()  
            },
            onSuccess: () => {
                notifySuccess('Resubmit Successfull')
            },
            onError: (error) => {
                notifyError('Something went wrong, Please try again or contact adminitrator.')
                console.log(error)
            },
            onFinish: () => {
                hideLoader()  
            },
        })
    }

  
</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-5 p-4 md:p-6 bg-stone-50">

        <!-- back -->
        <Link
            href="/pk-activities"
            class="inline-flex items-center gap-1.5 text-xs font-medium text-stone-400 hover:text-[#3F4E2E] transition-colors w-fit"
        >
            <ArrowLeft class="size-3.5" />
            Back to activities
        </Link>

        <!-- header -->
        <section class="w-full flex flex-col gap-4 bg-white border border-stone-200 shadow-sm p-4 md:p-5">
            <div class="w-full flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                <div class="flex flex-col gap-1.5 min-w-0">
                    <span class="text-lg md:text-xl font-bold uppercase tracking-tight text-stone-900 wrap-break-word">
                        {{ pk_activity.activity_name }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-stone-500">
                        <CalendarDays class="size-3.5 text-stone-400 shrink-0" />
                        {{ formatDate(pk_activity.date_start) }} – {{ formatDate(pk_activity.date_end) }}
                    </span>
                </div>
            </div>

            <div class="w-full flex flex-wrap items-center gap-2 pt-3 border-t border-stone-100">
                <span class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide bg-[#3F4E2E]/8 text-[#3F4E2E] border border-[#3F4E2E]/15">
                    <Target class="size-3" />
                    {{ pk_activity.type }} Scale
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide bg-stone-100 text-stone-600 border border-stone-200">
                    <Users class="size-3" />
                    {{ pk_activity.total_clients }} Total Clients
                </span>
                <span v-if="pk_activity.submitted_by" class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide bg-stone-100 text-stone-600 border border-stone-200">
                    Submitted by {{ pk_activity.submitted_by }}
                </span>
            </div>
        </section>

        <!-- responsive: stacked on mobile, 2-up from lg -->
        <section class="w-full grid grid-cols-1 lg:grid-cols-2 gap-5">

            <!-- hrh participants -->
            <div class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-sm divide-y divide-stone-200">
                <div class="w-full flex items-center justify-between bg-[#3F4E2E] text-white px-3 py-2.5">
                    <span class="text-xs font-semibold uppercase tracking-wide">Activity HRH Participants</span>
                    <span class="text-[10px] font-medium bg-white/10 px-2 py-0.5">{{ pk_activity.hrh?.length ?? 0 }}</span>
                </div>

                <div class="w-full flex flex-col divide-y divide-stone-100 max-h-80 overflow-y-auto">
                    <div
                        v-for="hrh in pk_activity.hrh"
                        :key="hrh.id"
                        class="w-full flex items-center gap-2 px-3 py-2.5 hover:bg-stone-50 transition-colors"
                    >
                        <Users class="size-3.5 text-stone-400 shrink-0" />
                        <span class="text-sm text-stone-700 truncate">{{ hrh.name }}</span>
                    </div>
                    <p v-if="!pk_activity.hrh?.length" class="w-full text-center text-xs text-stone-400 py-8">
                        No HRH participants recorded.
                    </p>
                </div>
            </div>

            <!-- barangays -->
            <div class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-sm divide-y divide-stone-200">
                <div class="w-full flex items-center justify-between bg-[#3F4E2E] text-white px-3 py-2.5">
                    <span class="text-xs font-semibold uppercase tracking-wide">Activity Barangays</span>
                    <span class="text-[10px] font-medium bg-white/10 px-2 py-0.5">{{ pk_activity.barangays?.length ?? 0 }}</span>
                </div>

                <div class="w-full flex flex-col divide-y divide-stone-100 max-h-80 overflow-y-auto">
                    <div
                        v-for="brgy in pk_activity.barangays"
                        :key="brgy.id"
                        class="w-full flex items-center justify-between gap-2 px-3 py-2.5 hover:bg-stone-50 transition-colors"
                    >
                        <span class="flex items-center gap-2 text-sm text-stone-700 min-w-0">
                            <MapPin class="size-3.5 text-stone-400 shrink-0" />
                            <span class="truncate">{{ brgy.name }}, {{ brgy.municipality?.name }}, {{ brgy.province?.name }}</span>
                        </span>
                        <span
                            v-if="brgy.is_host"
                            class="shrink-0 px-1.5 py-0.5 text-[9px] font-semibold uppercase tracking-wide bg-[#3F4E2E]/8 text-[#3F4E2E] border border-[#3F4E2E]/15"
                        >
                            Host
                        </span>
                    </div>
                    <p v-if="!pk_activity.barangays?.length" class="w-full text-center text-xs text-stone-400 py-8">
                        No barangays recorded.
                    </p>
                </div>
            </div>

            <!-- programs -->
            <div class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-sm divide-y divide-stone-200">
                <div class="w-full flex items-center justify-between bg-[#3F4E2E] text-white px-3 py-2.5">
                    <span class="text-xs font-semibold uppercase tracking-wide">Activity Programs</span>
                    <span class="text-[10px] font-medium bg-white/10 px-2 py-0.5">{{ pk_activity.programs?.length ?? 0 }}</span>
                </div>

                <div class="w-full flex flex-col divide-y divide-stone-100 max-h-80 overflow-y-auto">
                    <div
                        v-for="program in pk_activity.programs"
                        :key="program.id"
                        class="w-full flex items-center gap-2 px-3 py-2.5 hover:bg-stone-50 transition-colors"
                    >
                        <ListChecks class="size-3.5 text-stone-400 shrink-0" />
                        <span class="text-sm text-stone-700 truncate">{{ program.name }}</span>
                    </div>
                    <p v-if="!pk_activity.programs?.length" class="w-full text-center text-xs text-stone-400 py-8">
                        No programs recorded.
                    </p>
                </div>
            </div>

        </section>

        <!-- reports: full width, always expanded -->
        <section class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-sm">
            <div class="w-full flex items-center justify-between bg-[#3F4E2E] text-white px-3 py-2.5">
                <span class="text-xs font-semibold uppercase tracking-wide">Activity Reports</span>
                <span class="text-[10px] font-medium bg-white/10 px-2 py-0.5">{{ props.pk_activity.reports.length }}</span>
            </div>

            <!-- summary stats strip -->
            <div v-if="props.pk_activity.reports" class="w-full grid grid-cols-3 divide-x divide-stone-200 border-b border-stone-200 bg-stone-50">
                <div class="flex flex-col items-center gap-0.5 py-3">
                    <span class="text-lg font-bold text-stone-800">{{ props.pk_activity.reports.length }}</span>
                    <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Reports Filed</span>
                </div>
                <div class="flex flex-col items-center gap-0.5 py-3">
                    <span class="text-lg font-bold text-stone-800">{{ props.pk_activity.total_clients }}</span>
                    <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Total Clients</span>
                </div>
            </div>

            <div class="w-full flex flex-col divide-y divide-stone-200">
                <div
                    v-for="(rep, repIndex) in pk_activity.reports"
                    :key="rep.id ?? repIndex"
                    class="w-full flex flex-col gap-3 p-4"
                >
                    <!-- report header -->
                    <div class="w-full flex flex-wrap items-center justify-between gap-2">
                        <span class="inline-flex items-center gap-2">
                            <FileText class="size-4 text-stone-400 shrink-0" />
                            <span class="text-sm font-semibold text-stone-800">{{ formatDate(rep.date) }}</span>
                        </span>
                        <span class="inline-flex items-center gap-2">
                            <span
                                class="px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide border"
                                :class="statusClasses(rep.status)"
                            >
                                {{ rep.status ?? 'Pending' }}
                            </span>
                            <Link
                                :href="`/pk-activities/report/${rep.id}/edit`"
                                title="Edit report"
                                class="flex size-7 border border-stone-200 items-center justify-center text-stone-400 shadow-sm transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md"
                            >
                                <SquarePen class="size-3.5" />
                            </Link>
                            <button
                                @click="resubmitReport(rep.id)"
                                type="button"
                                title="Resubmit report"
                                class="flex size-7 items-center justify-center border border-stone-200 text-stone-400 shadow-sm transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md"
                            >
                                <RefreshCw class="size-3.5" />
                            </button>
                        </span>
                    </div>

                    <!-- stat row -->
                    <div class="w-full grid grid-cols-2 sm:grid-cols-5 gap-3 bg-stone-50 border border-stone-200 px-3 py-2.5">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Barangay</span>
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-stone-700">
                                <MapPin class="size-3 text-stone-400 shrink-0" />
                                {{ `${rep.barangay.name}, ${rep.barangay.municipality?.name}, ${rep.barangay.municipality?.province?.name}` }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Total Clients</span>
                            <span class="text-xs font-medium text-stone-700">{{ rep.total_clients }}</span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Returning Clients</span>
                            <span class="text-xs font-medium text-stone-700">{{ rep.total_returning_clients }}</span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400 wrap-break-word">Submitted By By</span>
                            <span class="text-xs font-medium text-stone-700" v-for="user in rep.users">{{ user.name }}</span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400 wrap-break-word">Approved/Rejected/Resubmitted By</span>
                            <span class="text-xs font-medium text-stone-700">{{ rep.action_by?.name ?? 'Pending' }}</span>
                        </div>
                    </div>

                    <!-- remarks -->
                    <div v-if="rep.remarks && rep.status === 'rejected'" class="w-full flex flex-col gap-0.5 text-rose-400">
                        <span class="text-[10px] font-medium uppercase tracking-wide">Remarks</span>
                        <span class="text-xs italic capitalize">"{{ rep.remarks }}"</span>
                    </div>

                    <!-- indicator breakdown -->
                    <div v-if="rep.values?.length" class="w-full flex flex-col gap-2">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Indicator Breakdown</span>

                        <div class="w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                            <div
                                v-for="value in rep.values"
                                :key="value.id"
                                class="w-full flex flex-col gap-2 p-3 bg-white border border-stone-200"
                            >
                                <div class="w-full flex items-center justify-between gap-2">
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-stone-800 uppercase tracking-wide min-w-0">
                                        <span class="w-1 h-3.5 bg-[#3F4E2E] shrink-0"></span>
                                        <span class="truncate">{{ value.indicator?.name }}</span>
                                    </span>
                                    <span class="shrink-0 text-xs font-bold text-[#3F4E2E]">{{ value.total }}</span>
                                </div>

                                <div class="w-full flex flex-col gap-1 pl-2.5 border-l border-stone-100">
                                    <div
                                        v-for="dis in value.disaggregations"
                                        :key="dis.id"
                                        class="flex items-center justify-between gap-2 text-[11px] text-stone-500"
                                    >
                                        <span class="truncate">{{ dis.disaggregation?.name }}</span>
                                        <span class="font-semibold text-stone-800">{{ dis.value }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <p v-if="!props.pk_activity.reports" class="w-full text-center text-xs text-stone-400 py-10">
                    No reports submitted yet.
                </p>
            </div>
        </section>

    </main>
</template>