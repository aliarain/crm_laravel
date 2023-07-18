function http_Request(options) {
    // return axios dynamic method call
    var h = {headers : {"X-Requested-With": "XMLHttpRequest"}};
    switch (options[0]['value']['method']) {
        case 'GET':
            return axios.get(options[0]['url'], options[0]['value'], h);
            break;
        case 'POST':
            return axios.post(options[0]['url'], options[0]['data'] ?? options[0]['value'], h);
            break;
        case 'PUT':
            return axios.put(options[0]['url'], options[0]['value'], h);
            break;
        case 'DELETE':
            return axios.delete(options[0]['url'], options[0]['value'], h);
            break;
        default:
            return axios.get(options[0]['url'], options[0]['value'], h);
            break;

    }

}
function http_Request_multipart(options) {
    return axios.post(options[0]['url'], options[0]['data'] ?? options[0]['value']);
}

function custom_http_request(options) {
    var h = {headers : {"X-Requested-With": "XMLHttpRequest"}};
    switch (options?.method) {
        case 'GET':
            return axios.get(options?.url, options?.data, h);
        case 'POST':
            return axios.post(options?.url, options?.data, h);
        case 'PUT':
            return axios.put(options?.url, options?.data, h);
        case 'DELETE':
            return axios.delete(options?.url, options?.data, h);
        default:
            return axios.get(options?.url, options?.data, h);

    }

}