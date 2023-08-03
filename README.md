# HR Management System
All-in-one Single-Page-Application to manage employee attendance, leaves, payrolls,
and organize your organization's branches and departments.

# Live Demo
https://hr-system.ahmeddeghady.com
>Note: The data is reset every 24 hours.

# Video Demo (Arabic)
https://youtu.be/JE1ihoGc1OQ

# Screenshots
![Dashboard Light Mode Mode](https://i.imgur.com/bGqyqHi.png)
![Dashboard Dark Mode](https://i.imgur.com/XxImPfa.png)
![Employees](https://i.imgur.com/F2yxXUN.png)
And Much more can be found on the demo.
# Installation
This project is built using Laravel 10, Vue 3, and Inertia.JS. 

### Pre-requisites
- PHP 8.2
- Composer
- PostgreSQL Server (not tested on MySQL)
- Cron (for scheduled tasks)
- npm

```
 git clone https://github.com/ahmed-fawzy99/hr-management-system.git
 cd hr-management-system
 composer install
 cp .env.example .env 
 ```
Then open `.env` file and fill in your database and SMTP credentials (for automatic Emails). 
Also, set `QUEUE_CONNECTION=database` for queues.

After that:
```
 php artisan key:generate
 ```
To generate basic data needed to start the system, we need to run the migrations and starter seeder:
```
 php artisan migrate --seeder=StarterSeeder
```
Alternatively, if you want to populate the application with dummy data so that you can test all functionality, you can run the following command:
```
 php artisan migrate --seeder=DatabaseSeeder
```

After that, Add this entry in your cron file:
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
_If you don't know how to add a cron job, please consult this post: 
[Setting Up a Cron Job](https://phoenixnap.com/kb/set-up-cron-job-linux#setting-up-a-cron-job) for more information._

You also need to run these commands for task scheduling and queues:
```
 php artisan queue:work
```


For Vue Dependencies:
```
 npm install
 npm run build
```
The project should be ready to go now. Your next steps will be to set up a web server such as Apache or Nginx.
Alternatively, you can use instead `php artisan serve` and head to `localhost:8000` to view the project.

If you are going to host the project on a server, further steps are required. Please consult the [Laravel Documentation](https://laravel.com/docs/8.x/deployment) for more information.

# Features
- ✅ Supports different roles; for admins to manage the system, and employees to view their data and make requests.
- ✅ Organizes your organization's branches and departments and tracks their employees.
- ✅ Tracks your employees valuable data, such as current and previous positions, salaries, and personal information.
- ✅ Tracks your employees' attendance and leaves.
- ✅ Supports Self-Taking Attendance for employees.
- ✅ Supports IP-Based Attendance to ensure employees are taking their attendance from inside the organization itself.
- ✅ Automatically calculates employee's work hours based on their shift, and shows their overtime/undertime hours.
- ✅ Automatic payroll generation with the ability to review and overwrite payroll's data.
- ✅ Supports Employee Evaluation for each in every payroll using weighted points, with the ability to use the points as a modifier to the salary.
- ✅ Supports Emailing payroll to Employees upon Admin's approval.
- ✅ Allows employees to make requests for leaves and complaints, with the option for admins to approve/reject them and provide a response, which will be visible in their dashboard and emailed to them.
- ✅ Supports Calendar for meetings, events, holidays, and leaves.
- ✅ Automatically accounts for holidays' and weekends' attendance and does not count them.
- ✅ Provides an attendance dashboard for employees to track their attendance this year.
- ✅ Allows employees to view all their data, including their attendance, requests, and payrolls.

## Other Features
- ✅ Single-Page-Application for better user experience.
- ✅ Supports Light & Dark Mode.
- ✅ Supports English and Arabic, and ready for translating to other languages.
- ✅ Supports multiple currencies for salaries.
- ✅ Log to track all changes in the system.
- ✅ Robust validation system for every request.

# System Behaviour
- The system is designed for employees to take their attendances by themselves, but the admin can overwrite their entries and take the attendance manually.
- If there is no attendance entry for an employee before midnight, the system will automatically mark them as absent.
- The system calculates the employee's work hours based on their shift's start & end time.
- If a shift that has employees assigned to it is deleted, the system will automatically assign them automatically to another shift.
- The system marks employees as late if they have signed in their attendance after their shift's start time by 15 minutes. There are no penalties applied for being late, but it will appear in the payroll's report.
- If an employee gets deleted, the system will automatically delete all their data, including their attendance, leaves, and payroll, but the personal details of the employee will be archived.

# Instructions
- After you log in for the first time, head to the globals page and fill in your organization's data and settings. This data is integral into the system's functionality.
- You might need to have a look at validation rules for national ids. The current rule is generic as countries have very different systems. You can change it inside `app/Services/ValidationServices.php` at `validateEmployeeCreationDetails` and `validateEmployeeUpdateDetails`.
- When you create a new employee, the default password is their `SoftKittyWarmKittyLittleBallOfFurr`.
- To add more languages, run this command in the app root directory: `php artisan langscanner YOUR_LANG` (Courtesy of [druc's laravel-langscanner](https://github.com/druc/laravel-langscanner)). For example, to add Deutsch, run `php artisan langscanner de`. This will generate a JSON file in root/lang/de.json with all the app strings. After you translate the file, add an entry in the language selector for your new language.


# Release Notes
- IP-based System Currently supports IPv4, and the option can be disabled.
- IP-based System assumes your organization has a static IP(s). If you have a dynamic IP(s), you'll need to update the IP(s) in the database every time it changes.
- The system extracts personal information such as gender and age from the national ID, and the current supported ID is for Egyptian IDs. You need to implement your own ID parser to support your country or remove the method altogether. The method is located in `resources/js/Composables/useExtractPersonalDetails.js`.
- normalized_name is a column in the database that is used to improve search experience for Arabic names and other languages that uses Arabic alphabets. it's only used for Arabic names but does not affect other languages, and you can utilize it in your own language. If you are using a different language, and it's bothering you, you can follow these steps to remove it:
- 1. in `app/Services/EmployeeServices.php`, replace line `->where('normalized_name', 'ILIKE', '%' . $term . '%')` with `->where('name', 'ILIKE', '%' . $term . '%')` in `renderIndexPage` and `renderFindPage`. repeat the same for Departments and Branches `renderShowPage` methods.
- 2. in `app/Models/Employee`, remove the entire `public static function boot()` since it's only used for normalization.
- 3. in `employees` migration table, remove the `normalized_name` column, then run the migrations again. NOTE: this will delete all employees' data, it's better to do this before you start adding employees.

# TODO
- [ ] Better Mobile Responsiveness.
- [ ] Support for IPv6 Networks.
- [ ] Option to Print System Data.
- [ ] Transitions for better UX.
- [ ] Better Calendar.

# License
This is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
You are free to use, modify, distribute, and sell it under the license terms.

I love listening to feedback and pull requests are always welcome ❤️

If you like this project, please support it by giving it a star ⭐,
you can also [Buy Me a Coffee ☕](https://www.buymeacoffee.com/ahmeddeghady) :)


# Contact
If your organization needs help with implementing this system or any other system, or needs a custom system built for you, 
please contact me at [ahmaddeghady99@gmail.com](mailto:ahmaddeghady99@gmail.com)
