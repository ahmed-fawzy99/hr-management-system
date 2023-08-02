/*
 This method is only usable for egyptian national IDs that follow the pattern of 14 digits.
 Replace with your own implementation if you want to use it for other countries.
 */
import {usePage} from "@inertiajs/vue3";

export function useExtractPersonalDetails() {
    function extractPersonalDetails(nationalId) {
        const localeOptions = {year: 'numeric', month: 'long', day: 'numeric'};
        const centuryNumber = parseInt(nationalId.charAt(0));
        const centuryOffset = (centuryNumber - 1) * 100;
        const yearOfBirth = 1800 + centuryOffset + parseInt(nationalId.substr(1, 2));
        const monthOfBirth = parseInt(nationalId.substr(3, 2));
        const dayOfBirth = parseInt(nationalId.substr(5, 2));
        const stateCode = nationalId.substr(7, 2); // State code

        const states = {
            '01': 'القاهرة',
            '02': 'الإسكندرية',
            '03': 'بورسعيد',
            '04': 'السويس',
            '11': 'دمياط',
            '12': 'الدقهلية',
            '13': 'الشرقية',
            '14': 'القليوبية',
            '15': 'كفر الشيخ',
            '16': 'الغربية',
            '17': 'المنوفية',
            '18': 'البحيرة',
            '19': 'الإسماعيلية',
            '21': 'الجيزة',
            '22': 'بني سويف',
            '23': 'الفيوم',
            '24': 'المنيا',
            '25': 'أسيوط',
            '26': 'سوهاج',
            '27': 'قنا',
            '28': 'أسوان',
            '29': 'الأقصر',
            '31': 'البحر الأحمر',
            '32': 'الوادي الجديد',
            '33': 'مطروح',
            '34': 'شمال سيناء',
            '35': 'جنوب سيناء',
            '88': 'خارج الجمهورية'
        };

        const state = states[stateCode]; // State name

        const genderNumber = parseInt(nationalId.charAt(12));
        const isMale = genderNumber % 2 !== 0;

        const dateOfBirth = new Date(yearOfBirth, monthOfBirth -1 , dayOfBirth);

        return {
            year: yearOfBirth,
            month: monthOfBirth,
            day: dayOfBirth,
            date: dateOfBirth.toLocaleDateString('en-uk', localeOptions),
            date_localized: dateOfBirth.toLocaleDateString(usePage().props.locale, localeOptions),
            state: state,
            isMale: isMale,
        };
    }
    return {extractPersonalDetails};
}



