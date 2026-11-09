<script setup>
defineProps({
    label: { type: String, default: '' },
    error: { type: String, default: '' },
    hint: { type: String, default: '' },
    required: { type: Boolean, default: false },
    for: { type: String, default: undefined },
});
</script>

<template>
    <div>
        <label v-if="label" :for="$props.for" class="label">
            {{ label }}<span v-if="required" class="ml-0.5 text-rose-500">*</span>
        </label>
        <slot />
        <p v-if="error" class="error">{{ error }}</p>
        <p v-else-if="hint" class="hint">{{ hint }}</p>
    </div>
</template>
