<template>
    <div ref="root">
        <div ref="trigger">
            <slot name="trigger" v-bind="{toggle}"></slot>
        </div>
        <div ref="content" v-show="visibility">
            <slot />
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch, onUnmounted } from 'vue'
import { createPopper } from '@popperjs/core'

export default defineComponent({
    name: "Popper",

    props: {
        placement: {
            type: String,
            default: 'bottom'
        }
    },

    setup(props) {
        const root = ref(null)
        const trigger = ref(null)
        const content = ref(null)

        let visibility = ref(false)

        const toggle = () => visibility.value = !visibility.value
        const clickOutside = e => (root.value && e.target !== root.value && !root.value.contains(e.target)) && (visibility.value = false)

        onMounted(() => document.addEventListener('click', clickOutside))
        onUnmounted(() => document.removeEventListener('click', clickOutside))

        onMounted(() => watch(
            visibility,
            value => value && createPopper(trigger.value, content.value, {
                placement: props.placement
            }),
            {immediate: true}
        ))

        return {
            root,
            trigger,
            content,
            visibility,
            toggle,
            clickOutside
        }
    }
})
</script>

<style scoped>

</style>