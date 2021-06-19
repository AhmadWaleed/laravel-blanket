<template>
    <modal v-if="store.create_log_modal" @close="store.toggle('create_log_modal')">
        <form @submit.prevent="store.submitLogForm" class="space-y-6">
            <div class="text-3xl text-gray-900 dark:text-gray-300">Log A Request</div>
            <div class="grid grid-cols-3 gap-4">
                <label v-for="method in methods">
                    <input :value="method" v-model="store.log_method" type="radio" name="method">
                    <span class="pl-2 uppercase">{{ method }}</span>
                </label>
            </div>
            <div class="flex items-center justify-center space-x-8 p-4">
                <button @click="store.toggle('create_log_modal')">
                    Cancel
                </button>
                <button class="px-3 py-1 flex items-center space-x-2 text-white dark:text-gray-900 bg-blue-600 dark:bg-blue-400 rounded-md">
                    Submit
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
import { defineComponent } from 'vue'
import Modal from './Modal'
import { useStore } from '@/store'

export default defineComponent({
    name: 'CreateRequestModal',

    components: { Modal },

    setup() {
        const store = useStore()
        const methods = ['get', 'post', 'put', 'patch', 'delete']

        return {
            store,
            methods
        }
    }
})
</script>

<style scoped>

</style>