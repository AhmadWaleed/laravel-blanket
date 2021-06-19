import { usePreferredDark } from "@vueuse/core"

export default class ColorSchemeDetector {
    constructor(scheme) {
        this.scheme = scheme
    }

    static isDark() {
        return usePreferredDark().value
    }

    suggestPreferredScheme() {
        const isDark = ColorSchemeDetector.isDark()
        if (this.scheme === 'dark' && isDark) return 'light'
        if (this.scheme === 'light' && isDark)  return 'auto'
        if (this.scheme === 'dark' && !isDark) return 'auto'
        if (this.scheme === 'light' && !isDark) return 'dark'
        if (this.scheme === 'auto' && isDark) return 'light'
        if (this.scheme === 'auto' && !isDark) return 'dark'
    }
}