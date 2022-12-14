export default function setLocation(curLoc) {
    try {
        history.pushState(null, null, curLoc);
        return;
    } catch (e) {}
    location.hash = '#' + curLoc;
}