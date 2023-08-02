export function useAgeCalculator(birthday) {

    const birthDate = new Date(birthday);
    const currentDate = new Date();

    // Calculate the difference in years
    let age = currentDate.getFullYear() - birthDate.getFullYear();
    // Adjust the age based on the month and day
    if (
        currentDate.getMonth() < birthDate.getMonth() ||
        (currentDate.getMonth() === birthDate.getMonth() && currentDate.getDate() < birthDate.getDate())
    ) {
        age--;
    }
    // Abs is not needed, but Fake Filler sometimes fills dates in the future. WILL BE REMOVED LATER
    return Math.abs(age);
}
