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
<h1>Request Status Update</h1>

<p>Hello {{ $request->employee->name }},</p>

<p>This mail is regarding your request #{{$request->id}} submitted on {{\Carbon\Carbon::createFromTimeString($request->created_at)->toDateString()}}</p><br />
<hr><br />
<h3>Your Request status has been updated to <b style="color: {{ $request->status == 'Rejected' ? 'red' : 'green' }}">{{ $request->status }}</b>.</h3>
@if($request->status == 'Rejected' && $request->admin_response)
    <p>Rejection Reason: {{$request->admin_response}}.</p>
@endif

<br />
<hr>
<br />

<p>If you have any questions or concerns, please feel free to submit a complaint request from your account.</p>

<p><br /><b>Thank you!</b></p>
</body>
</html>
