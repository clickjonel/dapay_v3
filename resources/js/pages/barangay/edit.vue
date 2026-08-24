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
        barangay: Object,
        provinces: Array,
        municipalities: Array
    })

    const form = useForm(props.barangay)

    function submit(){
        form.put(`/barangays/${props.barangay.id}/update`,{
            onStart: () => {
                showLoader()  
            },
            onSuccess: () => {
                notifySuccess('Update Successfull')
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
            <span class="text-lg font-bold uppercase tracking-tight text-stone-900">Edit Barangay Details</span>
        </section>

        <section class="w-full flex flex-col justify-start items-start gap-6">
            <div class="w-full flex flex-col justify-start items-start outline outline-gray-400 shadow-md divide-y">
                <div class="w-full flex bg-[#3F4E2E] text-white p-2">
                    <span class="font-semibold uppercase">Barangay Details</span>
                </div>

                <div class="w-full flex flex-col justify-start items-start gap-2">
                    <div class="w-full flex flex-col md:grid md:grid-cols-4 gap-2 p-4">
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">PSGC Code</label>
                            <input
                                v-model="form.psgc_code"
                                type="text"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors"
                            />
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Name</label>
                            <input
                               v-model="form.name"
                                type="text"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors"
                            />
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Municipality</label>
                            <select v-model="form.municipality_id" class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors">
                                <option :value="null">Select Municipality</option>
                                <option v-for="mun in municipalities" :value="mun.id">{{ mun.name }}</option>
                            </select>
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Province</label>
                            <select v-model="form.province_id" class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors">
                                <option :value="null">Select Province</option>
                                <option v-for="prov in provinces" :value="prov.id">{{ prov.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="w-full flex flex-col justify-start items-start outline divide-y outline-gray-400 shadow-md">
                <div class="w-full flex bg-[#3F4E2E] text-white p-2">
                    <span class="font-semibold uppercase">Barangay Geography</span>
                </div>

                <div class="w-full flex flex-col justify-start items-start gap-2">
                    <div class="w-full flex flex-col md:grid md:grid-cols-3 gap-2 p-4">
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Longitude</label>
                            <input
                                v-model="form.longitude"
                                type="number"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors"
                            />
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Latitude</label>
                            <input
                               v-model="form.latitude"
                                type="number"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors"
                            />
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">GIDA Area?</label>
                            <select v-model="form.province_id" class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors">
                                <option :value="null">Select</option>
                                <option :value="1">Yes</option>
                                <option :value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full flex flex-col justify-start items-start outline divide-y outline-gray-400 shadow-md">
                <div class="w-full flex bg-[#3F4E2E] text-white p-2">
                    <span class="font-semibold uppercase">Barangay Purokalusugan Status</span>
                </div>

                <div class="w-full flex flex-col justify-start items-start gap-2">
                    <div class="w-full flex flex-col md:grid md:grid-cols-2 gap-2 p-4">
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">PK Site?</label>
                            <select v-model="form.pk_status" class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors">
                                <option :value="null">Select</option>
                                <option v-for="st in pkStatuses" :value="st">{{st}}</option>
                            </select>
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">PK Site?</label>
                            <select v-model="form.pk_site" class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors">
                                <option :value="null">Select</option>
                                <option :value="1">Yes</option>
                                <option :value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full flex flex-col justify-start items-start outline divide-y outline-gray-400 shadow-md">
                <div class="w-full flex bg-[#3F4E2E] text-white p-2">
                    <span class="font-semibold uppercase">Barangay Population</span>
                </div>

                <div class="w-full flex flex-col justify-start items-start gap-2">
                    <div class="w-full flex flex-col md:grid md:grid-cols-2 gap-2 p-4">
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Total Population</label>
                            <input
                                v-model="form.total_population"
                                type="number"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors"
                            />
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Target Population</label>
                            <input
                                v-model="form.target_population"
                                type="number"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors"
                            />
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Total Puroks</label>
                            <input
                                v-model="form.total_puroks"
                                type="number"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors"
                            />
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Target Puroks</label>
                            <input
                                v-model="form.target_puroks"
                                type="number"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors"
                            />
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Total Households</label>
                            <input
                                v-model="form.total_households"
                                type="number"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors"
                            />
                        </div>
                        <div class="w-full flex flex-col gap-1.5">
                            <label for="psgc_code" class="text-[13px] font-medium text-stone-600">Target Households</label>
                            <input
                                v-model="form.target_households"
                                type="number"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none focus:border-[#3F4E2E] transition-colors"
                            />
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <section class="w-full flex justify-start items-start p-4">
            <button @click="submit" class="py-2 px-4 border bg-[#3F4E2E] text-white cursor-pointer hover:bg-green-900 shadow-md">Save Changes</button>
        </section>

    </main>
</template>