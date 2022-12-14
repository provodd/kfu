export default function delParameter(url,parameter){
    var urlParts = url.split('?');

    if (urlParts.length >= 2) {
      // Get first part, and remove from array
      var urlBase = urlParts.shift();
  
      // Join it back up
      var queryString = urlParts.join('?');
  
      var prefix = encodeURIComponent(parameter) + '=';
      var parts = queryString.split(/[&;]/g);
  
      // Reverse iteration as may be destructive
      for (var i = parts.length; i-- > 0; ) {
        // Idiom for string.startsWith
        if (parts[i].lastIndexOf(prefix, 0) !== -1) {
          parts.splice(i, 1);
        }
      }
  
      url = urlBase + '?' + parts.join('&');
    }
  
    return url;
}