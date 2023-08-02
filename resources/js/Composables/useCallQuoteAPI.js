import {ref} from "vue";

export function CallQuoteAPI (quote){

    const lastApiCallTimestamp = ref(localStorage.getItem('lastApiCallTimestamp') || null);
    const apiUrl = 'https://api.quotable.io/quotes/random?tags=Work|Motivational|Inspirational|Creativity';
    async function fetchDataFromAPI() {
        try {
            const response = await fetch(apiUrl);
            if (!response.ok) {
                throw new Error('Network response was NOT ok.');
            }
            const data = await response.json();
            localStorage.setItem('quote', JSON.stringify(data));
            quote.value = data[0];
        } catch (error) {
            throw new Error('API call error:', error);
        }
    }

    function shouldFetchData() {
        if (!lastApiCallTimestamp.value) {
            return true; // API has not been called yet
        }
        const currentTime = Date.now();
        const twentyFourHours = 93600000; // 24 hours in milliseconds
        return currentTime - lastApiCallTimestamp.value >= twentyFourHours;
    }

    if (shouldFetchData()) {
        fetchDataFromAPI();
        // Store the current timestamp in localStorage
        lastApiCallTimestamp.value = Date.now();
        localStorage.setItem('lastApiCallTimestamp', lastApiCallTimestamp.value);
    } else {
        quote.value = JSON.parse(localStorage.getItem('quote'))[0];
    }

}
