<template>
    <div @scroll="scrollCallback">
        <slot></slot>
    </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    emits: ['scroll-to-end'],

    setup(_, { emit }) {
        const trigger = __.throttle(() => {
            emit('scroll-to-end')
        }, 500, {leading: true, trailing: false})

        let scrollCallback = e => (e.target.offsetHeight + e.target.scrollTop >= e.target.scrollHeight) && trigger()

        return {scrollCallback}
    }
})
</script>
