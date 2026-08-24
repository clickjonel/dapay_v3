<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { useForm } from '@inertiajs/vue3';
    import { hideLoader, showLoader } from '@/composables/useLoading';
    import { notifyError, notifySuccess } from '@/composables/useNotify';

    defineOptions({
        layout: Main
    })

    const props = defineProps({
        barangay: Object,
        indicators: Array,
    })

    const form = useForm({
        indicators: props.indicators
    })

    function submit() {
        form.post(`/barangays/${props.barangay.id}/organizational-indicators/save`, {
            onStart: () => showLoader(),
            onSuccess: () => notifySuccess('Saved Successfully'),
            onError: () => notifyError('Something went wrong, check the form'),
            onFinish: () => hideLoader(),
        })
    }
</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-6 p-4 sm:p-6 bg-stone-50">

        <!-- header -->
        <section class="w-full flex flex-col gap-1 pb-4 border-b border-stone-200">
            <span class="text-lg font-bold uppercase tracking-tight text-stone-900">Set Organizational Indicators</span>
            <p v-if="barangay?.name" class="text-sm text-stone-500">{{ barangay.name }}</p>
        </section>

        <form @submit.prevent="submit" class="w-full flex flex-col gap-6">

            <!-- Indicators -->
            <section class="w-full flex flex-col justify-start items-start gap-3">

                <span class="text-[13px] font-semibold uppercase tracking-wide text-stone-500">Indicators</span>

                <div
                    v-for="(ind, index) in form.indicators"
                    :key="ind.id"
                    class="w-full flex flex-col bg-white border border-stone-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden"
                >
                    <div class="w-full flex items-center bg-[#3F4E2E] text-white px-4 py-2.5">
                        <span class="text-[13px] font-semibold uppercase tracking-wide">{{ ind.name }}</span>
                    </div>

                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-4 p-4">
                        <div class="w-full flex flex-col gap-1">
                            <label :for="`total-${ind.id}`" class="text-xs font-medium text-stone-600">Total</label>
                            <input
                                :id="`total-${ind.id}`"
                                v-model="ind.total"
                                type="number"
                                class="w-full outline-none border px-3 py-2 text-sm transition-colors focus:border-[#3F4E2E]"
                                :class="form.errors[`indicators.${index}.total`] ? 'border-red-400 focus:border-red-500' : 'border-stone-300'"
                            >
                            <span v-if="form.errors[`indicators.${index}.total`]" class="text-xs text-red-500">
                                {{ form.errors[`indicators.${index}.total`] }}
                            </span>
                        </div>

                        <div class="w-full flex flex-col gap-1">
                            <label :for="`community-${ind.id}`" class="text-xs font-medium text-stone-600">Community Based</label>
                            <input
                                :id="`community-${ind.id}`"
                                v-model="ind.community_based"
                                type="number"
                                class="w-full outline-none border px-3 py-2 text-sm transition-colors focus:border-[#3F4E2E]"
                                :class="form.errors[`indicators.${index}.community_based`] ? 'border-red-400 focus:border-red-500' : 'border-stone-300'"
                            >
                            <span v-if="form.errors[`indicators.${index}.community_based`]" class="text-xs text-red-500">
                                {{ form.errors[`indicators.${index}.community_based`] }}
                            </span>
                        </div>
                    </div>
                </div>

                <p v-if="!form.indicators.length" class="w-full text-center text-xs text-stone-400 py-8 bg-white border border-stone-200">
                    No indicators set for this barangay yet.
                </p>
            </section>

            <!-- Save -->
            <div class="w-full flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3">
                <span v-if="Object.keys(form.errors).length" class="text-xs text-red-500 order-2 sm:order-1">
                    Please review the errors above before saving.
                </span>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="h-10 px-6 bg-[#3F4E2E] text-white text-xs font-semibold uppercase tracking-wide shadow-sm hover:shadow-md hover:bg-[#334126] disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer transition-all order-1 sm:order-2"
                >
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                </button>
            </div>
        </form>

    </main>
</template>