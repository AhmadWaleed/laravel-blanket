<template>
    <div :class="store.aside ? 'flex flex-grow lg:flex-grow-0': 'hidden'" class="aside w-80 px-8 py-6 flex flex-col">
        <h2 class="text-center text-lg">View by Methods</h2>
        <ul class="flex flex-col mt-6 space-y-4">
            <li @click="store.log_filters.method = 'all'" class="filter-item">
                <button class="flex items-center space-x-1 focus:outline-none">
                    <i v-if="store.log_filters.method === 'all'" class='bx bxs-checkbox text-2xl'></i>
                    <i v-else class='bx bx-checkbox text-2xl'></i>
                    <span>All</span>
                </button>
                <span>{{ store.log_counts.all }}</span>
            </li>
            <li @click="store.log_filters.method = 'get'" class="filter-item">
                <button class="flex items-center space-x-1 focus:outline-none">
                    <i v-if="store.log_filters.method === 'get'" class='bx bxs-checkbox text-2xl'></i>
                    <i v-else class='bx bx-checkbox text-2xl'></i>
                    <span>Get</span>
                </button>
                <span>{{ store.log_counts.get }}</span>
            </li>
            <li @click="store.log_filters.method = 'post'" class="filter-item">
                <button class="flex items-center space-x-1 focus:outline-none">
                    <i v-if="store.log_filters.method === 'post'" class='bx bxs-checkbox text-2xl'></i>
                    <i v-else class='bx bx-checkbox text-2xl'></i>
                    <span>Post</span>
                </button>
                <span>{{ store.log_counts.post }}</span>
            </li>
            <li @click="store.log_filters.method = 'put'" class="filter-item">
                <button class="flex items-center space-x-1 focus:outline-none">
                    <i v-if="store.log_filters.method === 'put'" class='bx bxs-checkbox text-2xl'></i>
                    <i v-else class='bx bx-checkbox text-2xl'></i>
                    <span>Put</span>
                </button>
                <span>{{ store.log_counts.put }}</span>
            </li>

            <li @click="store.log_filters.method = 'patch'" class="filter-item">
                <button class="flex items-center space-x-1 focus:outline-none">
                    <i v-if="store.log_filters.method === 'patch'" class='bx bxs-checkbox text-2xl'></i>
                    <i v-else class='bx bx-checkbox text-2xl'></i>
                    <span>Patch</span>
                </button>
                <span>{{ store.log_counts.patch }}</span>
            </li>
            <li @click="store.log_filters.method = 'delete'" class="filter-item">
                <button class="flex items-center space-x-1 focus:outline-none">
                    <i v-if="store.log_filters.method === 'delete'" class='bx bxs-checkbox text-2xl'></i>
                    <i v-else class='bx bx-checkbox text-2xl'></i>
                    <span>Delete</span>
                </button>
                <span>{{ store.log_counts.delete }}</span>
            </li>
        </ul>
    </div>
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

<style>
.filter-item {
    @apply cursor-pointer flex items-center justify-between dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-400;
}
</style>