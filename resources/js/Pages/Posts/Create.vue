<template>
    <Head :title="t('posts.create_post') + ' - Ominimo Blog'" />

    <AuthenticatedLayout>
        
        <div class="min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                
                <div class="text-left mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ t('posts.create_post') }}</h1>
                    <p class="text-lg text-gray-600">{{ t('posts.share_thoughts') }}</p>
                </div>

                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <form @submit.prevent="submit" class="p-8">
                        
                        <div class="mb-8">
                            <label for="title" class="block text-sm font-semibold text-gray-900 mb-3">
                                {{ t('forms.title') }}
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="title" 
                                v-model="form.title"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3A30D3] focus:border-[#3A30D3] transition-colors duration-200 text-lg"
                                :class="{ 'border-red-500': form.errors.title }"
                                :placeholder="t('forms.title_placeholder')"
                                required
                            >
                            <div v-if="form.errors.title" class="mt-2 text-sm text-red-600">
                                {{ form.errors.title }}
                            </div>
                        </div>

                        
                        <div class="mb-8">
                            <label for="content" class="block text-sm font-semibold text-gray-900 mb-3">
                                {{ t('forms.content') }}
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="content" 
                                v-model="form.content"
                                rows="8" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3A30D3] focus:border-[#3A30D3] transition-colors duration-200 resize-vertical min-h-[200px]"
                                :class="{ 'border-red-500': form.errors.content }"
                                :placeholder="t('forms.content_placeholder')"
                                required
                            ></textarea>
                            <div v-if="form.errors.content" class="mt-2 text-sm text-red-600">
                                {{ form.errors.content }}
                            </div>
                            <p class="mt-2 text-sm text-gray-500">{{ t('forms.min_characters') }}</p>
                        </div>

                        
                        <div class="flex justify-end">
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-[#3A30D3] to-[#2A20C3] text-white text-sm font-semibold rounded-xl hover:from-[#2A20C3] hover:to-[#1A10B3] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3A30D3] transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ form.processing ? t('forms.creating') + '...' : t('posts.create_post') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <footer class="bg-gray-800 text-white py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-sm text-gray-400 mb-4">
                    {{ t('posts.footer_description') }}
                </p>
                <p class="text-sm text-gray-500">&copy; 2025 Ominimo Blog. {{ t('posts.all_rights_reserved') }}</p>
            </div>
        </footer>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useI18n } from 'vue-i18n';
import type { PostCreatePageProps, CreatePostFormData } from '@/types';

const page = usePage();
const { t } = useI18n();

const form = useForm<CreatePostFormData>({
    title: '',
    content: '',
});

const submit = () => {
    form.post(route('posts.store'));
};
</script>