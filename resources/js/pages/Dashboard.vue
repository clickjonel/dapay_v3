<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { Activity, MapPinned, FileText, Users, PackageCheck, Boxes, ClipboardList, Building2, Check, X, ChevronRight, ShieldAlert } from '@lucide/vue';
    import { onMounted } from 'vue';
    import { router, usePage } from '@inertiajs/vue3';
    import { showLoader, hideLoader } from '@/composables/useLoading';
    import AdminDashboard from '@/components/dashboard/AdminDashboard.vue';
    import DMODashboard from '@/components/dashboard/DMODashboard.vue';
    import HRHDashboard from '@/components/dashboard/HRHDashboard.vue';
    import PDOHODashboard from '@/components/dashboard/PDOHODashboard.vue';

    defineOptions({ layout: Main });
    const props = defineProps({
        admin: Object,
        dmo: Object,
        data: Object|Array
    });

    const page = usePage()
    const user = page.props?.auth?.user

</script>

<template>
    <main class="w-full flex flex-col gap-4">

            <!-- Header -->
            <header class="flex flex-col gap-4 border border-stone-200/80 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-stone-500">
                        <MapPinned class="size-3.5 text-stone-400" />
                        <span>Cordillera Administrative Region</span>
                    </div>
                    <h1 class="mt-1 text-2xl font-black">
                        Welcome back, {{ user?.name || 'Administrator' }}
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-2 border border-emerald-200 bg-emerald-50/80 px-3 py-1 text-xs font-medium text-emerald-800">
                        <span class="relative flex size-2">
                          <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                        </span>
                        System Operational
                    </span>
                </div>
            </header>

            <!-- Admin Side -->
            <AdminDashboard v-if="user.access_level === 1" :data="props.data" />

            <!-- HRH Side -->
            <HRHDashboard v-else-if="user.access_level === 2" :data="props.data"/>

            <!-- C/PDOHO Side -->
            <PDOHODashboard v-else-if="user.access_level === 3" :data="props.data"/>

            <!-- DMO Side -->
            <DMODashboard v-else="user.access_level === 4"  :data="props.data"/>
    </main>
</template>