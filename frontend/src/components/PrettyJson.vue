<script>
import { defineComponent, ref, onMounted, h } from 'vue'

export default defineComponent({
        name: 'PrettyJson',

        props: {
            content: {
                type: String,
            }
        },

        setup(props) {
            let root = ref(null)

            // https://ourcodeworld.com/articles/read/112/how-to-pretty-print-beautify-a-json-string
            const beautifyJson = (json) => {
                json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                return json.replace(
                    /("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
                    (match) => {
                        let cls = 'number';
                        if (/^"/.test(match)) {
                            if (/:$/.test(match)) {
                                cls = 'key';
                            } else {
                                cls = 'string';
                            }
                        } else if (/true|false/.test(match)) {
                            cls = 'boolean';
                        } else if (/null/.test(match)) {
                            cls = 'null';
                        }

                        return '<span class="' + cls + '">' + match + '</span>';
                    }
                );
            }

            onMounted(() => {
                const json = JSON.stringify(JSON.parse(props.content), null, 3)
                root.value.innerHTML = beautifyJson(json)
            })

            return () =>
                h(
                    'pre',
                    {
                        ref: root,
                        class: 'p-4 text-white bg-gray-700 whitespace-pre-wrap break-all'
                    }
                )
        }
    }
)

</script>

<style>
.key { @apply text-white; }
.null { @apply text-pink-600; }
.string { @apply text-blue-400; }
.boolean { @apply text-blue-700; }
.number { @apply text-yellow-400; }
</style>