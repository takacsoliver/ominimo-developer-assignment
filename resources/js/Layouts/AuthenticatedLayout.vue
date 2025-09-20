<script setup lang="ts">
import { ref } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue';
import LanguageSelector from '@/Components/LanguageSelector.vue';
import { useI18n } from 'vue-i18n';
import type { PageProps, User } from '@/types';

const page = usePage();
const { t } = useI18n();
const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
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
                    
                    <div v-if="$page.props.auth.user" class="flex items-center space-x-4">
                        <LanguageSelector />
                        <div class="relative group">
                            <button class="flex items-center space-x-3 text-sm text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3A30D3] focus:ring-offset-2 rounded-lg p-2 transition-colors">
                                <div class="w-8 h-8 bg-gradient-to-br from-[#3A30D3] to-[#2A20C3] rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">
                                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                    </span>
                                </div>
                                <span class="font-medium">{{ $page.props.auth.user.name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    {{ t('navigation.profile') }}
                                </Link>
                                <form @submit.prevent="logout" class="block">
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        {{ t('auth.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div v-else class="flex items-center space-x-4">
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

        <main class="min-h-screen">
            <slot />
        </main>
        
        <FlashMessage />
    </div>
</template>