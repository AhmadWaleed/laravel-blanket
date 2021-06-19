import { watch } from 'vue'
import config from '@/config'
import { defineStore } from 'pinia'
import useHttp from '@/functions/useHttp'
import ColorSchemeDetector from '@/ColorSchemeDetector'
import { useLocalStorage, useResizeObserver } from '@vueuse/core'

export const useStore =  defineStore({
    id: 'default',

    state: () => ({
        config: config,
        http: useHttp(config.base_url),
        aside: true,
        theme: useLocalStorage('theme', 'auto'),
        logs: [],
        log_counts: [],
        loading_logs: false,
        retrying_log: null,
        expanded_logs: useLocalStorage('expanded_logs', []),
        logs_end: false,
        take: config.logs_per_page,
        create_log_modal: false,
        log_method: 'get',
        host_filter: '',
        hosts: [],
        loading_hosts: false,
        log_filters: {
            host: '',
            method: 'all'
        },
        is_mobile: false,
    }),

    actions: {
        async setup() {
            this.togglePreferredTheme()
            this.fetchHosts()
            await this.fetchLogs()

            useResizeObserver(document.body, (entries) => {
                const entry = entries[0]
                const { width } = entry.contentRect
                if (width < 1024) {
                    this.aside = false
                    this.is_mobile = true
                } else {
                    this.is_mobile = false
                }
            })
        },

        toggle(key) {
            this[key] = !this[key]
        },

        toggleTheme() {
            const colorSchemeDetector = new ColorSchemeDetector(this.theme)
            this.theme = colorSchemeDetector.suggestPreferredScheme()
        },

        togglePreferredTheme() {
            watch(() => this.theme, scheme => {
                let browserInDarkMode = ColorSchemeDetector.isDark()
                let dark = (scheme === 'auto' && browserInDarkMode) || scheme === 'dark'
                if (dark) {
                    document.documentElement.classList.add('dark')
                    return
                }

                document.documentElement.classList.remove('dark')
            }, {immediate: true})
        },

        async fetchLogs() {
            this.loading_logs = true
            const { take, end, logs, counts } = await this.http.get(`/logs?take=${this.take}&filter_host=${this.log_filters.host}&filter_method=${this.log_filters.method}`)
            this.logs = logs
            this.take = take
            this.logs_end = end
            this.log_counts = counts
            this.loading_logs = false
        },

        fetchHosts() {
            this.loading_hosts = true
             this.http.get(`/hosts/filter?host_filter=${this.host_filter}`)
                 .then(hosts => {
                     this.loading_hosts = false
                     this.hosts = hosts
                 })
        },

        async refreshLogs() {
            this.take = config.logs_per_page
            await this.fetchLogs()
        },

        retryLog(id) {
            this.retrying_log = id
            this.http.post(`/logs/${id}/retry`).then(async (_) => {
                this.retrying_log = false
                await this.refreshLogs()
                this.retrying_log = null
            })
        },

        async deleteLog(id) {
            await this.http.call(`/logs/${id}`, 'delete')
            await this.refreshLogs()
            const index = this.expanded_logs.indexOf(`log_expand_${id}`)
            if (index !== -1) this.expanded_logs.splice(index, 1)
        },

        async clearAll() {
            await this.http.call(`/logs/truncate`, 'delete')
            await this.refreshLogs()
            this.expanded_logs = []
        },

        toggleLog(id) {
            this.toggleExpandedArray(`log_expand_${id}`)
        },

        toggleExpandedArray(key) {
            const index = this.expanded_logs.indexOf(key)
            if (index === -1) {
                this.expanded_logs.push(key)
                return
            }

            this.expanded_logs.splice(index, 1)
        },

        isLogExpanded(id) {
            return this.expanded_logs.includes(`log_expand_${id}`)
        },

        submitLogForm() {
            this.http.post('/logs', { log_method: this.log_method })
                .then(async (_) => {
                    this.log_method = 'get'
                    this.create_log_modal = false
                    await this.refreshLogs()
                })
        },
    }
})