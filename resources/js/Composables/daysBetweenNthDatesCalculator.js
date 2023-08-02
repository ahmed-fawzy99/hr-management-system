export function daysBetweenNthDates(nthDay) {
    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();

    // Calculate the date of the nthDay in the current month
    const startDate = new Date(currentYear, currentMonth, nthDay);

    // Calculate the date of the nthDay in the next month
    const nextMonth = (currentMonth + 1) % 12;
    const nextMonthYear = currentMonth === 11 ? currentYear + 1 : currentYear;
    const endDate = new Date(nextMonthYear, nextMonth, nthDay);

    // Calculate the difference in milliseconds between the two dates
    const timeDiff = endDate.getTime() - startDate.getTime();

    // Convert milliseconds to days and return the result
    return timeDiff / (1000 * 3600 * 24);
}
