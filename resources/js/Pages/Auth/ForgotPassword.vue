<script setup lang="ts">
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useI18n } from 'vue-i18n';
import type { ForgotPasswordPageProps } from '@/types';

const page = usePage();
const { t } = useI18n();

const form = useForm<{
    email: string;
}>({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head :title="t('auth.forgot_password') + ' - Ominimo Blog'" />

    <GuestLayout>
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">
                {{ t('auth.forgot_password') }}
            </h2>
            <p class="text-gray-600">
                {{ t('auth.forgot_password_description') }}
            </p>
        </div>
        
        <form @submit.prevent="submit" class="space-y-6">
            <div v-if="$page.props.status" class="success-message">
                {{ $page.props.status }}
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
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="btn-primary w-full text-lg py-3"
                    :class="{ 'loading': form.processing }"
                >
                    <span v-if="form.processing">{{ t('forms.submit') }}...</span>
                    <span v-else>{{ t('auth.send_reset_link') }}</span>
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    {{ t('auth.remember_password') }}
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
