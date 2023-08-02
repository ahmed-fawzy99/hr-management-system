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
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        thead {
            background-color: #f2f2f2;
        }

    </style>
</head>

<body>
<h1>Payroll Invoice</h1>

<p>Hello {{ $payroll->employee->name }},</p>

@php
    $date = \Carbon\Carbon::createFromFormat('Y-m-d', $payroll->due_date)->subMonthNoOverflow();
    $month = $date->format('F');
    $year = $date->year;
@endphp
<p>Here is the payroll details for {{$month . ' of ' . $year}}:</p>

<h3>Summary</h3>
<table>
    <thead>
    <tr>
        <th>Description</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>Base Salary</td>
            <td>{{ $payroll->currency . ' ' . $payroll->base}}</td>
        </tr>
        <tr>
            <td>Performance Multiplier</td>
            <td>{{$payroll->performance_multiplier * 100 . '%'}}</td>
        </tr>
        <tr>
            <td>Total Additions</td>
            <td>{{ $payroll->currency . ' ' . $payroll->total_additions}}</td>
        </tr>
        <tr>
            <td>Total Deductions</td>
            <td>{{ $payroll->currency . ' ' . $payroll->total_deductions}}</td>
        </tr>
    </tbody>
    <tfoot>
    <tr>
        <td><i>Calculation Method:</i></td>
        <td><i>(Base Salary * Performance Multiplier) + Total Additions - Total Deductions</i></td>
    </tr>
    <tr>
        <td><strong>Total:</strong></td>
        <td><strong>{{ $payroll->currency . ' ' . $payroll->total_payable }}</strong></td>
    </tr>
    </tfoot>
</table>

<br />
<hr>


<p>If you have any questions or concerns, please feel free to submit a payment request from your account.</p>

<p><br /><b>Thank you!</b></p>
</body>
</html>
