<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Monthly Invoice</title>
    <style>
        @font-face {
            font-family: 'Figtree';
        }
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>

<body>
<h1>Your Account with {{$orgName}}</h1>

<p>Hello {{ $credentials['name'] }},</p>

<p>You have been registered into our system as an employee.</p><br />
<p>You can use the credentials below to log in to the dashboard at: {{env('APP_URL')}}</p><br />
<hr><br />
<p><b>Username:</b> {{ $credentials['email'] }}</p>
<p><b>Password:</b> {{ $credentials['password'] }}</p>
<br /><hr><br />
<br />

<p>We recommend that you change your password upon logging in.</p>
<br />
<p>If you have any questions or concerns, please feel free to submit a complaint request from your account.</p>

<p><br /><b>Thank you!</b></p>
</body>
</html>
