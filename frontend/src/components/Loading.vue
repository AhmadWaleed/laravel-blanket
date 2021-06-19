<template>
    <i class="bx bx-loader-alt bx-spin" :class="textSize"></i>
</template>

<script>
import { defineComponent, computed } from 'vue'

export default defineComponent({
    props: {
        size: {
            type: String,
            default: '4xl'
        }
    },

    setup(props) {
        const textSize = computed(() => `text-${props.size}`)

        return { textSize }
    }
})
</script>
