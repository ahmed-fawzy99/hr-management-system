// Proceed with caution with this function. It expects the nested object to be like "sdsd":[{..}]. notice the array.
// Haven't tested it without arrays yet

/*
USAGE

Flattens Object. Any nested object within the resonse object will go one level up. NO NESTING.

~~ INPUT
{ "id": 9, "name": "Kendra Emard", "phone": "(561) 252-8926", "national_id": "27843717003590",
"email": "esther76@example.com", "address": "762 Kulas Mews Suite 412\nNew Donnieland, NY 81951",
"department": "Hester Bartell", "branch": "Adrain Kulas",
"salaries": [ { "id": 9, "employee_id": 9, "currency": "EGP", "salary": 0, "start_date": "2023-06-02",
"end_date": null, "created_at": "2023-06-02T11:50:10.000000Z", "updated_at": "2023-06-02T11:50:10.000000Z" }
 ] }

~~ OUTPUT
{ "id": 9, "name": "Kendra Emard", "phone": "(561) 252-8926", "national_id": "27843717003590",
"email": "esther76@example.com", "address": "762 Kulas Mews Suite 412\nNew Donnieland, NY 81951", "department": "Hester Bartell",
"branch": "Adrain Kulas",  "salaries.id": 9, "salaries.employee_id": 9, "salaries.currency": "EGP", "salaries.salary": 0,
"salaries.start_date": "2023-06-02", "salaries.end_date": null, "salaries.created_at": "2023-06-02T11:50:10.000000Z", "salaries.updated_at":
"2023-06-02T11:50:10.000000Z" } */


export function flattenObject(obj, prefix = '', result = {}) {
    for (let key in obj) {
        if (obj.hasOwnProperty(key)) {
            let newKey = prefix ? prefix + '.' + key : key;

            if (Array.isArray(obj[key]) && obj[key].length === 1 && typeof obj[key][0] === 'object' && obj[key][0] !== null) {
                // Handle the case where the value is an array with a single nested object
                flattenObject(obj[key][0], newKey, result);
            } else if (typeof obj[key] === 'object' && obj[key] !== null) {
                flattenObject(obj[key], newKey, result);
            } else {
                result[newKey] = obj[key];
            }
        }
    }

    return result;
}

