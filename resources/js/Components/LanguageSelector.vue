<template>
    <div class="relative group">
        <button 
            @click="toggleDropdown"
            class="flex items-center space-x-2 text-sm text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3A30D3] focus:ring-offset-2 rounded-lg px-3 py-2 transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
            </svg>
            <span class="font-medium">{{ currentLanguage.name }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        
        <div 
            v-if="showDropdown"
            class="absolute right-0 mt-2 w-32 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
        >
            <button
                v-for="language in languages"
                :key="language.code"
                @click="changeLanguage(language.code)"
                class="w-full text-left px-4 py-2 text-sm transition-colors"
                :class="{
                    'bg-[#3A30D3] text-white': language.code === currentLanguage.code,
                    'text-gray-700 hover:bg-gray-50': language.code !== currentLanguage.code
                }"
            >
                {{ language.name }}
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import type { PageProps } from '@/types';

const page = usePage();
const { locale } = useI18n();
const showDropdown = ref(false);

const languages = computed(() => page.props.availableLocales || [
    { code: 'hu', name: 'Magyar' },
    { code: 'en', name: 'English' }
]);

const currentLanguage = computed(() => {
    return languages.value.find(lang => lang.code === locale.value) || languages.value[0];
});

// Sync Vue I18n locale with backend locale
watch(() => page.props.locale, (newLocale) => {
    if (newLocale && newLocale !== locale.value) {
        locale.value = newLocale;
    }
}, { immediate: true });

const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value;
};

const changeLanguage = (languageCode: string) => {
    showDropdown.value = false;
    locale.value = languageCode as 'hu' | 'en';
    
    router.get(`/language/${languageCode}`, {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            console.log('Language changed to:', languageCode);
            //window.location.reload();
        }
    });
};

const handleClickOutside = (event) => {
    if (!event.target.closest('.relative.group')) {
        showDropdown.value = false;
    }
};

if (typeof window !== 'undefined') {
    document.addEventListener('click', handleClickOutside);
}
</script>
