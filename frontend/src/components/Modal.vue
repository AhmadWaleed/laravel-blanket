<template>
    <div @click="$emit('close')" class="fixed inset-0 w-full h-full bg-black bg-opacity-75 dark:bg-opacity-90 z-20 overflow-auto">
        <div @click.stop class="mx-auto w-full max-w-lg bg-white dark:bg-gray-900 my-24 rounded-lg p-6 space-y-10">
            <slot></slot>
        </div>
    </div>
</template>
<script>
import { defineComponent, onUnmounted } from 'vue'

export default defineComponent({
    name: 'Modal',
    emits: ['close'],
    setup() {
        document.body.style.overflow = 'hidden'

        onUnmounted(() => document.body.style.overflow = 'auto')
    }
})
</script>