<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { useForm } from '@inertiajs/vue3';
    import { notifyError, notifySuccess } from '@/composables/useNotify';
    import { hideLoader, showLoader } from '@/composables/useLoading';

    defineOptions({
        layout: Main
    })

    const props = defineProps({
        programmaticIndicator: Object,
        programs: Array
    })

    const form = useForm({
        name: props.programmaticIndicator.name,
        active: props.programmaticIndicator.active,
        scope: props.programmaticIndicator.scope,
        program_id: props.programmaticIndicator.program_id
    })

    function submit() {
        if (!form.name) {
            notifyError('Indicator name field is required.');
            return;
        }

        if (form.active === null || form.active === undefined) {
            notifyError('Status field is required.');
            return;
        }

        form.put(`/prog-indicators/${props.programmaticIndicator.id}/update`, {
            onStart: () => {
                showLoader();
            },
            onSuccess: () => {
                notifySuccess('Update successful');
            },
            onError: () => {
                notifyError('Something went wrong. Please try again or contact the administrator.');
            },
            onFinish: () => {
                hideLoader();
            },
        });
    }
</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-4 p-4">

        <!-- header -->
        <section class="w-full flex flex-col gap-3 pb-3 border-b border-stone-200">
            <span class="text-lg font-bold uppercase tracking-tight text-stone-900">Edit Programmatic Indicator</span>
        </section>

        <section class="w-full flex flex-col justify-start items-start gap-6">
            <div class="w-full flex flex-col justify-start items-start border border-stone-200">
                <div class="w-full flex bg-[#3F4E2E] text-white p-2">
                    <span class="text-[13px] font-semibold uppercase tracking-wide">Indicator Details</span>
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
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Scope</label>
                            <select v-model="form.active" class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors">
                                <option :value="null">Select Status</option>
                                <option :value="1">Active</option>
                                <option :value="0">Disabled</option>
                            </select>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="submit"
                        :disabled="form.processing"
                        class="py-2 px-4 bg-[#3F4E2E] text-white text-sm font-semibold cursor-pointer hover:bg-[#334126] disabled:opacity-60 disabled:cursor-not-allowed transition-colors"
                    >
                        {{ form.processing ? 'Saving…' : 'Save Indicator' }}
                    </button>
                </div>
            </div>
        </section>

    </main>
</template>