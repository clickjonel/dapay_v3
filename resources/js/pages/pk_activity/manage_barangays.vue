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
        barangays: Array
    })

    const selectedBarangay = ref(null)
    const isProcessing = ref(false)

    function addBarangay(){
        if (!selectedBarangay.value || isProcessing.value) return
        isProcessing.value = true

        router.post(`/pk-activities/${props.pk_activity.id}/barangays/add/${selectedBarangay.value.id}`, {}, {
            preserveScroll: true,
            onStart: () => {
                showLoader()
            },
            onSuccess: () => {
                notifySuccess('Barangay added.')
                selectedBarangay.value = null
            },
            onError: (error) => {
                notifyError('Something went wrong, Please try again or contact adminitrator.')
                console.log(error)
            },
            onFinish: () => {
                hideLoader()
                isProcessing.value = false
            },
        })
    }

    function removeBarangay(pivotID){
        if (isProcessing.value) return
        isProcessing.value = true

        router.delete(`/pk-activities/barangays/delete/${pivotID}`, {
            preserveScroll: true,
            onStart: () => {
                showLoader()
            },
            onSuccess: () => {
                notifySuccess('Barangay removed.')
            },
            onError: (error) => {
                notifyError('Something went wrong, Please try again or contact adminitrator.')
                console.log(error)
            },
            onFinish: () => {
                hideLoader()
                isProcessing.value = false
            },
        })
    }

    
</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-5 p-4 md:p-6 bg-stone-50">

        <!-- header -->
        <section class="w-full flex flex-col gap-1 pb-4 border-b border-stone-200">
            <span class="text-xl font-bold uppercase tracking-tight text-stone-900">Manage Barangays</span>
            <span v-if="pk_activity?.activity_name" class="text-xs text-stone-400">{{ pk_activity.activity_name }}</span>
        </section>

        <!-- responsive: stacked on mobile, side-by-side from lg up -->
        <section class="w-full grid grid-cols-1 lg:grid-cols-2 gap-5">

            <!-- current barangays -->
            <div class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-sm divide-y divide-stone-200">
                <div class="w-full flex items-center justify-between bg-[#3F4E2E] text-white px-3 py-2.5">
                    <span class="text-xs font-semibold uppercase tracking-wide">Current Barangays</span>
                    <span class="text-[10px] font-medium bg-white/10 px-2 py-0.5">{{ pk_activity?.barangays?.length ?? 0 }}</span>
                </div>

                <div class="w-full flex flex-col divide-y divide-stone-100 max-h-96 overflow-y-auto">
                    <div
                        v-for="brgy in pk_activity.barangays"
                        :key="brgy.id"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 hover:bg-stone-50 transition-colors"
                    >
                        <span class="flex items-center gap-2 text-sm text-stone-700 min-w-0">
                            <MapPin class="size-3.5 text-stone-400 shrink-0" />
                            <span class="truncate">
                                {{ brgy.name }}, {{ brgy.municipality?.name }}, {{ brgy.municipality?.province?.name }}
                            </span>
                        </span>
                        <button
                            @click="removeBarangay(brgy.pivot.id)"
                            :disabled="isProcessing"
                            class="shrink-0 inline-flex items-center px-2.5 py-1.5 text-[10px] font-semibold uppercase tracking-wide border border-stone-200 text-stone-500 shadow-sm transition-all hover:bg-red-600 hover:border-red-600 hover:text-white hover:shadow-md disabled:opacity-50 disabled:pointer-events-none"
                        >
                            Remove
                        </button>
                    </div>

                    <p v-if="!pk_activity.barangays?.length" class="w-full text-center text-xs text-stone-400 py-8">
                        No barangays added yet.
                    </p>
                </div>
            </div>

            <!-- add barangay -->
            <div class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-sm divide-y divide-stone-200">
                <div class="w-full flex items-center justify-between bg-[#3F4E2E] text-white px-3 py-2.5">
                    <span class="text-xs font-semibold uppercase tracking-wide">Add Barangay</span>
                    <span class="text-[10px] font-medium bg-white/10 px-2 py-0.5">{{ barangays?.length ?? 0 }}</span>
                </div>

                <div class="w-full flex flex-col justify-start items-start gap-3 p-4">
                    <Multiselect
                        v-model="selectedBarangay"
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

                    <button
                        @click="addBarangay"
                        :disabled="!selectedBarangay || isProcessing"
                        class="w-full h-9 inline-flex items-center justify-center gap-1.5 bg-[#3F4E2E] text-white text-xs font-semibold uppercase tracking-wide shadow-sm transition-all hover:shadow-md hover:bg-[#334126] active:shadow-none disabled:opacity-40 disabled:pointer-events-none"
                    >
                        <Plus class="size-3.5" />
                        Add Barangay
                    </button>
                </div>
            </div>

        </section>

    </main>
</template>