<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { onMounted } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { hideLoader, showLoader } from '@/composables/useLoading';
    import { notifyError, notifySuccess } from '@/composables/useNotify';

    defineOptions({
        layout: Main
    })

    const props = defineProps({
        indicator: Object,
        currentDisaggregations: Object,
        otherDisaggregations: Object
    })

    function submit(indDisaggregation){
        // router.post(`/prog-indicators/${props.indicator.id}/disaggregations/store`,payload, {
        //         onStart: () => {
        //             showLoader()
        //         },
        //         onSuccess: () => {
        //             notifySuccess('Added Successfully')
        //         },
        //         onError: () => {
        //             notifyError('Something went wrong, Check form or contact system administrator')
        //         },
        //         onFinish: () => {
        //             hideLoader()
        //         },

        //     })
    }

    onMounted(()=>{
        console.log(props.currentDisaggregations)
    })

    function addDisaggregation(id){
        const payload = {
            disaggregation_id: id,
            totalable: false
        }

        router.post(`/prog-indicators/${props.indicator.id}/disaggregations/add`,payload, {
            onStart: () => {
                showLoader()
            },
            onSuccess: () => {
                notifySuccess('Added Successfully')
            },
            onError: () => {
                notifyError('Something went wrong, Check form or contact system administrator')
            },
            onFinish: () => {
                hideLoader()
            },

        })
    }

    function removeDisaggregation(id){
        router.post(`/prog-indicators/${id}/disaggregations/remove`,{}, {
            onStart: () => {
                showLoader()
            },
            onSuccess: () => {
                notifySuccess('Removed Successfully')
            },
            onError: () => {
                notifyError('Something went wrong, Check form or contact system administrator')
            },
            onFinish: () => {
                hideLoader()
            },

        })
    }

    function setDisaggregationTotalable(id){
        router.post(`/prog-indicators/${id}/disaggregations/totalable`,{}, {
            onStart: () => {
                showLoader()
            },
            onSuccess: () => {
                notifySuccess('Updated Successfully')
            },
            onError: () => {
                notifyError('Something went wrong, Check form or contact system administrator')
            },
            onFinish: () => {
                hideLoader()
            },

        })
    }

</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-6 p-6 bg-stone-50">

        <!-- header -->
        <section class="w-full flex flex-col gap-1 pb-4 border-b border-stone-200">
            <span class="text-lg font-bold uppercase tracking-tight text-stone-900">Set Indicator Disaggregations</span>
            <p v-if="indicator?.name" class="text-xs text-stone-400">{{ indicator.name }}</p>
        </section>

        <!-- Adding Disaggregation -->
        <section class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-md hover:shadow-lg transition-shadow overflow-hidden">
            <div class="w-full flex items-center gap-2 bg-[#3F4E2E] text-white px-4 py-2.5 shadow-sm">
                <span class="text-[13px] font-semibold uppercase tracking-wide">Add Disaggregations</span>
            </div>

            <div class="w-full flex flex-col divide-y divide-stone-100">
                <div v-for="(items, group) in props.otherDisaggregations" :key="group" class="w-full flex flex-col">
                   <div class="w-full flex flex-col justify-start items-start border-l-4 border-[#3F4E2E]">
                        <span class="w-full text-xs uppercase font-semibold tracking-wide text-stone-500 px-4 py-2 bg-stone-100">{{ group }}</span>

                        <div v-for="dis in items" :key="dis.id" class="w-full flex justify-between items-center px-4 py-2.5 text-sm text-stone-700 hover:bg-stone-50 transition-colors">
                            <span>{{ dis.name }}</span>
                            <button @click="addDisaggregation(dis.id)" class="text-xs font-medium px-3 py-1 border border-[#3F4E2E] text-[#3F4E2E] shadow-sm hover:shadow-md hover:bg-[#3F4E2E] hover:text-white transition-all">
                                Add
                            </button>
                        </div>
                   </div>
                </div>

                <p v-if="!Object.keys(props.otherDisaggregations || {}).length" class="w-full text-center text-xs text-stone-400 py-6">
                    No disaggregations available to add.
                </p>
            </div>
        </section>

        <!-- Removing Disaggregation -->
        <section class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-md hover:shadow-lg transition-shadow overflow-hidden">
            <div class="w-full flex items-center gap-2 bg-[#3F4E2E] text-white px-4 py-2.5 shadow-sm">
                <span class="text-[13px] font-semibold uppercase tracking-wide">Remove Current Disaggregations</span>
            </div>

            <div class="w-full flex flex-col divide-y divide-stone-100">
                <div v-for="(items, group) in props.currentDisaggregations" :key="group" class="w-full flex flex-col">
                   <div class="w-full flex flex-col justify-start items-start border-l-4 border-[#3F4E2E]">
                        <span class="w-full text-xs uppercase font-semibold tracking-wide text-stone-500 px-4 py-2 bg-stone-100">{{ group }}</span>

                        <div v-for="dis in items" :key="dis.id" class="w-full flex justify-between items-center px-4 py-2.5 text-sm text-stone-700 hover:bg-stone-50 transition-colors">
                            <span>{{ dis.name }}</span>
                            <button @click="removeDisaggregation(dis.pivot?.id)" class="text-xs font-medium px-3 py-1 border border-red-400 text-red-500 shadow-sm hover:shadow-md hover:bg-red-500 hover:text-white transition-all">
                                Remove
                            </button>
                        </div>
                   </div>
                </div>

                <p v-if="!Object.keys(props.currentDisaggregations || {}).length" class="w-full text-center text-xs text-stone-400 py-6">
                    No disaggregations available to remove.
                </p>
            </div>
        </section>

        <!-- Set Totalable Disaggregations -->
        <section class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-md hover:shadow-lg transition-shadow overflow-hidden">
            <div class="w-full flex items-center gap-2 bg-[#3F4E2E] text-white px-4 py-2.5 shadow-sm">
                <span class="text-[13px] font-semibold uppercase tracking-wide">Set Totalable Disaggregations</span>
            </div>

            <div class="w-full flex flex-col divide-y divide-stone-100">
                <div v-for="(items, group) in props.currentDisaggregations" :key="group" class="w-full flex flex-col">
                   <div class="w-full flex flex-col justify-start items-start border-l-4 border-[#3F4E2E]">
                        <span class="w-full text-xs uppercase font-semibold tracking-wide text-stone-500 px-4 py-2 bg-stone-100">{{ group }}</span>

                        <div v-for="dis in items" :key="dis.id" class="w-full flex justify-between items-center px-4 py-2.5 text-sm text-stone-700 bg-green-50/60 hover:bg-green-50 transition-colors">
                            <span>{{ dis.name }}</span>
                            <div class="flex items-center gap-2">
                                <button @click="setDisaggregationTotalable(dis.pivot?.id)" v-if="dis.pivot.totalable === null | dis.pivot.totalable === 0" class="text-xs font-medium px-3 py-1 border border-blue-400 text-blue-500 shadow-sm hover:shadow-md hover:bg-blue-500 hover:text-white transition-all">
                                    Include
                                </button>
                                <button @click="setDisaggregationTotalable(dis.pivot?.id)" v-if="dis.pivot.totalable === null | dis.pivot.totalable === 1" class="text-xs font-medium px-3 py-1 border border-amber-400 text-amber-600 shadow-sm hover:shadow-md hover:bg-amber-500 hover:text-white transition-all">
                                    Remove
                                </button>
                            </div>
                        </div>
                   </div>
                </div>

                <p v-if="!Object.keys(props.currentDisaggregations || {}).length" class="w-full text-center text-xs text-stone-400 py-6">
                    No current disaggregations to set as totalable.
                </p>
            </div>
        </section>

    </main>
</template>