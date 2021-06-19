<template>
    <div class="bg-white dark:bg-gray-900 dark:border dark:border-gray-700 shadow-sm">
        <div class="p-4 flex flex-col md:flex-row items-center rounded-md space-y-4 md:space-x-4">
            <span :class="[methodClass, 'text-white', 'dark:text-gray-900', 'rounded-lg', 'font-semibold', 'w-24', 'py-1', 'text-center']">
              {{ log.method }}
            </span>
            <div class="flex-grow">
                <div class="flex items-center space-x-2">
                    <a :href="log.url" class="font-bold text-sm underline text-blue-600 dark:text-blue-400">http://...</a>
                    <h2>{{ log.path }}</h2>
                </div>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="font-bold" :class="statusClass">Status: {{ log.status }}</span>
                    <span>{{ store.config.app_env }}</span>
                    <span>{{ log.created_at }}</span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button class="focus:outline-none bg-gray-200 dark:bg-gray-700 rounded-md px-2 py-2" @click="store.retryLog(log.id)">
                    <i :class="{'bx-spin': store.retrying_log === log.id}" class='bx bx-revision text-2xl hover:text-blue-600 dark:hover:text-blue-400'></i>
                </button>
                <button @click="store.deleteLog(log.id)" class="focus:outline-none bg-gray-200 dark:bg-gray-700 rounded-md px-2 py-2">
                    <i class='bx bxs-trash-alt text-2xl hover:text-red-600 dark:hover:text-red-600'></i>
                </button>
                <button
                    :class="store.isLogExpanded(log.id) ? 'bg-blue-600 dark:bg-blue-400' : 'bg-gray-200 dark:bg-gray-700'"
                    class="focus:outline-none rounded-md px-2 py-2" @click="store.toggleLog(log.id)">
                    <i :class="store.isLogExpanded(log.id) ? 'text-white hover:text-white' : ''" class='bx bx-expand-alt text-2xl hover:text-blue-600 dark:hover:text-blue-400'></i>
                </button>
            </div>
        </div>
        <div v-if="store.isLogExpanded(log.id)">
            <Divider/>
            <div class="flex items-center">
                <button @click="activeTab = 'request'" :class="activeTab === 'request' ? 'text-blue-600 dark:text-blue-400 border-b-4 border-rounded-b-lg border-blue-600 dark:border-blue-400' : ''" class="font-semibold focus:outline-none p-4">
                    Request
                </button>
                <button @click="activeTab = 'response'" :class="activeTab === 'response' ? 'text-blue-600 dark:text-blue-400 border-b-4 border-rounded-b-lg border-blue-600 dark:border-blue-400' : ''" class="font-semibold focus:outline-none p-4">
                    Response
                </button>
            </div>
            <div class="rounded-md rounded-t-none overflow-auto h-96">
                <pretty-json v-if="activeTab === 'request'" :content="JSON.stringify(log.request)"></pretty-json>
                <pretty-json v-else :content="JSON.stringify(log.response)"></pretty-json>
            </div>
            <Divider/>
            <div
                class="flex flex-col flex-col-reverse md:flex-row items-center justify-between md:justify-end space-x-8 p-4">
                <button @click="store.toggleLog(log.id)" class="px-4 focus:outline-none">Close</button>
                <button @click="copyToClipboard" class="focus:outline-none px-3 py-1 mb-4 md:mb-0 flex items-center space-x-2 text-white dark:text-gray-900 bg-blue-600 dark:bg-blue-400 rounded-md">
                    <i v-if="copied" class='bx bx-check text-xl'></i>
                    <i v-else class='bx bx-clipboard text-xl'></i>
                    <span>Copy to clipboard</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { computed, defineComponent, ref, watch } from 'vue'
import copy from 'copy-to-clipboard'
import { useStore } from '@/store'
import { between } from '@/utils'
import PrettyJson from '@/components/PrettyJson'

export default defineComponent({
    props: {
        log: {type: Object, required: true},
    },

    components: {PrettyJson},

    setup(props) {
        const methodClass = computed(() => {
            const method = props.log.method.toLowerCase()
            if (method === 'get') return 'bg-green-400'
            if (method === 'post') return 'bg-yellow-200'
            if (method === 'put') return 'bg-indigo-500'
            if (method === 'patch') return 'bg-yellow-600'
            if (method === 'delete') return 'bg-red-400'
            return ''
        })
        const statusClass = computed(() => {
            const code = parseInt(props.log.status)
            if (between(code, 100, 299)) return 'text-blue-600 dark:text-blue-400'
            if (between(code, 300, 399)) return 'text-indigo-500'
            return 'text-red-500'
        })

        let copied = ref(false)
        let activeTab = ref('request')

        const copyToClipboard = () => {
            const content = activeTab.value === 'request'
                ? JSON.stringify(props.log.request)
                : JSON.stringify(props.log.response)

            copy(content)
            copied.value = true
        }

        watch(copied, (value) => {
            if (value) setTimeout(() => copied.value = false, 3000)
        })

        return {
            copied,
            activeTab,
            statusClass,
            methodClass,
            copyToClipboard,
            store: useStore()
        }
    }
})
</script>