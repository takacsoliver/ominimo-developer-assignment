<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-white">
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <Link :href="route('home')" class="flex items-center space-x-3 group">
                            <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                                <span class="text-white font-bold text-xl">O</span>
                            </div>
                            <span class="text-2xl font-bold text-gray-900">Ominimo Blog</span>
                        </Link>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <LanguageSelector />
                        <Link :href="route('login')" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ t('auth.login') }}
                        </Link>
                        <Link :href="route('register')" class="bg-[#3A30D3] text-white hover:bg-[#2A20C3] px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ t('auth.register') }}
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <div class="text-center mb-8">
                    <Link href="/" class="inline-flex items-center space-x-3">
                        <div class="w-16 h-16 gradient-primary rounded-2xl flex items-center justify-center">
                            <span class="text-white font-bold text-2xl">O</span>
                        </div>
                        <span class="text-3xl font-bold text-gray-900">Ominimo Blog</span>
                    </Link>
                </div>

                <div class="card-elevated p-8">
                    <slot />
                </div>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        {{ t('auth.terms_footer_text') }}
                        <a href="#" class="text-[#3A30D3] hover:text-[#2A20C3] font-medium">{{ t('auth.terms_of_service') }}</a>
                        {{ t('auth.and') }}
                        <a href="#" class="text-[#3A30D3] hover:text-[#2A20C3] font-medium">{{ t('auth.privacy_policy') }}</a>
                    </p>
                </div>
            </div>
        </main>
        
        <FlashMessage />
    </div>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue';
import LanguageSelector from '@/Components/LanguageSelector.vue';
import { useI18n } from 'vue-i18n';
import type { PageProps } from '@/types';

const page = usePage();
const { t } = useI18n();
</script>