<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-md border border-[#3A30D3] bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-[#3A30D3] shadow-sm transition duration-150 ease-in-out hover:bg-[#3A30D3] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#3A30D3] focus:ring-offset-2 disabled:opacity-25"
    >
        <slot />
    </button>
</template>
