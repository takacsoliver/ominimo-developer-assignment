<template>
    <button
        :type="type"
        :disabled="disabled || loading"
        :class="buttonClasses"
        v-bind="$attrs"
        @click="$emit('click', $event)"
    >
        <span v-if="loading" class="inline-flex items-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loadingText }}
        </span>
        <span v-else>
            <slot />
        </span>
    </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    loadingText: {
        type: String,
        default: 'Loading...',
    },
    fullWidth: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(value),
    },
    className: {
        type: String,
        default: '',
    },
});

defineEmits(['click']);

const buttonClasses = computed(() => {
    const baseClasses = 'inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed';
    
    const sizeClasses = {
        xs: 'px-2.5 py-1.5 text-xs',
        sm: 'px-3 py-2 text-sm',
        md: 'px-4 py-2 text-sm',
        lg: 'px-4 py-2 text-base',
        xl: 'px-6 py-3 text-base',
    };
    
    const widthClasses = props.fullWidth ? 'w-full' : '';
    
    return `${baseClasses} ${sizeClasses[props.size]} ${widthClasses} ${props.className}`;
});
</script>
