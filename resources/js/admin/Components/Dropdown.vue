<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

defineProps({
    align: { type: String, default: 'right' },
    width: { type: String, default: 'min-w-[11rem]' },
    up: { type: Boolean, default: false },
});
const open = ref(false);
const root = ref(null);
const close = () => (open.value = false);
const onDoc = (e) => {
    if (root.value && !root.value.contains(e.target)) close();
};
const onKey = (e) => e.key === 'Escape' && close();
onMounted(() => {
    document.addEventListener('mousedown', onDoc);
    document.addEventListener('keydown', onKey);
});
onBeforeUnmount(() => {
    document.removeEventListener('mousedown', onDoc);
    document.removeEventListener('keydown', onKey);
});
</script>

<template>
    <div ref="root" class="relative inline-block">
        <slot name="trigger" :open="open" :toggle="() => (open = !open)" />
        <div v-if="open" class="menu absolute" :class="[align === 'right' ? 'right-0' : 'left-0', up ? 'bottom-full mb-1.5' : 'mt-1.5', width]" @click="close">
            <slot :close="close" />
        </div>
    </div>
</template>
