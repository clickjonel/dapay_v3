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
        hrh: Array,
        programs: Array,
        barangays: Array
    })


    const form = useForm({
        activity_name: '',
        date_start: '',
        date_end: '',
        type: '',
        total_clients: '',
        barangays: [],
        hrh: [],
        programs: []
    })

    function submit(){
        form
        .transform((data) => ({
            ...data,
            hrh: data.hrh.map((item) => item.id),
            barangays: data.barangays.map((item) => item.id),
            programs: data.programs.map((item) => item.id),
        }))
        .post(`/pk-activities/store`, {
            onStart: () => {
                showLoader()  
            },
            onSuccess: () => {
                notifySuccess('Added Successfully')
            },
            onError: (error) => {
                notifyError('Something went wrong, Please try again or contact adminitrator.')
                console.log(error)
            },
            onFinish: () => {
                hideLoader()  
            },
        });
    }

    
</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-4 p-2">

        <!-- header -->
        <section class="w-full flex flex-col gap-3 border-b">
            <span class="text-lg font-bold uppercase tracking-tight text-stone-900">Edit PK Activity</span>
        </section>

        <section class="w-full flex flex-col justify-start items-start gap-6">
            <div class="w-full flex flex-col justify-start items-start outline outline-gray-400 shadow-md divide-y">
                <div class="w-full flex bg-[#3F4E2E] text-white p-2">
                    <span class="font-semibold uppercase">Activity Details</span>
                </div>

                <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                    <div class="w-full flex flex-col md:grid md:grid-cols-1 gap-2">
                        <div class="w-full flex flex-col gap-1.5">
                            <label class="text-[13px] font-medium text-stone-600">Activity Name</label>
                            <input
                                v-model="form.activity_name"
                                type="text"
                                class="w-full h-10 px-3 border text-sm outline-none transition-colors"
                                :class="form.errors.activity_name ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-[#3F4E2E]'"
                            />
                            <span v-if="form.errors.activity_name" class="text-xs text-red-500">{{ form.errors.activity_name }}</span>
                        </div>
                    </div>

                    <div class="w-full flex flex-col md:grid md:grid-cols-1 gap-2">
                        <div class="w-full flex flex-col gap-1.5">
                            <label class="text-[13px] font-medium text-stone-600">Activity Start Date</label>
                            <input
                                v-model="form.date_start"
                                type="date"
                                class="w-full h-10 px-3 border text-sm outline-none transition-colors"
                                :class="form.errors.date_start ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-[#3F4E2E]'"
                            />
                            <span v-if="form.errors.date_start" class="text-xs text-red-500">{{ form.errors.date_start }}</span>
                        </div>
                    </div>

                    <div class="w-full flex flex-col md:grid md:grid-cols-1 gap-2">
                        <div class="w-full flex flex-col gap-1.5">
                            <label class="text-[13px] font-medium text-stone-600">Activity End Date</label>
                            <input
                                v-model="form.date_end"
                                type="date"
                                class="w-full h-10 px-3 border text-sm outline-none transition-colors"
                                :class="form.errors.date_end ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-[#3F4E2E]'"
                            />
                            <span v-if="form.errors.date_end" class="text-xs text-red-500">{{ form.errors.date_end }}</span>
                        </div>
                    </div>

                    <div class="w-full flex flex-col gap-1.5">
                        <label class="text-[13px] font-medium text-stone-600">PK Kit?</label>
                        <select
                            v-model="form.type"
                            class="w-full h-10 px-3 border text-sm outline-none transition-colors"
                            :class="form.errors.type ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-[#3F4E2E]'"
                        >
                            <option :value="null">Select</option>
                            <option value="large">Large Scale</option>
                            <option value="small">Small Scale</option>
                            <option value="small">Others</option>
                        </select>
                        <span v-if="form.errors.type" class="text-xs text-red-500">{{ form.errors.type }}</span>
                    </div>

                    <div class="w-full flex flex-col md:grid md:grid-cols-1 gap-2">
                        <div class="w-full flex flex-col gap-1.5">
                            <label class="text-[13px] font-medium text-stone-600">Total Clients</label>
                            <input
                                v-model="form.total_clients"
                                type="number"
                                class="w-full h-10 px-3 border text-sm outline-none transition-colors"
                                :class="form.errors.total_clients ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-[#3F4E2E]'"
                            />
                            <span v-if="form.errors.total_clients" class="text-xs text-red-500">{{ form.errors.total_clients }}</span>
                        </div>
                    </div>

                </div>

            </div>

        </section>

        <section class="w-full flex flex-col justify-start items-start gap-6">
            <div class="w-full flex flex-col justify-start items-start outline outline-gray-400 shadow-md divide-y">
                <div class="w-full flex bg-[#3F4E2E] text-white p-2">
                    <span class="font-semibold uppercase">Participating HRH</span>
                </div>

                <div class="w-full flex flex-col gap-1.5 pt-4 border-t border-stone-100 p-4">
                    <label class="text-[13px] font-medium text-stone-600">Select HRH Participants</label>
                    <Multiselect
                        v-model="form.hrh"
                        :options="props.hrh"
                        :multiple="true"
                        :searchable="true"
                        :close-on-select="false"
                        :show-labels="false"
                        placeholder="Select HRH"
                        aria-label="Select HRH"
                        track-by="id"
                        label="name"
                        class="w-full custom-multiselect"
                    ></Multiselect>
                    <span v-if="form.errors.hrh" class="text-xs text-red-600">{{ form.errors.hrh }}</span>
                </div>

            </div>

        </section>

        <section class="w-full flex flex-col justify-start items-start gap-6">
            <div class="w-full flex flex-col justify-start items-start outline outline-gray-400 shadow-md divide-y">
                <div class="w-full flex bg-[#3F4E2E] text-white p-2">
                    <span class="font-semibold uppercase">Participating Barangays</span>
                </div>

                <div class="w-full flex flex-col gap-1.5 pt-4 border-t border-stone-100 p-4">
                    <label class="text-[13px] font-medium text-stone-600">Select Barangays</label>
                    <Multiselect
                        v-model="form.barangays"
                        :options="props.barangays"
                        :multiple="true"
                        :searchable="true"
                        :close-on-select="false"
                        :show-labels="false"
                        placeholder="Select barangays"
                        aria-label="Select barangays"
                        track-by="id"
                        label="name"
                        class="w-full custom-multiselect"
                    ></Multiselect>
                    <span v-if="form.errors.barangays" class="text-xs text-red-600">{{ form.errors.barangays }}</span>
                </div>

            </div>

        </section>

        <section class="w-full flex flex-col justify-start items-start gap-6">
            <div class="w-full flex flex-col justify-start items-start outline outline-gray-400 shadow-md divide-y">
                <div class="w-full flex bg-[#3F4E2E] text-white p-2">
                    <span class="font-semibold uppercase">Participating Programs</span>
                </div>

                <div class="w-full flex flex-col gap-1.5 pt-4 border-t border-stone-100 p-4">
                    <label class="text-[13px] font-medium text-stone-600">Select Programs Participating</label>
                    <Multiselect
                        v-model="form.programs"
                        :options="props.programs"
                        :multiple="true"
                        :searchable="true"
                        :close-on-select="false"
                        :show-labels="false"
                        placeholder="Select Programs"
                        aria-label="Select Programs"
                        track-by="id"
                        label="name"
                        class="w-full custom-multiselect"
                    ></Multiselect>
                    <span v-if="form.errors.programs" class="text-xs text-red-600">{{ form.errors.programs }}</span>
                </div>

            </div>

        </section>

        <button
            @click="submit"
            type="button"
            class="py-2 px-4 border border-[#3F4E2E] bg-[#3F4E2E] text-white cursor-pointer hover:bg-green-900 shadow-md disabled:opacity-50 disabled:cursor-not-allowed transition-all"
        >
            Save Changes
        </button>

    </main>
</template>