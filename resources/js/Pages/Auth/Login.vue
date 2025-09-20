<script setup lang="ts">
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useI18n } from 'vue-i18n';
import type { LoginPageProps } from '@/types';

const page = usePage();
const { t } = useI18n();

const form = useForm<{
    email: string;
    password: string;
    remember: boolean;
}>({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head :title="t('auth.login') + ' - Ominimo Blog'" />

    <GuestLayout>
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">
                {{ t('auth.login') }}
            </h2>
            <p class="text-gray-600">
                {{ t('auth.sign_in_to_continue') }}
            </p>
        </div>
        
        
        <form @submit.prevent="submit" class="space-y-6">
            
            <div>
                <label for="email" class="form-label">
                    {{ t('auth.email') }}
                </label>
                <input 
                    id="email" 
                    v-model="form.email"
                    type="email" 
                    required 
                    class="form-input"
                    :placeholder="t('auth.enter_email')"
                    autocomplete="email"
                >
                <div v-if="form.errors.email" class="form-error">{{ form.errors.email }}</div>
            </div>

            
            <div>
                <label for="password" class="form-label">
                    {{ t('auth.password') }}
                </label>
                <input 
                    id="password" 
                    v-model="form.password"
                    type="password" 
                    required 
                    class="form-input"
                    :placeholder="t('auth.enter_password')"
                    autocomplete="current-password"
                >
                <div v-if="form.errors.password" class="form-error">{{ form.errors.password }}</div>
            </div>

            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input 
                        id="remember" 
                        v-model="form.remember"
                        type="checkbox" 
                        class="form-checkbox"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-700">
                        {{ t('auth.remember_me') }}
                    </label>
                </div>

                <div class="text-sm">
                    <Link :href="route('password.request')" class="form-link font-medium text-[#3A30D3] hover:text-[#2A20C3]">
                        {{ t('auth.forgot_password') }}
                    </Link>
                </div>
            </div>

            
            <div>
                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="btn-primary w-full text-lg py-3"
                    :class="{ 'loading': form.processing }"
                >
                    <span v-if="form.processing">{{ t('auth.login') }}...</span>
                    <span v-else>{{ t('auth.login') }}</span>
                </button>
            </div>

            
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    {{ t('auth.not_registered') }} 
                    <Link :href="route('register')" class="form-link font-medium text-[#3A30D3] hover:text-[#2A20C3]">
                        {{ t('auth.register') }}
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
</style>
