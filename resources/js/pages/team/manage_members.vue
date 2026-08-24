<script setup>
    import Main from '@/layouts/auth/Main.vue';
    import { SquarePen, ListChecks, Target, CircleChevronLeft, CircleChevronRight, UserPlus, Trash2, Save, Users } from '@lucide/vue';
    import { Link, router, useForm } from '@inertiajs/vue3';
    import { onMounted, ref } from 'vue';
    import { pkStatuses } from '@/lib/usePurokalusuganStatuses';
    import { notifyError, notifySuccess } from '@/composables/useNotify';
    import { hideLoader, showLoader } from '@/composables/useLoading';
    import { accessLevels, getAccessLevelLabel } from '@/lib/useAccessLevel';
    import Multiselect from 'vue-multiselect';
    import { roles } from '@/lib/useTeamMemberRole';

    defineOptions({
        layout: Main
    });

    const props = defineProps({
        team: Object
    });

    const addForm = useForm({
        team_id: props.team.id,
        name: '',
        role: null,
        position: '',
        pk_oriented: null
    });

    function submitAddMember() {
        addForm.post(`/teams/members/add`, {
            onStart: () => showLoader(),
            onSuccess: () => {
                notifySuccess('Member added successfully');
                addForm.reset();
            },
            onError: () => notifyError('Failed to add member. Please check fields.'),
            onFinish: () => hideLoader(),
        });
    }

    function updateMember(member) {
        const updateForm = useForm({
            name: member.name,
            role: member.role,
            position: member.position,
            pk_oriented: member.pk_oriented
        });

        updateForm.put(`/teams/members/${member.id}/update`, {
            onStart: () => showLoader(),
            onSuccess: () => notifySuccess('Member updated successfully'),
            onError: () => notifyError('Failed to update member.'),
            onFinish: () => hideLoader(),
        });
    }

    function removeMember(memberId) {
        if (!confirm('Are you sure you want to remove this member?')) return;

        router.delete(`/teams/members/${memberId}/delete`, {
            onStart: () => showLoader(),
            onSuccess: () => notifySuccess('Member removed successfully'),
            onError: () => notifyError('Failed to remove member.'),
            onFinish: () => hideLoader(),
        });
    }
</script>

<template>
    <main class="w-full h-full flex flex-col justify-start items-start gap-6 p-4 sm:p-6 max-w-7xl mx-auto">

        <!-- Header -->
        <section class="w-full flex items-center justify-between gap-3 pb-4 border-b border-stone-200">
            <div class="flex items-center gap-2">
                <Users class="w-6 h-6 text-[#3F4E2E]" />
                <h1 class="text-xl font-bold uppercase tracking-tight text-stone-900">Edit Team Members</h1>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-stone-100 text-stone-700 rounded-full border border-stone-300">
                Team: {{ props.team.name }}
            </span>
        </section>

        <!-- Add Member Card -->
        <section class="w-full flex flex-col gap-6">
            <div class="w-full flex flex-col bg-white border border-stone-300 shadow-sm overflow-hidden">
                <div class="w-full flex items-center gap-2 bg-[#3F4E2E] text-white px-4 py-3">
                    <UserPlus class="w-4 h-4" />
                    <span class="font-semibold text-sm uppercase tracking-wider">Add New Member</span>
                </div>

                <div class="w-full p-4 sm:p-6 flex flex-col gap-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        <!-- Name -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[13px] font-medium text-stone-700">Member Name</label>
                            <input
                                v-model="addForm.name"
                                type="text"
                                placeholder="Enter full name"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none transition-all focus:border-[#3F4E2E] focus:ring-1 focus:ring-[#3F4E2E]"
                            />
                            <span v-if="addForm.errors.name" class="text-xs text-red-500">{{ addForm.errors.name }}</span>
                        </div>

                        <!-- Role -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[13px] font-medium text-stone-700">Role</label>
                            <select
                                v-model="addForm.role"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none transition-all focus:border-[#3F4E2E] focus:ring-1 focus:ring-[#3F4E2E] bg-white"
                            >
                                <option :value="null">Select Role</option>
                                <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
                            </select>
                            <span v-if="addForm.errors.role" class="text-xs text-red-500">{{ addForm.errors.role }}</span>
                        </div>

                        <!-- Position -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[13px] font-medium text-stone-700">Member Position</label>
                            <input
                                v-model="addForm.position"
                                type="text"
                                placeholder="e.g. Health Worker"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none transition-all focus:border-[#3F4E2E] focus:ring-1 focus:ring-[#3F4E2E]"
                            />
                            <span v-if="addForm.errors.position" class="text-xs text-red-500">{{ addForm.errors.position }}</span>
                        </div>

                        <!-- PK Oriented -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[13px] font-medium text-stone-700">PK Oriented?</label>
                            <select
                                v-model="addForm.pk_oriented"
                                class="w-full h-10 px-3 border border-stone-300 text-sm outline-none transition-all focus:border-[#3F4E2E] focus:ring-1 focus:ring-[#3F4E2E] bg-white"
                            >
                                <option :value="null">Select Option</option>
                                <option :value="1">Yes</option>
                                <option :value="0">No</option>
                            </select>
                            <span v-if="addForm.errors.pk_oriented" class="text-xs text-red-500">{{ addForm.errors.pk_oriented }}</span>
                        </div>

                    </div>

                    <div class="flex justify-end items-center pt-2 border-t border-stone-100">
                        <button
                            @click="submitAddMember"
                            type="button"
                            class="w-full sm:w-auto h-10 px-6 bg-[#3F4E2E] text-white text-xs font-semibold uppercase tracking-wider shadow-sm hover:bg-green-900 active:scale-[0.99] transition-all flex items-center justify-center gap-2"
                        >
                            <UserPlus class="w-4 h-4" />
                            Add Member
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Existing Team Members List Card -->
        <section class="w-full flex flex-col gap-6">
            <div class="w-full flex flex-col bg-white border border-stone-300 shadow-sm overflow-hidden">
                <div class="w-full flex items-center justify-between bg-[#3F4E2E] text-white px-4 py-3">
                    <div class="flex items-center gap-2">
                        <Users class="w-4 h-4" />
                        <span class="font-semibold text-sm uppercase tracking-wider">Current Team Members</span>
                    </div>
                    <span class="text-xs bg-white/20 px-2 py-0.5 font-mono">
                        Total: {{ team.members?.length || 0 }}
                    </span>
                </div>

                <div class="w-full divide-y divide-stone-200">
                    <div 
                        v-for="(mem, index) in team.members" 
                        :key="mem.id || index" 
                        class="p-4 sm:p-6 hover:bg-stone-50/50 transition-colors flex flex-col gap-4"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            
                            <!-- Name -->
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[13px] font-medium text-stone-700">Member Name</label>
                                <input
                                    v-model="mem.name"
                                    type="text"
                                    class="w-full h-10 px-3 border border-stone-300 text-sm outline-none transition-all focus:border-[#3F4E2E] focus:ring-1 focus:ring-[#3F4E2E]"
                                />
                            </div>

                            <!-- Role -->
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[13px] font-medium text-stone-700">Role</label>
                                <select
                                    v-model="mem.role"
                                    class="w-full h-10 px-3 border border-stone-300 text-sm outline-none transition-all focus:border-[#3F4E2E] focus:ring-1 focus:ring-[#3F4E2E] bg-white"
                                >
                                    <option :value="null">Select Role</option>
                                    <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
                                </select>
                            </div>

                            <!-- Position -->
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[13px] font-medium text-stone-700">Member Position</label>
                                <input
                                    v-model="mem.position"
                                    type="text"
                                    class="w-full h-10 px-3 border border-stone-300 text-sm outline-none transition-all focus:border-[#3F4E2E] focus:ring-1 focus:ring-[#3F4E2E]"
                                />
                            </div>

                            <!-- PK Oriented -->
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[13px] font-medium text-stone-700">PK Oriented?</label>
                                <select
                                    v-model="mem.pk_oriented"
                                    class="w-full h-10 px-3 border border-stone-300 text-sm outline-none transition-all focus:border-[#3F4E2E] focus:ring-1 focus:ring-[#3F4E2E] bg-white"
                                >
                                    <option :value="null">Select Option</option>
                                    <option :value="1">Yes</option>
                                    <option :value="0">No</option>
                                </select>
                            </div>

                        </div>

                        <!-- Actions Bar -->
                        <div class="flex flex-col sm:flex-row justify-end items-center gap-2 pt-2">
                            <button
                                @click="removeMember(mem.id)"
                                type="button"
                                class="w-full sm:w-auto h-9 px-4 border border-red-300 text-red-700 bg-red-50 hover:bg-red-100 text-xs font-semibold uppercase tracking-wider transition-all flex items-center justify-center gap-1.5"
                            >
                                <Trash2 class="w-3.5 h-3.5" />
                                Remove Member
                            </button>

                            <button
                                @click="updateMember(mem)"
                                type="button"
                                class="w-full sm:w-auto h-9 px-4 bg-[#3F4E2E] text-white text-xs font-semibold uppercase tracking-wider shadow-sm hover:bg-green-900 transition-all flex items-center justify-center gap-1.5"
                            >
                                <Save class="w-3.5 h-3.5" />
                                Update Details
                            </button>
                        </div>

                    </div>

                    <!-- Empty State -->
                    <div v-if="!team.members || team.members.length === 0" class="p-8 text-center text-stone-500 text-sm">
                        No team members registered yet.
                    </div>
                </div>

            </div>
        </section>

    </main>
</template>