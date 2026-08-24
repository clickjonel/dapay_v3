<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { SquarePen, ListChecks, Target, CircleChevronLeft, CircleChevronRight } from '@lucide/vue';
    import { Link, router, useForm } from '@inertiajs/vue3';
    import { onMounted, ref } from 'vue';
    import { pkStatuses } from '@/lib/usePurokalusuganStatuses';
    import { notifyError, notifySuccess } from '@/composables/useNotify';
    import { hideLoader, showLoader } from '@/composables/useLoading';
    import { accessLevels, getAccessLevelLabel } from '@/lib/useAccessLevel';


    defineOptions({
        layout: Main
    })

    const props = defineProps({
        provinces: Array
    })


    const form = useForm({
        name: '',
        email: '',
        province_id: null,
        access_level: null,
    })

    function submit(){
        form.post(`/users/store`,{
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
            <span class="text-lg font-bold uppercase tracking-tight text-stone-900">Add New User</span>
        </section>

        <section class="w-full flex flex-col justify-start items-start gap-6">
            <div class="w-full flex flex-col justify-start items-start outline outline-gray-400 shadow-md divide-y">
                <div class="w-full flex bg-[#3F4E2E] text-white p-2">
                    <span class="font-semibold uppercase">User Details</span>
                </div>

                <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                    <div class="w-full flex flex-col md:grid md:grid-cols-1 gap-2">
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="name" class="text-[13px] font-medium text-stone-600">Full Name</label>
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

                    <div class="w-full flex flex-col md:grid md:grid-cols-1 gap-2">
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="email" class="text-[13px] font-medium text-stone-600">Email</label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="w-full h-10 px-3 border text-sm outline-none transition-colors"
                                :class="form.errors.email ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-[#3F4E2E]'"
                            />
                            <span v-if="form.errors.email" class="text-xs text-red-500">{{ form.errors.email }}</span>
                        </div>
                    </div>

                    <div class="w-full flex flex-col gap-1.5">
                        <label for="province_id" class="text-[13px] font-medium text-stone-600">Scope</label>
                        <select
                            id="province_id"
                            v-model="form.province_id"
                            class="w-full h-10 px-3 border text-sm outline-none transition-colors"
                            :class="form.errors.province_id ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-[#3F4E2E]'"
                        >
                            <option :value="null">Select Province</option>
                            <option v-for="prov in props.provinces" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
                        </select>
                        <span v-if="form.errors.province_id" class="text-xs text-red-500">{{ form.errors.province_id }}</span>
                    </div>

                    <div class="w-full flex flex-col gap-1.5">
                        <label for="access_level" class="text-[13px] font-medium text-stone-600">Access Level</label>
                        <select
                            id="access_level"
                            v-model="form.access_level"
                            class="w-full h-10 px-3 border text-sm outline-none transition-colors"
                            :class="form.errors.access_level ? 'border-red-400 focus:border-red-500' : 'border-stone-300 focus:border-[#3F4E2E]'"
                        >
                            <option :value="null">Select Access Level</option>
                            <option v-for="(label, level) in accessLevels" :key="level" :value="Number(level)">
                                {{ label }}
                            </option>
                        </select>
                        <span v-if="form.errors.access_level" class="text-xs text-red-500">{{ form.errors.access_level }}</span>
                    </div>

                    <button
                        @click="submit"
                        type="button"
                        :disabled="form.processing"
                        class="py-2 px-4 border border-[#3F4E2E] bg-[#3F4E2E] text-white cursor-pointer hover:bg-green-900 shadow-md disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                    >
                        {{ form.processing ? 'Submitting...' : 'Submit User' }}
                    </button>
                </div>

            </div>

        </section>

    </main>
</template>