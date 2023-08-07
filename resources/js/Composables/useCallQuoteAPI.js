import {ref} from "vue";

export function CallQuoteAPI (quote){

    const lastApiCallTimestamp = ref(localStorage.getItem('lastApiCallTimestamp') || null);
    const apiUrl = 'https://api.quotable.io/quotes/random?tags=Work|Motivational|Inspirational|Creativity';
    async function fetchDataFromAPI() {
        try {
            const response = await fetch(apiUrl);
            if (!response.ok) {
                throw new Error('API call error.');
            }
            const data = await response.json();
            quote.value = data[0]; // quote variable in front-end

            // Store the quote and set a timestamp for the last API call, to prevent calling the API more than once per day
            lastApiCallTimestamp.value = Date.now();
            localStorage.setItem('lastApiCallTimestamp', lastApiCallTimestamp.value);
            localStorage.setItem('quote', JSON.stringify(data));
        } catch (error) {
            throw new Error('Network response was NOT ok:', error);
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
    } else {
        quote.value = JSON.parse(localStorage.getItem('quote'))?.[0];
    }

}
