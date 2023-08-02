export function getQueryVariable (variable) {
    const query = window.location.search.substring(1);
    const vars = query.split("&");
    for (let i = 0; i < vars.length; i++) {
        const [key, value] = vars[i].split("=");
        if (key === variable) {
            return value;
        }
    }
    return false;
}
