import {ref} from "vue";

export function useIsDark() {
    const mode = ref(document.documentElement.classList.contains('dark'));
    const darkModeObserver = new MutationObserver(() => {
        mode.value = document.documentElement.classList.contains('dark');
    });
    darkModeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
    return mode;
}
