export function addParameter(url, param, value) {
    var val = new RegExp('(\\?|\\&)' + param + '=.*?(?=(&|$))'),
        parts = url.toString().split('#'),
        url = parts[0],
        hash = parts[1]
    qstring = /\?.+$/,
        newURL = url;

    if (val.test(url)) {
        newURL = url.replace(val, '$1' + param + '=' + value);
    } else if (qstring.test(url)) {
        newURL = url + '&' + param + '=' + value;
    } else {
        // if there's no query string, add one
        newURL = url + '?' + param + '=' + value;
    }
    if (hash) {
        newURL += '#' + hash;
    }
    return newURL;
}