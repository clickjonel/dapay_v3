<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { router } from '@inertiajs/vue3';
    import { hideLoader, showLoader } from '@/composables/useLoading';
    import { notifyError, notifySuccess } from '@/composables/useNotify';
    import { VueDraggable } from 'vue-draggable-plus';
    import { ref } from 'vue';

    defineOptions({
        layout: Main
    })

    const props = defineProps({
        barangay: Object,
        programs: Array,
    })

    // Seed the draggable copy from the barangay's current priority programs
    const priorityProgramsOrdered = ref([...(props.barangay.priority_programs ?? [])])

    function addProgram(prog) {
        if (!prog.target) {
            notifyError('Target Field is Required, Fill it up before submitting.')
            return
        }

        const payload = {
            target: prog.target,
            program_id: prog.id
        }

        router.post(`/barangays/${props.barangay.id}/priority-programs/add`, payload, {
            onStart: () => showLoader('Adding Program, Please Wait...'),
            onSuccess: () => notifySuccess('Program Successfully Added'),
            onError: () => {
                notifyError('Something Went Wrong, Please Try Again or Contact System Administrator')
            },
            onFinish: () => hideLoader()
        })
    }

    function removeProgram(prog) {
        router.delete(`/barangays/${props.barangay.id}/priority-programs/remove`, {
            data: {
                pivot_id: prog.pivot_id,
            },
            onStart: () => showLoader('Removing Program, Please Wait...'),
            onSuccess: () => notifySuccess('Program Successfully Removed'),
            onError: () => {
                notifyError('Something Went Wrong, Please Try Again or Contact System Administrator')
            },
            onFinish: () => hideLoader()
        })
    }

    function updatePriorityProgram(prog) {
        if (!prog.target) {
            notifyError('Target Field is Required, Fill it up before submitting.')
            return
        }

        const payload = {
            target: prog.target,
            pivot_id: prog.pivot_id
        }

        router.put(`/barangays/${props.barangay.id}/priority-programs/update`, payload, {
            onStart: () => showLoader('Updating Program, Please Wait...'),
            onSuccess: () => notifySuccess('Program Successfully Updated'),
            onError: () => {
                notifyError('Something Went Wrong, Please Try Again or Contact System Administrator')
            },
            onFinish: () => hideLoader()
        })
    }

    function saveOrder() {
        const payload = {
            order: priorityProgramsOrdered.value.map((prog, index) => ({
                pivot_id: prog.pivot.id,
                order: index + 1,
            })),
        }

        router.put(`/barangays/${props.barangay.id}/priority-programs/save-order`, payload, {
            onStart: () => showLoader('Saving Order, Please Wait...'),
            onSuccess: () => notifySuccess('Order Successfully Saved'),
            onError: () => {
                notifyError('Something Went Wrong, Please Try Again or Contact System Administrator')
            },
            onFinish: () => hideLoader()
        })
      
    }
</script>


<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-4 sm:gap-6 p-3 sm:p-4 md:p-6 bg-stone-50">

        <!-- header -->
        <section class="w-full flex flex-col justify-start md:justify-between items-start md:items-center gap-2 md:gap-1 pb-4 border-b border-stone-200">
            <div class="flex flex-col">
                <span class="text-base sm:text-lg font-bold uppercase tracking-tight text-stone-900">Set Priority Programs</span>
                <p v-if="barangay?.name" class="text-xs sm:text-sm text-stone-500">{{ barangay.name }}</p>
            </div>
        </section>

        <form @submit.prevent="submit" class="w-full flex flex-col gap-4 sm:gap-6">

            <!-- Add Program -->
            <section class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                <div class="w-full flex justify-start items-start gap-2 bg-[#3F4E2E] text-white px-3 sm:px-4 py-2.5 shadow-sm">
                    <span class="text-xs sm:text-[13px] font-semibold uppercase tracking-wide">Add Priority Programs</span>
                </div>

                <div class="w-full flex flex-col divide-y divide-stone-100">
                    <div
                        v-for="prog in programs" :key="prog.id"
                        class="w-full flex flex-col gap-3 p-3 sm:p-4 transition-colors duration-300"
                        :class="prog.pivot_id ? 'bg-green-100' : 'hover:bg-stone-50'"
                    >
                        <div class="w-full flex items-center gap-2">
                            <span
                                class="w-1.5 h-1.5 rounded-full shrink-0 transition-colors"
                                :class="prog.pivot_id ? 'bg-green-600' : 'bg-stone-300'"
                            ></span>
                            <span class="text-sm font-medium text-stone-800">{{ prog.name }}</span>
                        </div>

                        <div class="w-full flex flex-col sm:flex-row sm:items-end gap-3">
                            <div class="w-full flex flex-col gap-1">
                                <label :for="`target-${prog.id}`" class="text-xs font-medium text-stone-600">Target</label>
                                <input
                                    :id="`target-${prog.id}`"
                                    v-model="prog.target"
                                    type="number"
                                    placeholder="Enter target"
                                    class="w-full outline-none border border-stone-300 bg-white px-3 py-2 text-sm shadow-sm transition-colors focus:border-[#3F4E2E] focus:shadow-md"
                                >
                            </div>

                            <div class="w-full sm:w-auto flex gap-2 shrink-0">
                                <button
                                    v-if="!prog.pivot_id"
                                    type="button"
                                    @click="addProgram(prog)"
                                    class="flex-1 sm:flex-initial h-10 px-4 sm:px-5 border border-green-800 bg-green-900 text-white text-xs font-semibold uppercase tracking-wide shadow-sm hover:shadow-md hover:bg-green-800 active:shadow-none transition-all whitespace-nowrap"
                                >
                                    Add
                                </button>

                                <button
                                    v-if="prog.pivot_id"
                                    type="button"
                                    @click="updatePriorityProgram(prog)"
                                    class="flex-1 sm:flex-initial h-10 px-4 sm:px-5 border border-blue-700 bg-blue-600 text-white text-xs font-semibold uppercase tracking-wide shadow-sm hover:shadow-md hover:bg-blue-700 active:shadow-none transition-all whitespace-nowrap"
                                >
                                    Update
                                </button>

                                <button
                                    v-if="prog.pivot_id"
                                    type="button"
                                    @click="removeProgram(prog)"
                                    class="flex-1 sm:flex-initial h-10 px-4 sm:px-5 border border-rose-800 bg-rose-900 text-white text-xs font-semibold uppercase tracking-wide shadow-sm hover:shadow-md hover:bg-rose-800 active:shadow-none transition-all whitespace-nowrap"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Reorder Priority Programs -->
            <section class="w-full flex flex-col justify-start items-start bg-white border border-stone-200 shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                <div class="w-full flex flex-wrap items-center gap-1 sm:gap-2 bg-[#3F4E2E] text-white px-3 sm:px-4 py-2.5 shadow-sm">
                    <span class="text-xs sm:text-[13px] font-semibold uppercase tracking-wide">Reorder Priority Programs</span>
                    <span class="text-[10px] font-normal normal-case tracking-normal text-white/60 sm:ml-auto">drag to rearrange</span>
                </div>

                <VueDraggable
                    v-model="priorityProgramsOrdered"
                    :animation="200"
                    handle=".drag-handle"
                    ghost-class="drag-ghost"
                    class="w-full flex flex-col divide-y divide-stone-100"
                >
                    <div
                        v-for="(prog, index) in priorityProgramsOrdered"
                        :key="prog.id"
                        class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-3 bg-white hover:bg-stone-50 transition-colors"
                    >
                        <span class="drag-handle flex flex-col gap-0.75 cursor-grab active:cursor-grabbing shrink-0 p-2 -m-2 touch-none">
                            <span class="w-4 h-0.5 bg-stone-300 rounded-full"></span>
                            <span class="w-4 h-0.5 bg-stone-300 rounded-full"></span>
                            <span class="w-4 h-0.5 bg-stone-300 rounded-full"></span>
                        </span>
                        <span class="flex items-center justify-center w-6 h-6 shrink-0 rounded-full bg-[#3F4E2E]/10 text-[#3F4E2E] text-xs font-bold">
                            {{ index + 1 }}
                        </span>
                        <div class="flex flex-col flex-1 min-w-0">
                            <span class="text-sm font-medium text-stone-800 truncate">{{ prog.name }}</span>
                            <span v-if="prog.target" class="text-xs text-stone-400 truncate">Target: {{ prog.target }}</span>
                        </div>
                    </div>
                </VueDraggable>

                <p v-if="!props.barangay.priority_programs.length" class="w-full text-center text-xs text-stone-400 py-8">
                    No priority programs added yet.
                </p>

                 <!-- Save -->
                <div class="w-full flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3 p-3 sm:p-4">
                    <button
                        @click="saveOrder"
                        class="w-full sm:w-auto h-10 px-6 bg-[#3F4E2E] text-white text-xs font-semibold uppercase tracking-wide shadow-sm hover:shadow-md hover:bg-[#334126] active:shadow-none disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer transition-all"
                    >
                        Save Order
                    </button>
                </div>

            </section>

        </form>

    </main>
</template>

<style scoped>
    .drag-ghost {
        opacity: 0.4;
        background-color: #f0f4ed;
    }
</style>