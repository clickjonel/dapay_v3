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
        team: Object,
        barangays: Array
    })


    const form = useForm({
        name: props.team.name,
        active: props.team.active,
        pk_kit: props.team.pk_kit,
        eo_link: props.team.eo_link,
        barangay_id: props.barangays.find(b => b.id === props.team.barangay_id) || null
    })

    function submit(){
        form.transform((data) => ({
            ...data,
            barangay_id: data.barangay_id?.id || null
        })).put(`/teams/${props.team.id}/update`, {
            onStart: () => {
                showLoader()  
            },
            onSuccess: () => {
                notifySuccess('Store Successfull')
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
            <span class="text-lg font-bold uppercase tracking-tight text-stone-900">Edit Team</span>
        </section>

        <section class="w-full flex flex-col justify-start items-start gap-6">
            <div class="w-full flex flex-col justify-start items-start outline outline-gray-400 shadow-md divide-y">
                <div class="w-full flex bg-[#3F4E2E] text-white p-2">
                    <span class="font-semibold uppercase">Team Details</span>
                </div>

                <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                    <div class="w-full flex flex-col md:grid md:grid-cols-1 gap-2">
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="name" class="text-[13px] font-medium text-stone-600">Team Name</label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="w-full h-10 px-3 border text-sm outline-none transition-colors"
                                :class="form.errors.name ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-[#3F4E2E]'"
                            />
                            <span v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</span>
                        </div>
                    </div>

                    <div class="w-full flex flex-col gap-1.5">
                        <label for="team_active" class="text-[13px] font-medium text-stone-600">Active</label>
                        <select
                            id="team_active"
                            v-model="form.active"
                            class="w-full h-10 px-3 border text-sm outline-none transition-colors"
                            :class="form.errors.active ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-[#3F4E2E]'"
                        >
                            <option :value="null">Select</option>
                            <option :value="1">Active</option>
                            <option :value="0">Disabled</option>
                        </select>
                        <span v-if="form.errors.active" class="text-xs text-red-500">{{ form.errors.active }}</span>
                    </div>

                    <div class="w-full flex flex-col gap-1.5">
                        <label for="team_pk_kit" class="text-[13px] font-medium text-stone-600">PK Kit?</label>
                        <select
                            id="team_pk_kit"
                            v-model="form.pk_kit"
                            class="w-full h-10 px-3 border text-sm outline-none transition-colors"
                            :class="form.errors.pk_kit ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-[#3F4E2E]'"
                        >
                            <option :value="null">Select</option>
                            <option :value="1">PK Kit Issued</option>
                            <option :value="0">No Issuance</option>
                        </select>
                        <span v-if="form.errors.pk_kit" class="text-xs text-red-500">{{ form.errors.pk_kit }}</span>
                    </div>

                    <div class="w-full flex flex-col md:grid md:grid-cols-1 gap-2">
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="eo_link" class="text-[13px] font-medium text-stone-600">EO Link</label>
                            <input
                                id="eo_link"
                                v-model="form.eo_link"
                                type="text"
                                class="w-full h-10 px-3 border text-sm outline-none transition-colors"
                                :class="form.errors.eo_link ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-[#3F4E2E]'"
                            />
                            <span v-if="form.errors.eo_link" class="text-xs text-red-500">{{ form.errors.eo_link }}</span>
                        </div>
                    </div>

                    <div class="w-full flex flex-col justify-start items-start gap-2">
                        <label for="eo_link" class="text-[13px] font-medium text-stone-600">Barangay</label>
                        <multiselect 
                            v-model="form.barangay_id" 
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
                        ></multiselect>
                        <span v-if="form.errors.barangay_id" class="text-xs text-red-500">{{ form.errors.handledBarangays }}</span>
                    </div>

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