<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { SquarePen, ListChecks, Target, CircleChevronLeft, CircleChevronRight, MapPin, Plus } from '@lucide/vue';
    import { Link, router, useForm } from '@inertiajs/vue3';
    import { onMounted, ref } from 'vue';
    import { pkStatuses } from '@/lib/usePurokalusuganStatuses';
    import { notifyError, notifySuccess } from '@/composables/useNotify';
    import { hideLoader, showLoader } from '@/composables/useLoading';
    import { accessLevels, getAccessLevelLabel } from '@/lib/useAccessLevel';
    import Multiselect from 'vue-multiselect'


    defineOptions({
        layout: Main
    })

    const props = defineProps({
        pk_activity: Object,
        users: Array,
        barangays: Array,
        indicators: Array
    })

    const form = useForm({
        report: {
            date: null,
            total_clients: null,
            total_returning_clients: null,
            barangay:null
        },
        indicators: props.indicators.map(ind => ({
            id: ind.id,
            name: ind.name,
            total: null,
            program_id: ind.program?.id,
            disaggregations: ind.disaggregations 
                ? ind.disaggregations.map(dis => ({ 
                    id: dis.id, 
                    name: dis.name,
                    value: null,
                    totalable: dis.pivot.totalable
                })) 
                : []
        })),
        users: []
    })

    function indicatorTotal(ind){
        return ind.disaggregations
            .filter(dis => dis.totalable)
            .reduce((sum, dis) => sum + (Number(dis.value) || 0), 0)
    }

    function submit(){
        form.indicators.forEach(ind => {
            ind.total = indicatorTotal(ind)
        })

        form.transform((data) => {
            const { barangay, ...report } = data.report

            return {
                report: {
                    ...report,
                    barangay_id: barangay?.id ?? null,
                    pk_activity_id: props.pk_activity.id,
                },
                indicators: data.indicators.map(ind => ({
                    id: ind.id,
                    program_id: ind.program_id,
                    total: ind.total,
                    disaggregations: ind.disaggregations.map(dis => ({
                        id: dis.id,
                        value: dis.value ?? 0,
                    })),
                })),
                users: data.users.map(user => user.id),
            }
        }).post('/pk-activities/report/create', {
            onStart: () => {
                showLoader()  
            },
            onSuccess: () => {
                notifySuccess('Store Successfull')
                form.reset()
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

        <!-- header -->
        <section class="w-full flex flex-col gap-1 pb-4 border-b border-stone-200">
            <span class="text-xl font-bold uppercase tracking-tight text-stone-900">Create Activity Report</span>
            <span v-if="pk_activity?.activity_name" class="text-xs text-stone-400">{{ pk_activity.activity_name }}</span>
        </section>

        <section class="w-full flex flex-col gap-5">

            <!-- report details -->
            <div class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-sm divide-y divide-stone-200">
                <div class="w-full flex items-center justify-between bg-[#3F4E2E] text-white px-3 py-2.5">
                    <span class="text-xs font-semibold uppercase tracking-wide">Report Details</span>
                </div>

                <div class="w-full flex flex-col gap-4 p-4">
                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="w-full flex flex-col gap-1.5">
                            <label class="text-[13px] font-medium text-stone-600">Date</label>
                            <input
                                v-model="form.report.date"
                                type="date"
                                class="w-full h-10 px-3 border text-sm text-stone-800 outline-none focus:ring-1 transition-colors"
                                :class="form.errors['report.date'] ? 'border-red-400 focus:border-red-400 focus:ring-red-200' : 'border-stone-300 focus:border-[#3F4E2E] focus:ring-[#3F4E2E]/20'"
                            />
                            <span v-if="form.errors['report.date']" class="text-xs text-red-600">{{ form.errors['report.date'] }}</span>
                        </div>

                        <div class="w-full flex flex-col gap-1.5">
                            <label class="text-[13px] font-medium text-stone-600">Total Clients</label>
                            <input
                                v-model.number="form.report.total_clients"
                                type="number"
                                min="0"
                                placeholder="0"
                                class="w-full h-10 px-3 border text-sm text-stone-800 placeholder:text-stone-400 outline-none focus:ring-1 transition-colors"
                                :class="form.errors['report.total_clients'] ? 'border-red-400 focus:border-red-400 focus:ring-red-200' : 'border-stone-300 focus:border-[#3F4E2E] focus:ring-[#3F4E2E]/20'"
                            />
                            <span v-if="form.errors['report.total_clients']" class="text-xs text-red-600">{{ form.errors['report.total_clients'] }}</span>
                        </div>

                        <div class="w-full flex flex-col gap-1.5">
                            <label class="text-[13px] font-medium text-stone-600">Total Returning Clients</label>
                            <input
                                v-model.number="form.report.total_returning_clients"
                                type="number"
                                min="0"
                                placeholder="0"
                                class="w-full h-10 px-3 border text-sm text-stone-800 placeholder:text-stone-400 outline-none focus:ring-1 transition-colors"
                                :class="form.errors['report.total_returning_clients'] ? 'border-red-400 focus:border-red-400 focus:ring-red-200' : 'border-stone-300 focus:border-[#3F4E2E] focus:ring-[#3F4E2E]/20'"
                            />
                            <span v-if="form.errors['report.total_returning_clients']" class="text-xs text-red-600">{{ form.errors['report.total_returning_clients'] }}</span>
                        </div>
                    </div>

                    <div class="w-full flex flex-col gap-1.5 pt-4 border-t border-stone-100">
                        <label class="text-[13px] font-medium text-stone-600">Barangay</label>
                        <Multiselect
                            v-model="form.report.barangay"
                            :options="props.barangays"
                            :multiple="false"
                            :searchable="true"
                            :close-on-select="true"
                            :show-labels="false"
                            placeholder="Select barangay"
                            aria-label="Select barangay"
                            track-by="id"
                            label="name"
                            class="w-full custom-multiselect"
                        ></Multiselect>
                        <span v-if="form.errors['report.barangay_id']" class="text-xs text-red-600">{{ form.errors['report.barangay_id'] }}</span>
                    </div>
                </div>
            </div>

            <!-- tag users -->
            <div class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-sm divide-y divide-stone-200">
                <div class="w-full flex items-center justify-between bg-[#3F4E2E] text-white px-3 py-2.5">
                    <span class="text-xs font-semibold uppercase tracking-wide">Tag Users</span>
                </div>

                <div class="w-full flex flex-col gap-1.5 p-4">
                    <label class="text-[13px] font-medium text-stone-600">Users</label>
                    <Multiselect
                        v-model="form.users"
                        :options="props.users"
                        :multiple="true"
                        :searchable="true"
                        :close-on-select="true"
                        :show-labels="false"
                        placeholder="Select users"
                        aria-label="Select users"
                        track-by="id"
                        label="name"
                        class="w-full custom-multiselect"
                    ></Multiselect>
                </div>
            </div>

            <!-- indicators -->
            <div class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-sm divide-y divide-stone-200">
                <div class="w-full flex items-center justify-between bg-[#3F4E2E] text-white px-3 py-2.5">
                    <span class="text-xs font-semibold uppercase tracking-wide">Indicators</span>
                </div>

                <div class="w-full flex flex-col divide-y divide-stone-100">
                    <div v-for="(ind, indIndex) in form.indicators" :key="ind.id" class="w-full flex flex-col gap-3 p-4">
                        <span class="inline-flex items-center gap-2 text-sm font-semibold text-stone-800 uppercase tracking-wide">
                            <span class="w-1 h-4 bg-[#3F4E2E] shrink-0"></span>
                            {{ ind.name }}
                        </span>

                        <div class="w-full grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            <div v-for="(dis, disIndex) in ind.disaggregations" :key="dis.id" class="w-full flex flex-col gap-1">
                                <label class="text-[11px] font-medium text-stone-500 uppercase tracking-wide truncate">{{ dis.name }}</label>
                                <input
                                    v-model.number="dis.value"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    class="w-full h-9 px-2.5 border text-sm text-stone-800 placeholder:text-stone-400 outline-none focus:ring-1 transition-colors"
                                    :class="form.errors[`indicators.${indIndex}.disaggregations.${disIndex}.value`] ? 'border-red-400 focus:border-red-400 focus:ring-red-200' : 'border-stone-300 focus:border-[#3F4E2E] focus:ring-[#3F4E2E]/20'"
                                />
                                <span v-if="form.errors[`indicators.${indIndex}.disaggregations.${disIndex}.value`]" class="text-[11px] text-red-600">
                                    {{ form.errors[`indicators.${indIndex}.disaggregations.${disIndex}.value`] }}
                                </span>
                            </div>
                        </div>

                        <div class="w-full flex items-center justify-between bg-stone-50 border border-stone-200 px-3 py-2 mt-1">
                            <span class="text-[11px] font-semibold uppercase tracking-wide text-stone-500">Total</span>
                            <span class="text-sm font-bold text-[#3F4E2E]">{{ indicatorTotal(ind) }}</span>
                        </div>
                    </div>

                    <p v-if="!form.indicators?.length" class="w-full text-center text-xs text-stone-400 py-8">
                        No indicators available for this report.
                    </p>
                </div>
            </div>

            <!-- submit actions -->
            <div class="w-full flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2">
                <button
                    type="button"
                    class="h-10 px-5 border border-stone-300 text-stone-600 text-xs font-semibold uppercase tracking-wide transition-colors hover:bg-stone-100"
                >
                    Cancel
                </button>
                <button
                @click="submit"
                    type="button"
                    class="h-10 px-5 bg-[#3F4E2E] text-white text-xs font-semibold uppercase tracking-wide shadow-sm transition-all hover:shadow-md hover:bg-[#334126] active:shadow-none"
                >
                    Save Report
                </button>
            </div>

        </section>

    </main>
</template>