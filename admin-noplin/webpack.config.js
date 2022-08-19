//冗余文件，为了让webstorm正确显示@路径
const path = require('path')
module.exports = {
    context: path.resolve(__dirname, './'),
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            '@/': path.resolve('src'),
        }
    }
}
