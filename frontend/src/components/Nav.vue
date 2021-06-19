<template>
    <nav class="nav bg-white text-3xl md:text-5xl dark:bg-gray-900 flex flex-col items-center px-2 space-y-4 border-r border-gray-300 dark:border-gray-700">
        <img v-if="store.theme === 'dark'" src="./../assets/logo-dark.png" alt="logo" class="w-20">
        <img v-else src="./../assets/logo-light.png" alt="logo" class="w-16 md:w-20">
        <button @click="store.toggle('aside')" :class="store.aside ? 'text-blue-600 dark:text-blue-400' : ''" class="focus:outline-none">
            <i class='bx bxs-cylinder'></i>
        </button>
        <button @click="store.refreshLogs()" :class="store.loading_logs ? 'text-blue-600 dark:text-blue-400' : ''" class="focus:outline-none">
            <i class='bx bx-refresh' :class="{'bx-spin': store.loading_logs}"></i>
        </button>
        <button @click="store.toggle('create_log_modal')" :class="store.create_log_modal ? 'text-blue-600 dark:text-blue-400' : ''" class="focus:outline-none">
            <i class='bx bxs-plus-square'></i>
        </button>
        <div class="flex-grow"></div>
        <button @click="store.toggleTheme" class="focus:outline-none">
            <i v-if="store.theme === 'dark'" class='bx bxs-moon bx-tada'></i>
            <i v-else class='bx bx-sun bx-tada'></i>
        </button>
        <a :href="store.config.github_url" target="_blank">
            <i class='bx bxl-github'></i>
        </a>
    </nav>
</template>

<script>
import { defineComponent } from 'vue'
import { useStore } from '@/store'

export default defineComponent({
    setup() {
        return { store: useStore() }
    }
})
</script>