<template>
    <div class="bg-gray-100 dark:bg-gray-900 flex h-screen text-gray-500 dark:text-gray-300">
        <Nav/>
        <Aside/>
        <Main/>
        <CreateLogModal/>
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import Nav from '@/components/Nav'
import Main from '@/components/Main'
import Aside from '@/components/Aside'
import CreateLogModal from '@/components/CreateLogModal'

import { useStore } from '@/store'

export default defineComponent({
    components: {
        Nav,
        Aside,
        Main,
        CreateLogModal
    },
    setup() {
        const store = useStore()
        store.setup()
    }
})
</script>