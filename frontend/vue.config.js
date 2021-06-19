module.exports = {
    outputDir: './../public/',
    publicPath: process.env.NODE_ENV === 'production' ? '/vendor/blanket/' : '/',
    filenameHashing: false,
}