export default function setPreloader(type) {

    let preloaderEl = document.getElementById('preloader');
    if (type == 'hide') {
        preloaderEl.classList.add('hidden');
        preloaderEl.classList.remove('visible');
    } else {
        preloaderEl.classList.add('visible');
        preloaderEl.classList.remove('hidden');
    }

}