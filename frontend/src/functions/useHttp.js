
import axios from 'axios'

export default function useHttp(baseURL) {
    const defaultHeaders = {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }

    const call = (url, method, options = {}) => {
        const data = options.data || {}
        const config = options.config || {}
        const headers = options.headers || {}

        const promise = axios({
            url: url,
            method: method,
            baseURL: baseURL,
            data: data,
            ...config,
            headers: { ...defaultHeaders, ...headers },
        })

        if (options['wantsRawResponse']) {
            return promise;
        }

        return (
            promise
                .then(response => {
                    return response.data
                })
                .catch(error => {
                    console.log(`Fetch error: ${error}`)
                    return error.response ? error.response : error.message
                })
        )
    }

    const get = (url, options = {}) => call(url, 'get', options)
    const post = (url, data, options = {}) => call(url, 'post', {...{data: data}, ...options})

    return { call, get, post }
}