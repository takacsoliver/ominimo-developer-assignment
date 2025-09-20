<template>
    <Head :title="`${post.title} - Ominimo Blog`" />

    <AuthenticatedLayout>
        <div class="min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
                    <Link :href="route('home')" class="hover:text-[#3A30D3] transition-colors">{{ t('navigation.home') }}</Link>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <Link :href="route('posts.index')" class="hover:text-[#3A30D3] transition-colors">{{ t('navigation.posts') }}</Link>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-900">{{ post.title.length > 50 ? post.title.substring(0, 50) + '...' : post.title }}</span>
                </nav>

                <article class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-100">
                        <h1 class="text-4xl font-bold text-gray-900 mb-6 leading-tight">{{ post.title }}</h1>
                        
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#3A30D3] to-[#2A20C3] rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-lg">
                                    {{ post.author.name.charAt(0).toUpperCase() }}
                                </span>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ post.author.name }}</div>
                                <div class="text-sm text-gray-500">{{ formatDate(post.created_at) }}</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-6 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    {{ post.comments_count || 0 }} {{ t('posts.comments') }}
                                </div>
                            </div>
                            
                            <div v-if="isPostOwner() || isAdmin()" class="flex items-center space-x-2">
                                <Link 
                                    v-if="isPostOwner()"
                                    :href="route('posts.edit', props.post.id)"
                                    class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-3 py-2 rounded-lg transition-colors text-sm font-medium"
                                    :title="t('posts.edit_post')"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </Link>
                                
                                <button 
                                    v-if="isPostOwner()"
                                    @click="deletePost"
                                    class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors text-sm font-medium"
                                    :title="t('posts.delete_post')"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                                
                                <button 
                                    v-if="isAdmin() && !isPostOwner()"
                                    @click="adminDeletePost"
                                    class="text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors text-sm font-medium"
                                    :title="t('posts.delete_post') + ' (Admin)'"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="prose prose-lg max-w-none">
                            <div>{{ stripHtml(post.content) }}</div>
                        </div>
                    </div>
                </article>

                <div class="mt-12">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ t('posts.comments') }}</h3>
                        
                        <div class="mb-8">
                            <form @submit.prevent="submitComment" class="space-y-4">
                                <div>
                                    <label for="comment" class="form-label">{{ t('comments.your_comment') }}</label>
                                    <textarea 
                                        id="comment"
                                        v-model="form.comment"
                                        rows="4"
                                        class="form-input"
                                        :placeholder="t('comments.comment_text')"
                                        required
                                    ></textarea>
                                    <div v-if="form.errors.comment" class="form-error">{{ form.errors.comment }}</div>
                                </div>

                                <div v-if="!$page.props.auth.user" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="guest_name" class="form-label">{{ t('comments.guest_name') }}</label>
                                        <input 
                                            id="guest_name"
                                            v-model="form.guest_name"
                                            type="text"
                                            class="form-input"
                                            :placeholder="t('comments.guest_name_placeholder')"
                                            required
                                        />
                                        <div v-if="form.errors.guest_name" class="form-error">{{ form.errors.guest_name }}</div>
                                    </div>
                                    <div>
                                        <label for="guest_email" class="form-label">{{ t('comments.guest_email') }}</label>
                                        <input 
                                            id="guest_email"
                                            v-model="form.guest_email"
                                            type="email"
                                            class="form-input"
                                            :placeholder="t('comments.guest_email_placeholder')"
                                            required
                                        />
                                        <div v-if="form.errors.guest_email" class="form-error">{{ form.errors.guest_email }}</div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div></div>
                                    <button 
                                        type="submit" 
                                        :disabled="form.processing"
                                        class="btn-primary"
                                        :class="{ 'loading': form.processing }"
                                    >
                                        <span v-if="form.processing">{{ t('forms.submit') }}...</span>
                                        <span v-else>{{ t('comments.submit_comment') }}</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div v-if="post.comments && post.comments.length > 0">
                            <div class="space-y-6">
                                <div v-for="comment in post.comments" :key="comment.id" class="border-b border-gray-100 pb-6 last:border-b-0">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-[#3A30D3] to-[#2A20C3] rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-semibold text-sm">
                                                {{ comment.author.name.charAt(0).toUpperCase() }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-medium text-gray-900">
                                                        {{ comment.author.name }}
                                                    </span>
                                                    <span class="text-sm text-gray-500">
                                                        {{ formatDate(comment.created_at) }}
                                                    </span>
                                                </div>
                                                
                                                <div v-if="isCommentOwner(comment) || isAdmin()" class="flex items-center space-x-2">
                                                    <button 
                                                        v-if="isCommentOwner(comment)"
                                                        @click="deleteComment(comment.id)"
                                                        class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1 rounded transition-colors"
                                                        :title="t('comments.delete')"
                                                    >
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                    
                                                    <button 
                                                        v-if="isAdmin() && !isCommentOwner(comment)"
                                                        @click="adminDeleteComment(comment.id)"
                                                        class="text-red-400 hover:text-red-600 hover:bg-red-50 p-1 rounded transition-colors"
                                                        :title="t('comments.delete') + ' (Admin)'"
                                                    >
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="text-gray-700">{{ comment.comment }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div v-else class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">{{ t('comments.no_comments_yet') }}</p>
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
import { Head, useForm, usePage, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useI18n } from 'vue-i18n';
import type { PostShowPageProps, Post, Comment, User } from '@/types';

const props = defineProps<{
    post: Post;
}>();

const page = usePage();
const { t } = useI18n();

const isAdmin = () => {
    return page.props.auth.user && page.props.auth.user.roles && 
           page.props.auth.user.roles.some(role => role.name === 'admin');
};

const isPostOwner = () => {
    return page.props.auth.user && page.props.auth.user.id === props.post.author.id;
};

const isCommentOwner = (comment: Comment): boolean => {
    return page.props.auth.user && page.props.auth.user.id === comment.author.id;
};

const form = useForm<{
    comment: string;
    guest_name?: string;
    guest_email?: string;
}>({
    comment: '',
    guest_name: '',
    guest_email: '',
});

const submitComment = () => {
        form.post(route('posts.comments.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: (errors) => {
        },
    });
};

const deletePost = () => {
    if (confirm(t('auth.confirm_delete_post'))) {
        router.delete(route('posts.destroy', props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                router.visit(route('posts.index'));
            },
        });
    }
};

const deleteComment = (commentId) => {
    if (confirm(t('auth.confirm_delete_comment'))) {
        router.delete(route('posts.comments.destroy', [props.post.id, commentId]), {
            preserveScroll: true,
            onSuccess: () => {
                router.reload({ only: ['post'] });
            },
        });
    }
};

const adminDeletePost = () => {
    if (confirm(t('auth.confirm_admin_delete_post'))) {
        router.delete(route('admin.posts.destroy', props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                router.visit(route('posts.index'));
            },
        });
    }
};

const adminDeleteComment = (commentId) => {
    if (confirm(t('auth.confirm_admin_delete_comment'))) {
        router.delete(route('admin.comments.destroy', commentId), {
            preserveScroll: true,
            onSuccess: () => {
                router.reload({ only: ['post'] });
            },
        });
    }
};

const formatDate = (date) => {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = d.getMonth() + 1;
    const day = d.getDate();
    const hours = d.getHours()?.toString().padStart(2, '0');
    const minutes = d.getMinutes()?.toString().padStart(2, '0');
    return `${year}. ${month}. ${day}. ${hours}:${minutes}`;
};

const stripHtml = (html) => {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};
</script>

<style scoped>
.prose {
    color: inherit;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    color: #111827;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.prose h1:first-child, .prose h2:first-child, .prose h3:first-child {
    margin-top: 0;
}

.prose p {
    margin-bottom: 1.5rem;
    line-height: 1.7;
}

.prose ul, .prose ol {
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

.prose blockquote {
    border-left: 4px solid #3A30D3;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #6B7280;
}

.prose code {
    background-color: #F3F4F6;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    color: #3A30D3;
}

.prose pre {
    background-color: #1F2937;
    color: #F9FAFB;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.prose pre code {
    background-color: transparent;
    padding: 0;
    color: inherit;
}
</style>