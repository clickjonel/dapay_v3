<script setup lang="ts">
    import { Head, useForm, } from '@inertiajs/vue3';
    import PKLogo from '@/assets/logo_purokalusugan_car.png'
    import { notifySuccess, notifyError } from '@/composables/useNotify'

    const form = useForm({
        email: '',
        password: '',
    });

    const submit = () => {
        form.post('login',{
            onSuccess: () => {
                notifySuccess('Login Successfull')
            }
        });
    };
</script>

<template>
    <Head title="Login" />

    <div class="w-full min-h-screen flex justify-center items-center bg-[#F7F4EC] px-5">
        <div class="w-full max-w-100 bg-white/60 border border-[#E8E4D8]">
            <!-- Woven-stripe signature accent -->
            <div class="w-full h-2 flex">
                <span class="flex-1 bg-[#1F3D2B]"></span>
                <span class="flex-1 bg-[#C08A28]"></span>
                <span class="flex-1 bg-[#8B3A2E]"></span>
                <span class="flex-1 bg-[#2B2825]"></span>
            </div>

            <div class="px-8 pt-9 pb-8">
                <!-- Logo / wordmark -->
                <div class="flex flex-col items-center gap-2 pb-7 mb-7 border-b border-[#E8E4D8]">
                    <img :src="PKLogo" alt="Dap-ay" class="size-12 object-contain" />
                    <p class="font-serif font-semibold text-[20px] leading-none tracking-tight text-[#2B2825]">
                        Dap-ay
                    </p>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-[#2B2825]/50">
                        Purokalusugan Cordillera
                    </p>
                </div>

                <form class="flex flex-col gap-5" @submit.prevent="submit">
                    <!-- Email -->
                    <div class="flex flex-col gap-1.5">
                        <label for="email" class="text-[13px] font-medium text-[#2B2825]/80">
                            Email
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            autocomplete="email"
                            autofocus
                            placeholder="you@example.com"
                            class="w-full h-11 border border-[#E8E4D8] bg-white px-3.5 text-[14px] text-[#2B2825] placeholder:text-[#2B2825]/35 outline-none focus:border-[#C08A28] transition-colors"
                        />
                        <p v-if="form.errors.email" class="text-[12px] text-[#8B3A2E]">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Password -->
                    <div class="flex flex-col gap-1.5">
                        <label for="password" class="text-[13px] font-medium text-[#2B2825]/80">
                            Password
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full h-11 border border-[#E8E4D8] bg-white px-3.5 text-[14px] text-[#2B2825] placeholder:text-[#2B2825]/35 outline-none focus:border-[#C08A28] transition-colors"
                        />
                        <p v-if="form.errors.password" class="text-[12px] text-[#8B3A2E]">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full h-11 mt-2 inline-flex items-center justify-center bg-[#C08A28] text-[14px] font-semibold text-[#F7F4EC] tracking-wide hover:bg-[#A5731E] disabled:opacity-60 disabled:cursor-not-allowed transition-colors"
                    >
                        {{ form.processing ? 'Signing in…' : 'Login' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>