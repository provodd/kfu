export default function updateURL() {
    if (history.pushState) {
        var baseUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        var newUrl = baseUrl + 'url';
        history.pushState(null, null, newUrl);
    } else {
        console.warn('History API не поддерживает ваш браузер');
    }
}

export default function serializeGet(obj) {
    var str = [];
    for (var p in obj)
        if (obj.hasOwnProperty(p)) {
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        }
    return str.join("&");
}

export default function addGet(url, get) {
    if (typeof (get) === 'object') {
        get = serializeGet(get);
    }
    if (url.match(/\?/)) {
        return url + '&' + get;
    }
    if (!url.match(/\.\w{3,4}$/) && url.substr(-1, 1) !== '/') {
        url += '/';
    }
    return url + '?' + get;
}