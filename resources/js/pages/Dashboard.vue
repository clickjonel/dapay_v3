<script setup>
import Main from '@/layouts/auth/Main.vue';
import { Activity, RefreshCw } from '@lucide/vue';
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

defineOptions({ layout: Main });

const props = defineProps({
    admin: Object,
    dashboardData: Object
});

const isLoading = ref(false);

const loadData = () => {
    isLoading.value = true;
    router.get(route('dashboard'), { access_level: props.admin?.access_level }, {
        preserveState: true,
        only: ['dashboardData'],
        onFinish: () => isLoading.value = false
    });
};

onMounted(() => {
    loadData();
});
</script>

<template>
    <main class="min-h-screen w-full bg-stone-100 p-4">
        <!-- Header -->
        <header class="w-full flex items-center justify-between border border-stone-200 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-stone-900">Welcome Back, Administrator</h1>
                <p class="text-xs text-stone-500">Cordillera Administrative Region · Dashboard</p>
            </div>

            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-stone-100 px-3 py-1.5 text-xs text-stone-600 border border-stone-200">
                    <Activity class="size-3.5 text-[#3F4E2E]" /> Status: Operational
                </span>

                <!-- Simple Load Button -->
                <button 
                    @click="loadData" 
                    :disabled="isLoading"
                    class="inline-flex items-center gap-2 rounded-lg bg-[#3F4E2E] px-4 py-2 text-xs font-medium text-white hover:bg-[#323f25] disabled:opacity-50"
                >
                    <RefreshCw class="size-3.5" :class="{ 'animate-spin': isLoading }" />
                    <span>{{ isLoading ? 'Loading...' : 'Load Data' }}</span>
                </button>
            </div>
        </header>

        <!-- Data Section -->
        <div class="mt-4">
            <div v-if="dashboardData" class="p-4 bg-white border border-stone-200 rounded">
                <pre class="text-xs">{{ dashboardData }}</pre>
            </div>
            <div v-else class="p-4 bg-white border border-stone-200 rounded text-xs text-stone-500">
                Loading data...
            </div>
        </div>
    </main>
</template>