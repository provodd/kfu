export default function addParameter(url, param, value) {
    let val = new RegExp('(\\?|\\&)' + param + '=.*?(?=(&|$))');
    let parts = url.toString().split('#');
    url = parts[0];
    let hash = parts[1];
    let qstring = /\?.+$/;
    let newURL = url;

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