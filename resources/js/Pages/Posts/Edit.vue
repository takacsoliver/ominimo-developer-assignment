<template>
    <Head :title="t('posts.edit_post')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('posts.edit_post') }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ t('forms.title') }}
                                </label>
                                <input
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    required
                                />
                                <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.title }}
                                </div>
                            </div>

                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ t('forms.content') }}
                                </label>
                                <textarea
                                    id="content"
                                    v-model="form.content"
                                    rows="8"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 min-h-[200px] resize-vertical"
                                    required
                                ></textarea>
                                <div v-if="form.errors.content" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.content }}
                                </div>
                            </div>


                            <div class="flex justify-end space-x-4">
                                <Link
                                    :href="route('posts.show', post.id)"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    {{ t('forms.cancel') }}
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                                >
                                    {{ form.processing ? t('forms.updating') + '...' : t('posts.update_post') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Head, Link, useForm, usePage } from "@inertiajs/vue3"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
import { useI18n } from 'vue-i18n';
import type { PostEditPageProps, Post, UpdatePostFormData } from '@/types';

const page = usePage();
const { t } = useI18n();

const props = defineProps<{
    post: Post;
}>();

const form = useForm<UpdatePostFormData>({
    title: props.post.title,
    content: props.post.content
});

const submit = () => {
    form.put(route("posts.update", props.post.id))
}
</script>
