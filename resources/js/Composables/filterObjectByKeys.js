
export function filterKeys(arrayOfObjects, excludedKeys) {
    return arrayOfObjects.map(obj => {
        const filteredObj = {};
        for (const key in obj) {
            if (!excludedKeys.includes(key)) {
                filteredObj[key] = obj[key];
            }
        }
        return filteredObj;
    });
}


/*

## SAMPLE USAGE

const arrayOfObjects = [
  { "id": 2, "employee_id": 2, "currency": "EGP", "salary": 0, "start_date": "2023-06-02",
  "end_date": null, "created_at": "2023-06-02T11:50:10.000000Z", "updated_at": "2023-06-02T11:50:10.000000Z" }, {...}
];

const excludedKeys = ['start_date', 'end_date'];

const filteredArray = filterKeys(arrayOfObjects, excludedKeys);

console.log(filteredArray);

~~~~~~~~~~~~~~~

OUTPUT:
[
{ "id": 2, "employee_id": 2, "currency": "EGP", "salary": 0,
"created_at": "2023-06-02T11:50:10.000000Z", "updated_at": "2023-06-02T11:50:10.000000Z" }
]

*/
