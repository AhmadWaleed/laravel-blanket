<template>
    <popper placement="bottom-start">
        <template #trigger="{toggle}">
            <form @click.prevent>
                <div class="relative focus-within:text-blue-600 dark:focus-within:text-blue-400">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-1">
                        <button type="submit" class="focus:outline-none focus:shadow-outline">
                            <Loading v-if="store.loading_hosts" size="2xl"/>
                            <i v-else class='bx bx-search text-2xl'></i>
                        </button>
                    </span>
                    <input @focus="toggle" v-model="store.host_filter" type="search" name="q" class="text-sm p-1 w-full md:w-96 dark:bg-gray-700 rounded-md pl-10 focus:outline-none" placeholder="Search base URL..." autocomplete="off">
                </div>
            </form>
        </template>
        <div class="bg-white dark:bg-gray-900 w-full md:w-96 mt-1 text-lg font-sm border border-gray-100 dark:border-gray-700 rounded-md shadow-md">
            <ul class="block space-y-2 text-blue-600 dark:text-blue-400 underline">
                <li @click="store.log_filters.host = host" v-for="host in store.hosts" class="hover:bg-blue-50 dark:hover:bg-gray-700 px-4 py-2 cursor-pointer">
                    {{ host }}
                </li>
            </ul>
        </div>
    </popper>
</template>

<script>
import { defineComponent, ref, watch } from 'vue';
import Popper from '@/components/Popper'
import { useStore } from '@/store'

export default defineComponent({
    name: 'HostFilterDropdown',

    components: { Popper },

    setup() {
        const store = useStore();

        watch(() => store.host_filter, () => {
            __.debounce(() => store.fetchHosts(), 500)()
        })

        return { store }
    }
})
</script>