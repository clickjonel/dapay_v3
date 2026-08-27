<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { SquarePen, ListChecks, Target, CircleChevronLeft, CircleChevronRight } from '@lucide/vue';
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
        programs: Array
    })


    function add(programID){
        router.post(`/pk-activities/${props.pk_activity.id}/programs/add/${programID}`,{
            onStart: () => {
                showLoader()  
            },
            onSuccess: () => {
                notifySuccess('Update Successfull')
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

    function remove(pivotID){
        router.delete(`/pk-activities/programs/delete/${pivotID}`,{
            onStart: () => {
                showLoader()  
            },
            onSuccess: () => {
                notifySuccess('Update Successfull')
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
            <span class="text-xl font-bold uppercase tracking-tight text-stone-900">Manage PK Activity Programs</span>
            <span v-if="pk_activity?.activity_name" class="text-xs text-stone-400">{{ pk_activity.activity_name }}</span>
        </section>

        <!-- responsive: stacked on mobile, side-by-side from lg up -->
        <section class="w-full grid grid-cols-1 lg:grid-cols-2 gap-5">

            <!-- current participants -->
            <div class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-sm divide-y divide-stone-200">
                <div class="w-full flex items-center justify-between bg-[#3F4E2E] text-white px-3 py-2.5">
                    <span class="text-xs font-semibold uppercase tracking-wide">Current Participants</span>
                    <span class="text-[10px] font-medium bg-white/10 px-2 py-0.5">{{ pk_activity?.programs?.length ?? 0 }}</span>
                </div>

                <div class="w-full flex flex-col divide-y divide-stone-100 overflow-y-auto">
                    <div
                        v-for="prog in pk_activity.programs"
                        :key="prog.id"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 hover:bg-stone-50 transition-colors"
                    >
                        <span class="flex items-center gap-2 text-sm text-stone-700 min-w-0">
                            <ListChecks class="size-3.5 text-stone-400 shrink-0" />
                            <span class="truncate">{{ prog.name }}</span>
                        </span>
                        <span @click="remove(prog.pivot.id)" class="shrink-0 inline-flex items-center px-2.5 py-1.5 text-[10px] font-semibold uppercase tracking-wide border border-stone-200 text-stone-500 shadow-sm cursor-pointer transition-all hover:bg-red-600 hover:border-red-600 hover:text-white hover:shadow-md">
                            Remove
                        </span>
                    </div>

                    <p v-if="!pk_activity.programs?.length" class="w-full text-center text-xs text-stone-400 py-8">
                        No programs added yet.
                    </p>
                </div>
            </div>

            <!-- add programs -->
            <div class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-sm divide-y divide-stone-200">
                <div class="w-full flex items-center justify-between bg-[#3F4E2E] text-white px-3 py-2.5">
                    <span class="text-xs font-semibold uppercase tracking-wide">Add Programs</span>
                    <span class="text-[10px] font-medium bg-white/10 px-2 py-0.5">{{ programs?.length ?? 0 }}</span>
                </div>

                <div class="w-full flex flex-col divide-y divide-stone-100 max-h-96 overflow-y-auto">
                    <div
                        v-for="prog in programs"
                        :key="prog.id"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 hover:bg-stone-50 transition-colors"
                    >
                        <span class="flex items-center gap-2 text-sm text-stone-700 min-w-0">
                            <ListChecks class="size-3.5 text-stone-400 shrink-0" />
                            <span class="truncate">{{ prog.name }}</span>
                        </span>
                        <span @click="add(prog.id)" class="shrink-0 inline-flex items-center px-2.5 py-1.5 text-[10px] font-semibold uppercase tracking-wide border border-stone-200 text-stone-500 shadow-sm cursor-pointer transition-all hover:bg-[#3F4E2E] hover:border-[#3F4E2E] hover:text-white hover:shadow-md">
                            Add
                        </span>
                    </div>

                    <p v-if="!programs?.length" class="w-full text-center text-xs text-stone-400 py-8">
                        No available programs to add.
                    </p>
                </div>
            </div>

        </section>

    </main>
</template>