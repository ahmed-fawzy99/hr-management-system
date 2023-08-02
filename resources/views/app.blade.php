<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{app()->getLocale() == 'ar' ? 'rtl' : 'ltr'}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'HRS') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml"
          href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22256%22 height=%22256%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2220%22 fill=%22%237d6ee7%22></rect><path fill=%22%23fff%22 d=%22M44.72 70.06L44.72 70.06Q44.32 70.20 43.66 70.36Q43.00 70.53 42.21 70.53L42.21 70.53Q39.31 70.53 39.31 68.08L39.31 68.08L39.31 52.11L18.72 52.11L18.72 70.06Q18.32 70.20 17.66 70.36Q17.00 70.53 16.21 70.53L16.21 70.53Q13.30 70.53 13.30 68.08L13.30 68.08L13.30 30.20Q13.63 30.07 14.33 29.90Q15.02 29.74 15.81 29.74L15.81 29.74Q18.72 29.74 18.72 32.18L18.72 32.18L18.72 47.62L39.31 47.62L39.31 30.20Q39.64 30.07 40.33 29.90Q41.02 29.74 41.82 29.74L41.82 29.74Q44.72 29.74 44.72 32.18L44.72 32.18L44.72 70.06ZM61.88 55.02L61.88 70.06Q61.48 70.20 60.82 70.36Q60.16 70.53 59.37 70.53L59.37 70.53Q56.47 70.53 56.47 68.08L56.47 68.08L56.47 32.97Q56.47 31.92 57.00 31.35Q57.52 30.79 58.65 30.46L58.65 30.46Q60.56 29.87 63.36 29.61Q66.17 29.34 68.81 29.34L68.81 29.34Q77.13 29.34 81.19 32.67Q85.24 36.01 85.24 42.15L85.24 42.15Q85.24 46.70 82.90 49.80Q80.56 52.90 75.81 54.22L75.81 54.22Q77.46 56.34 79.11 58.45Q80.76 60.56 82.24 62.44Q83.73 64.32 84.88 65.84Q86.04 67.36 86.70 68.22L86.70 68.22Q86.43 69.34 85.54 70.00Q84.65 70.66 83.73 70.66L83.73 70.66Q82.60 70.66 81.88 70.13Q81.15 69.60 80.36 68.48L80.36 68.48L70.72 55.02L61.88 55.02ZM61.81 50.53L69.40 50.53Q74.29 50.53 77.06 48.42Q79.83 46.30 79.83 42.15L79.83 42.15Q79.83 37.99 76.93 35.91Q74.02 33.83 68.61 33.83L68.61 33.83Q66.83 33.83 65.02 33.99Q63.20 34.16 61.81 34.42L61.81 34.42L61.81 50.53Z%22></path></svg>"/>


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;700&display=swap"
          rel="stylesheet">

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead

</head>
<body class="font-sans antialiased !mb-0 ">
@inertia

{{-- Passing Translation to Front-End --}}
<x-translations></x-translations>
</body>
</html>
