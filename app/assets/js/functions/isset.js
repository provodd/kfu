export default function isset(accessor) {
    try {
        return accessor() !== undefined && accessor() !== null && accessor() !== ''
    } catch (e) {
        return false
    }
}