<template>
    <main :class="store.is_mobile && store.aside ? 'hidden' : ''" class="main flex-grow px-4 py-6 h-full">
        <infinite-scroll @scroll-to-end="!store.logs_end && store.fetchLogs()" style="height:calc(100%)" class="overflow-auto overflow-x-hidden relative">
            <div class="sticky top-0 px-2 pb-6 bg-gray-100 dark:bg-gray-900 block md:flex items-center justify-between">
                <host-filter-dropdown></host-filter-dropdown>
                <button @click="clearAll" class="mt-2 w-full bg-white md:w-40 dark:bg-gray-900 px-4 py-1 rounded-md dark:border dark:border-gray-600">
                    Clear All logs
                </button>
            </div>
            <div v-if="store.logs.length" class="mt-6 space-y-3">
                <Log v-for="log in store.logs" :key="log.id" :log="log"/>
            </div>
            <div class="w-full h-[30vh] flex items-center justify-center">
                <Loading v-if="store.loading_logs"/>
                <div v-else-if="!store.logs.length" class="text-center">
                    <div class="uppercase text-xl font-bold text-gray-600">No logs found :(</div>
                    <div class="text-gray-400 mt-2">Try to clear filters or send few request...</div>
                </div>
                <div v-else-if="store.logs_end" class="uppercase text-xl font-light">End Of File</div>
            </div>
        </infinite-scroll>
    </main>
</template>

<script>
import { defineComponent, watch } from 'vue'
import { useStore } from '@/store'
import Log from './Log'
import InfiniteScroll from './InfiniteScroll'
import HostFilterDropdown from '@/components/HostFilterDropdown'

export default defineComponent({
    components: {
        Log,
        InfiniteScroll,
        HostFilterDropdown
    },

    setup() {
        const store = useStore()
        const clearAll = () => {
            const ok = confirm("Are you sure? you want to clear all the logs.");
            if (ok) store.clearAll()
        }

        watch(() => store.log_filters, ({host}) => {
            if (host) store.host_filter = host
            __.debounce(() => store.fetchLogs(), 500)()
        }, {deep: true})

        return {store, clearAll}
    }
})
</script>
