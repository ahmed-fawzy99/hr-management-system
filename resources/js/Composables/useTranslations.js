export function __(key, replacements = {}) {
    let translation = window._translations[key] || key;
    Object.keys(replacements).forEach((replacement) => {
        translation = translation.replace(`:${replacement}`, replacements[replacement]);
    });
    return translation;
}
