<template>
    <Head :title="t('posts.title')" />

    <AuthenticatedLayout>
        
        <div class="min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                
                <div class="text-center mb-16">
                    <h1 class="text-5xl font-bold text-gray-900 mb-6">
                        {{ t('posts.title') }}
                    </h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        {{ t('posts.discover_stories') }}
                    </p>
                </div>

                
                <div class="mb-12">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">{{ t('posts.latest_posts') }}</h2>
                        <Link :href="route('posts.index')" class="inline-flex items-center px-6 py-3 bg-[#3A30D3] text-white font-medium rounded-lg hover:bg-[#2A20C3] transition-colors duration-200">
                            {{ t('posts.all_posts') }}
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </Link>
                    </div>

                        <div v-if="posts && posts.length > 0">
                        
                        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                            
                            <Link v-if="$page.props.auth.user" :href="route('posts.create')" class="group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 block h-full">
                                <div class="h-full flex flex-col items-center justify-center text-center p-6">
                                    <div class="w-16 h-16 bg-gradient-to-br from-[#3A30D3] to-[#2A20C3] rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300 mb-4">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[#3A30D3] transition-colors duration-200">{{ t('posts.write_post') }}</h3>
                                    <p class="text-gray-600 text-sm">{{ t('posts.click_to_create') }}</p>
                                </div>
                            </Link>

                            
                            <Link v-for="post in posts" :key="post.id" :href="getPostUrl(post.id)" class="group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 block cursor-pointer">
                                <div class="p-6">
                                    
                                    <div class="flex items-center text-sm text-gray-500 mb-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-[#3A30D3] to-[#2A20C3] rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                            {{ post.author.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <span class="font-medium">{{ post.author.name }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ formatDate(post.created_at) }}</span>
                                    </div>
                                    
                                    
                                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#3A30D3] transition-colors duration-200 line-clamp-2">
                                        {{ post.title }}
                                    </h3>
                                    
                                    
                                    <p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                                        {{ stripHtml(post.content).substring(0, 120) }}...
                                    </p>
                                    
                                    
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                {{ post.comments_count || 0 }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="inline-flex items-center text-[#3A30D3] font-medium group-hover:text-[#2A20C3] transition-colors duration-200 cursor-pointer">
                                        {{ t('posts.read_more') }}
                                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <div v-else>
                        
                        <div class="text-center py-20">
                            <div class="max-w-lg mx-auto">
                                <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ t('posts.no_posts_yet') }}</h3>
                                <p class="text-lg text-gray-600 mb-8 leading-relaxed">{{ t('posts.be_first_to_share') }}</p>
                                <Link v-if="!$page.props.auth.user" :href="route('register')" class="btn-primary text-lg px-8 py-4">
                                    {{ t('posts.register_and_create') }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        
        <footer class="bg-gray-800 text-white py-8 mt-16">
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
import { Head, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useI18n } from 'vue-i18n';
import type { HomePageProps, Post } from '@/types';

const page = usePage();
const { t } = useI18n();

const props = defineProps<{
    posts: Post[];
}>();

const formatDate = (date) => {
    const d = new Date(date);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${months[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`;
};

const stripHtml = (html) => {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};

const getPostUrl = (postId) => {
    return `/posts/${postId}`;
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
