export function daysUntilNthDayOfMonth(nthDay) {
    const today = new Date();
    const todayDate = today.getDate();
    const year = today.getFullYear();
    const month = today.getMonth();
    let targetMonth = month;
    let targetYear = year;

    if (nthDay > todayDate) {
        // If the nth day is larger than today's date, set the target month to the current month
        targetMonth = month;
    } else {
        // Otherwise, set the target month to the next month
        targetMonth = (month + 1) % 12;
        targetYear = month === 11 ? year + 1 : year;
    }

    const targetDate = new Date(targetYear, targetMonth, nthDay);
    const millisecondsPerDay = 24 * 60 * 60 * 1000;
    const daysLeft = Math.ceil((targetDate - today) / millisecondsPerDay);

    return daysLeft;
}
