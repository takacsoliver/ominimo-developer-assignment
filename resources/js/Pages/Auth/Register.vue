<script setup lang="ts">
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useI18n } from 'vue-i18n';
import type { RegisterPageProps } from '@/types';

const page = usePage();
const { t } = useI18n();

const form = useForm<{
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    terms: boolean;
}>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head :title="t('auth.register') + ' - Ominimo Blog'" />

    <GuestLayout>
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">
                {{ t('auth.register') }}
            </h2>
            <p class="text-gray-600">
                {{ t('auth.create_your_account') }}
            </p>
        </div>
        
        
        <form @submit.prevent="submit" class="space-y-6">
            
            <div>
                <label for="name" class="form-label">
                    {{ t('auth.name') }}
                </label>
                <input 
                    id="name" 
                    v-model="form.name"
                    type="text" 
                    required 
                    class="form-input"
                    :placeholder="t('auth.enter_name')"
                    autocomplete="name"
                >
                <div v-if="form.errors.name" class="form-error">{{ form.errors.name }}</div>
            </div>

            
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
                    :placeholder="t('auth.create_password')"
                    autocomplete="new-password"
                >
                <div v-if="form.errors.password" class="form-error">{{ form.errors.password }}</div>
            </div>

            
            <div>
                <label for="password_confirmation" class="form-label">
                    {{ t('auth.confirm_password') }}
                </label>
                <input 
                    id="password_confirmation" 
                    v-model="form.password_confirmation"
                    type="password" 
                    required 
                    class="form-input"
                    :placeholder="t('auth.confirm_password_placeholder')"
                    autocomplete="new-password"
                >
                <div v-if="form.errors.password_confirmation" class="form-error">{{ form.errors.password_confirmation }}</div>
            </div>

            
            <div class="flex items-start">
                <input 
                    id="terms" 
                    v-model="form.terms"
                    type="checkbox" 
                    required
                    class="form-checkbox mt-1"
                >
                <label for="terms" class="ml-2 text-sm text-gray-700">
                    {{ t('auth.accept_terms') }}
                    <Link href="#" class="form-link text-[#3A30D3] hover:text-[#2A20C3]">{{ t('auth.terms_of_service') }}</Link>
                    {{ t('auth.and') }}
                    <Link href="#" class="form-link text-[#3A30D3] hover:text-[#2A20C3]">{{ t('auth.privacy_policy') }}</Link>
                </label>
            </div>
            <div v-if="form.errors.terms" class="form-error">{{ form.errors.terms }}</div>

            
            <div>
                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="btn-primary w-full text-lg py-3"
                    :class="{ 'loading': form.processing }"
                >
                    <span v-if="form.processing">{{ t('auth.register') }}...</span>
                    <span v-else>{{ t('auth.register') }}</span>
                </button>
            </div>

            
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    {{ t('auth.already_registered') }} 
                    <Link :href="route('login')" class="form-link font-medium text-[#3A30D3] hover:text-[#2A20C3]">
                        {{ t('auth.login') }}
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
</style>
