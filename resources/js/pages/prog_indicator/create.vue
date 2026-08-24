<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { SquarePen, ListChecks, Target, CircleChevronLeft, CircleChevronRight } from '@lucide/vue';
    import { Link, router, useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import { pkStatuses } from '@/lib/usePurokalusuganStatuses';
    import { notifyError, notifySuccess } from '@/composables/useNotify';
    import { hideLoader, showLoader } from '@/composables/useLoading';

    defineOptions({
        layout: Main
    })

    const props = defineProps({
        programs: Array
    })

    const form = useForm({
        name: '',
        program_id: null,
        scope: ''
    })

    function submit(){
        if(!form.name || !form.program_id || !form.scope){
            notifyError('All fields are required.')

            return;
        }

        form.post(`/prog-indicators/store`,{
            onStart: () => {
                showLoader()  
            },
            onSuccess: () => {
                notifySuccess('Store Successfull')
            },
            onError: () => {
                notifyError('Something went wrong, Please try again or contact adminitrator.')
            },
            onFinish: () => {
                hideLoader()  
            },
        })
    }


</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-4 p-2">

        <!-- header -->
        <section class="w-full flex flex-col gap-3 border-b">
            <span class="text-lg font-bold uppercase tracking-tight text-stone-900">Add New Programmatic Indicator</span>
        </section>

        <section class="w-full flex flex-col justify-start items-start gap-6">
            <div class="w-full flex flex-col justify-start items-start outline outline-gray-400 shadow-md divide-y">
                <div class="w-full flex bg-[#3F4E2E] text-white p-2">
                    <span class="font-semibold uppercase">Indicator Details</span>
                </div>

                <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                    <div class="w-full flex flex-col md:grid md:grid-cols-1 gap-2">
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Indicator Name</label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors"
                            />
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Programs</label>
                            <select v-model="form.program_id" class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors">
                                <option :value="null">Select Program</option>
                                <option v-for="prog in props.programs" :value="prog.id">{{ prog.name }}</option>
                            </select>
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Scope</label>
                            <select v-model="form.scope" class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors">
                                <option :value="null">Select Scope</option>
                                <option value="Central">Central</option>
                                <option value="Region">Region</option>
                            </select>
                        </div>
                    </div>

                    <button @click="submit" class="py-2 px-4 border bg-[#3F4E2E] text-white cursor-pointer hover:bg-green-900 shadow-md">Save Indicator</button>
                </div>

            </div>

        </section>

    </main>
</template>