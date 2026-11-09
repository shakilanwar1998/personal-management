<script setup>
import { AlertTriangle } from 'lucide-vue-next';
import Modal from './Modal.vue';
import { confirmState, settleConfirm } from '../composables/useConfirm';
</script>

<template>
    <Modal :show="confirmState.open" size="sm" @close="settleConfirm(false)">
        <div class="flex gap-4 pt-2">
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                :class="confirmState.tone === 'danger' ? 'bg-rose-100 text-rose-600 dark:bg-rose-500/15 dark:text-rose-400' : 'bg-brand-100 text-brand-600 dark:bg-brand-500/15 dark:text-brand-300'"
            >
                <AlertTriangle class="h-5 w-5" />
            </div>
            <div>
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white">{{ confirmState.title }}</h3>
                <p v-if="confirmState.message" class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">{{ confirmState.message }}</p>
            </div>
        </div>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="settleConfirm(false)">Cancel</button>
            <button type="button" class="btn" :class="confirmState.tone === 'danger' ? 'btn-danger' : 'btn-primary'" @click="settleConfirm(true)">
                {{ confirmState.confirmText }}
            </button>
        </template>
    </Modal>
</template>
