<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { 
        Users, 
        ListChecks, 
        MapPin, 
        Package, 
        GraduationCap, 
        CalendarDays, 
        TrendingUp, 
        Trophy, 
        UserCog,
        ChevronRight,
        Activity
    } from '@lucide/vue';
import { onMounted } from 'vue';

    defineOptions({
        layout: Main
    })

    const props = defineProps({
        admin:Object
    })

    // ── Static Mock Data ──

    const stats = [
        { label: 'Total Programs', value: 12, icon: ListChecks, change: '+2 this month', isPositive: true },
        { label: 'Active Teams', value: 84, icon: Users, change: '98% operational', isPositive: true },
        { label: 'Registered Users', value: 342, icon: UserCog, change: '23 pending review', isPositive: false },
    ]

    const usersByAccessLevel = [
        { label: 'Super Admin', value: 4, percentage: '1.1%' },
        { label: 'Provincial Admin', value: 18, percentage: '5.2%' },
        { label: 'Team Leader', value: 84, percentage: '24.5%' },
        { label: 'Health Worker', value: 236, percentage: '69.0%' },
    ]

    const barangaysByProvince = [
        { province: 'Benguet', pkSites: 45, nonPkSites: 32 },
        { province: 'Ifugao', pkSites: 28, nonPkSites: 19 },
        { province: 'Mountain Province', pkSites: 22, nonPkSites: 15 },
        { province: 'Kalinga', pkSites: 19, nonPkSites: 24 },
        { province: 'Apayao', pkSites: 14, nonPkSites: 11 },
    ]

    const teamsPkKits = { withKits: 62, withoutKits: 22 }
    const membersOriented = { oriented: 512, notOriented: 148 }

    const activitiesByProvince = [
        { province: 'Benguet', small: 34, large: 12 },
        { province: 'Ifugao', small: 21, large: 8 },
        { province: 'Mountain Province', small: 18, large: 6 },
        { province: 'Kalinga', small: 15, large: 9 },
        { province: 'Apayao', small: 9, large: 4 },
    ]

    const mostActivePrograms = [
        { name: 'Maternal & Child Health', count: 156, category: 'Healthcare' },
        { name: 'Immunization Services', count: 132, category: 'Preventive' },
        { name: 'Community Nutrition', count: 98, category: 'Wellness' },
        { name: 'Family Planning', count: 76, category: 'Education' },
        { name: 'TB DOTS Program', count: 54, category: 'Specialized' },
    ]

    const topBarangays = [
        { name: 'Poblacion, La Trinidad', province: 'Benguet', count: 24 },
        { name: 'Betag, La Trinidad', province: 'Benguet', count: 21 },
        { name: 'Lagawe Centro, Lagawe', province: 'Ifugao', count: 19 },
        { name: 'Bontoc Ili, Bontoc', province: 'Mountain Province', count: 17 },
        { name: 'Tabuk City Proper', province: 'Kalinga', count: 15 },
    ]

    // ── Dynamic Helpers ──

    function pct(value, max) {
        if (!max) return 0
        return Math.max((value / max) * 100, value > 0 ? 2 : 0)
    }

    const maxUsers = Math.max(...usersByAccessLevel.map(l => l.value), 1)
    const programMax = Math.max(...mostActivePrograms.map(p => p.count), 1)
    const barangayMax = Math.max(...topBarangays.map(b => b.count), 1)

    onMounted(()=>{
        console.log(props.admin)
    })
</script>

<template>
    <main class="min-h-screen w-full bg-stone-100 p-4 sm:p-6 lg:p-8">

        <div class="mx-auto max-w-7xl flex flex-col gap-6">

            <!-- Welcome Header -->
            <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-2">
                        <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Live Overview</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-stone-900">
                        Welcome Back, Administrator
                    </h1>
                    <p class="text-xs sm:text-sm text-stone-500">
                        Cordillera Administrative Region · <span class="font-medium text-stone-700">Purokalusugan Program Dashboard</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-stone-100 px-3 py-1.5 text-xs font-medium text-stone-600 border border-stone-200">
                        <Activity class="size-3.5 text-[#3F4E2E]" /> Status: Operational
                    </span>
                </div>
            </header>

            <!-- Top Level KPIs -->
            <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="group relative flex items-center justify-between rounded-xl border border-stone-200 bg-white p-5 shadow-sm transition-all hover:border-[#3F4E2E]/40 hover:shadow-md">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-stone-400">Teams</span>
                        <span class="text-3xl font-extrabold tracking-tight text-stone-900">{{props.admin.team_total}}</span>
                    </div>
                </div>
                <div class="group relative flex items-center justify-between rounded-xl border border-stone-200 bg-white p-5 shadow-sm transition-all hover:border-[#3F4E2E]/40 hover:shadow-md">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-stone-400">Programs</span>
                        <span class="text-3xl font-extrabold tracking-tight text-stone-900">{{props.admin.program_total}}</span>
                    </div>
                </div>
                <div class="group relative flex items-center justify-between rounded-xl border border-stone-200 bg-white p-5 shadow-sm transition-all hover:border-[#3F4E2E]/40 hover:shadow-md">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-stone-400">Users</span>
                        <span class="text-3xl font-extrabold tracking-tight text-stone-900">{{props.admin.users}}</span>
                    </div>
                </div>
            </section>

            <!-- Main Interactive Data Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left Column (8 cols): Primary Metrics & Geographical Distributions -->
                <div class="lg:col-span-8 flex flex-col gap-6">

                    <!-- Barangays: PK Sites vs Non-PK Sites -->
                    <div class="rounded-xl border border-stone-200 bg-white shadow-sm overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-stone-100 bg-stone-50/50 px-5 py-4 gap-2">
                            <div class="flex items-center gap-2">
                                <div class="p-1.5 rounded-md bg-[#3F4E2E]/10 text-[#3F4E2E]">
                                    <MapPin class="size-4" />
                                </div>
                                <h2 class="text-sm font-bold uppercase tracking-wide text-stone-800">
                                    Barangay Coverage by Province
                                </h2>
                            </div>
                            <div class="flex items-center gap-4 text-xs font-medium text-stone-600">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="size-2.5 rounded-sm bg-[#3F4E2E]"></span> PK Site
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="size-2.5 rounded-sm bg-stone-300"></span> Non-PK Site
                                </span>
                            </div>
                        </div>

                        <div class="p-5 flex flex-col gap-4">
                            <div v-for="row in barangaysByProvince" :key="row.province" class="flex flex-col gap-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-stone-700">{{ row.province }}</span>
                                    <span class="font-medium text-stone-500">
                                        <strong class="text-stone-900">{{ row.pkSites }}</strong> PK / {{ row.pkSites + row.nonPkSites }} Total
                                    </span>
                                </div>
                                <div class="h-3 w-full rounded-md bg-stone-100 flex overflow-hidden p-0.5 border border-stone-200/60">
                                    <div 
                                        class="h-full rounded-xs bg-[#3F4E2E] transition-all duration-500" 
                                        :style="{ width: (row.pkSites / (row.pkSites + row.nonPkSites) * 100) + '%' }"
                                    ></div>
                                    <div 
                                        class="h-full rounded-xs bg-stone-300 transition-all duration-500" 
                                        :style="{ width: (row.nonPkSites / (row.pkSites + row.nonPkSites) * 100) + '%' }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program Scale Disaggregation -->
                    <div class="rounded-xl border border-stone-200 bg-white shadow-sm overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-stone-100 bg-stone-50/50 px-5 py-4 gap-2">
                            <div class="flex items-center gap-2">
                                <div class="p-1.5 rounded-md bg-[#3F4E2E]/10 text-[#3F4E2E]">
                                    <CalendarDays class="size-4" />
                                </div>
                                <h2 class="text-sm font-bold uppercase tracking-wide text-stone-800">
                                    PK Activities Scale Distribution
                                </h2>
                            </div>
                            <div class="flex items-center gap-4 text-xs font-medium text-stone-600">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="size-2.5 rounded-sm bg-[#3F4E2E]"></span> Small Scale
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="size-2.5 rounded-sm bg-amber-500"></span> Large Scale
                                </span>
                            </div>
                        </div>

                        <div class="p-5 flex flex-col gap-4">
                            <div v-for="row in activitiesByProvince" :key="row.province" class="flex flex-col gap-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-stone-700">{{ row.province }}</span>
                                    <span class="text-stone-500">
                                        <span class="font-medium text-[#3F4E2E]">{{ row.small }} Small</span> · 
                                        <span class="font-medium text-amber-600">{{ row.large }} Large</span>
                                    </span>
                                </div>
                                <div class="h-2.5 w-full rounded-md bg-stone-100 flex overflow-hidden border border-stone-200/60">
                                    <div 
                                        class="h-full bg-[#3F4E2E]" 
                                        :style="{ width: (row.small / (row.small + row.large) * 100) + '%' }"
                                    ></div>
                                    <div 
                                        class="h-full bg-amber-500" 
                                        :style="{ width: (row.large / (row.small + row.large) * 100) + '%' }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Operational Readiness Cards (Kits & Orientation Split) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        
                        <!-- Teams PK Kits -->
                        <div class="flex flex-col rounded-xl border border-stone-200 bg-white shadow-sm p-5 justify-between">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <Package class="size-4 text-[#3F4E2E]" />
                                    <h3 class="text-xs font-bold uppercase tracking-wide text-stone-800">PK Kit Provision</h3>
                                </div>
                                <span class="text-xs font-bold text-[#3F4E2E]">73.8% Covered</span>
                            </div>
                            <div class="h-3 w-full rounded-full bg-stone-100 overflow-hidden flex border border-stone-200/60 mb-4">
                                <div class="h-full bg-[#3F4E2E]" :style="{ width: (teamsPkKits.withKits / (teamsPkKits.withKits + teamsPkKits.withoutKits) * 100) + '%' }"></div>
                                <div class="h-full bg-stone-300" :style="{ width: (teamsPkKits.withoutKits / (teamsPkKits.withKits + teamsPkKits.withoutKits) * 100) + '%' }"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-stone-100 text-center">
                                <div class="flex flex-col">
                                    <span class="text-xs text-stone-500">With Kit</span>
                                    <span class="text-lg font-extrabold text-stone-900">{{ teamsPkKits.withKits }}</span>
                                </div>
                                <div class="flex flex-col border-l border-stone-100">
                                    <span class="text-xs text-stone-500">Pending</span>
                                    <span class="text-lg font-extrabold text-stone-400">{{ teamsPkKits.withoutKits }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Members Orientation -->
                        <div class="flex flex-col rounded-xl border border-stone-200 bg-white shadow-sm p-5 justify-between">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <GraduationCap class="size-4 text-[#3F4E2E]" />
                                    <h3 class="text-xs font-bold uppercase tracking-wide text-stone-800">Member Orientation</h3>
                                </div>
                                <span class="text-xs font-bold text-[#3F4E2E]">77.5% Completed</span>
                            </div>
                            <div class="h-3 w-full rounded-full bg-stone-100 overflow-hidden flex border border-stone-200/60 mb-4">
                                <div class="h-full bg-[#3F4E2E]" :style="{ width: (membersOriented.oriented / (membersOriented.oriented + membersOriented.notOriented) * 100) + '%' }"></div>
                                <div class="h-full bg-stone-300" :style="{ width: (membersOriented.notOriented / (membersOriented.oriented + membersOriented.notOriented) * 100) + '%' }"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-stone-100 text-center">
                                <div class="flex flex-col">
                                    <span class="text-xs text-stone-500">Oriented</span>
                                    <span class="text-lg font-extrabold text-stone-900">{{ membersOriented.oriented }}</span>
                                </div>
                                <div class="flex flex-col border-l border-stone-100">
                                    <span class="text-xs text-stone-500">Unoriented</span>
                                    <span class="text-lg font-extrabold text-stone-400">{{ membersOriented.notOriented }}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Right Column (4 cols): User Role Distribution & Key Highlights -->
                <div class="lg:col-span-4 flex flex-col gap-6">

                    <!-- Users by Access Level -->
                    <div class="rounded-xl border border-stone-200 bg-white shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 border-b border-stone-100 bg-stone-50/50 px-5 py-4">
                            <div class="p-1.5 rounded-md bg-[#3F4E2E]/10 text-[#3F4E2E]">
                                <UserCog class="size-4" />
                            </div>
                            <h2 class="text-sm font-bold uppercase tracking-wide text-stone-800">
                                User Breakdown
                            </h2>
                        </div>
                        <div class="p-5 flex flex-col gap-4">
                            <div v-for="level in usersByAccessLevel" :key="level.label" class="flex flex-col gap-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-medium text-stone-700">{{ level.label }}</span>
                                    <span class="font-bold text-stone-900">{{ level.value }} <span class="text-stone-400 font-normal">({{ level.percentage }})</span></span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-stone-100 overflow-hidden">
                                    <div 
                                        class="h-full bg-[#3F4E2E] rounded-full" 
                                        :style="{ width: pct(level.value, maxUsers) + '%' }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Most Active Programs Ranking -->
                    <div class="rounded-xl border border-stone-200 bg-white shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between border-b border-stone-100 bg-stone-50/50 px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="p-1.5 rounded-md bg-[#3F4E2E]/10 text-[#3F4E2E]">
                                    <TrendingUp class="size-4" />
                                </div>
                                <h2 class="text-sm font-bold uppercase tracking-wide text-stone-800">
                                    Top Active Programs
                                </h2>
                            </div>
                        </div>
                        <div class="divide-y divide-stone-100">
                            <div 
                                v-for="(program, index) in mostActivePrograms" 
                                :key="program.name" 
                                class="flex items-center gap-3 p-3.5 hover:bg-stone-50/80 transition-colors"
                            >
                                <span class="flex size-6 shrink-0 items-center justify-center rounded-md bg-stone-100 text-xs font-bold text-stone-600">
                                    {{ index + 1 }}
                                </span>
                                <div class="flex flex-col min-w-0 flex-1">
                                    <span class="text-xs font-semibold text-stone-800 truncate">{{ program.name }}</span>
                                    <span class="text-[10px] text-stone-400">{{ program.category }}</span>
                                </div>
                                <span class="rounded-md bg-[#3F4E2E]/10 px-2 py-1 text-xs font-bold text-[#3F4E2E]">
                                    {{ program.count }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Top Barangays Participating -->
                    <div class="rounded-xl border border-stone-200 bg-white shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between border-b border-stone-100 bg-stone-50/50 px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="p-1.5 rounded-md bg-[#3F4E2E]/10 text-[#3F4E2E]">
                                    <Trophy class="size-4" />
                                </div>
                                <h2 class="text-sm font-bold uppercase tracking-wide text-stone-800">
                                    Top Participating Barangays
                                </h2>
                            </div>
                        </div>
                        <div class="p-4 flex flex-col gap-3">
                            <div v-for="(brgy, index) in topBarangays" :key="brgy.name" class="flex flex-col gap-1">
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-1.5 truncate">
                                        <span class="font-bold text-stone-400">#{{ index + 1 }}</span>
                                        <span class="font-medium text-stone-700 truncate">{{ brgy.name }}</span>
                                    </div>
                                    <span class="font-bold text-stone-900 shrink-0 ml-2">{{ brgy.count }}</span>
                                </div>
                                <div class="h-1.5 w-full rounded-full bg-stone-100 overflow-hidden">
                                    <div 
                                        class="h-full bg-[#3F4E2E] rounded-full" 
                                        :style="{ width: pct(brgy.count, barangayMax) + '%' }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </main>
</template>