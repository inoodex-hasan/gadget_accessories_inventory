<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services pdf</title>
</head>
<body>
    <div style="text-align:center;">
        <H3 style="text-align:center; margin:0;">Quick Phone Fix N More</H3>
        <p style="text-align:center; margin:0; font-size:14px;">Employee List</p>
        @if($request->from && $request->to)
            <p style="text-align:center; margin:0; font-size:12px;"> From: {{$request->from}}</p>
            <p style="text-align:center; margin:0; font-size:12px;"> To: {{$request->to}}</p>
        @endif
        @if($request->serach_by && $request->key)
            <p style="text-align:center; margin:0; font-size:12px; text-transform: capitalize;">{{$request->serach_by}}: {{$request->key}}</p>
        @endif
        <p style="text-align:center; margin:0; font-size:12px;">Date: {{date('Y-m-d')}}</p>
    </div>
    <div style="display:flex; justify-content:center; margin-top:10px;">
        <table style="margin: 0 auto; text-align: center; font-size:10px; border-collapse: collapse; border: 1px solid #000; ">
            <thead>
                <tr role="row">
                    <th style="border: 1px solid #000;">#</th>
                    <th style="border: 1px solid #000;">Name</th>
                    <th style="border: 1px solid #000;">Email</th>
                    <th style="border: 1px solid #000;">Phone</th>
                    <th style="border: 1px solid #000;">Department</th>
                    <th style="border: 1px solid #000;">Designation</th>
                    <th style="border: 1px solid #000;">Joining Date</th>
                    <th style="border: 1px solid #000;">Earning Amount</th>
                    <th style="border: 1px solid #000;">received Amount</th>
                    <th style="border: 1px solid #000;">Salary</th>
                    <th style="border: 1px solid #000;">Days</th>
                    <th style="border: 1px solid #000;">Balance</th>
                    <th style="border: 1px solid #000;">Paid</th>
                    <th style="border: 1px solid #000;">Status</th>
                </tr>
            </thead>
            <tbody>
            @php $totalsalary = 0; $totalDays = 0; $total = 0; $totalPaid = 0; $totalEarn = 0; $totalReceived = 0; @endphp
                @foreach ($users as $item)
                    @php 
                        $totalsalary += $item->salary;
                        $total += $item->balance;
                        $totalDays += $item->days;
                        $totalPaid += $item->paid;
                        $totalEarn += (isset($salarySummary[$item->id]) ? $salarySummary[$item->id] : 0);
                        $totalReceived += (isset($SalaryPaymentSummary[$item->id]) ? $SalaryPaymentSummary[$item->id] : 0);
                    @endphp
                <tr role="row" class="odd">
                    <td style="border: 1px solid #000;">{{$loop->index+1}}</td>
                    <td style="border: 1px solid #000;">{{ $item->name }}</td>
                    <td style="border: 1px solid #000;">{{ $item->email }}</td>
                    <td style="border: 1px solid #000;">{{ $item->phone }}</td>
                    <td style="border: 1px solid #000;">{{ $item->department ?? 'N/A' }}</td>
                    <td style="border: 1px solid #000;">{{ $item->designation ?? 'N/A' }}</td>
                    <td style="border: 1px solid #000;">{{ $item->joining_date ? \Carbon\Carbon::parse($item->joining_date)->format('d M Y') : 'N/A' }}</td>
                    <td style="border: 1px solid #000;">${{ number_format((isset($salarySummary[$item->id]) ? $salarySummary[$item->id] : 0), 2) }}</td>
                    <td style="border: 1px solid #000;">${{ number_format((isset($SalaryPaymentSummary[$item->id]) ? $SalaryPaymentSummary[$item->id] : 0), 2) }}</td>
                    <td style="border: 1px solid #000;">${{ number_format($item->salary, 2) }}</td>
                    <td style="border: 1px solid #000;">{{ number_format($item->days) }}</td>
                    <td style="border: 1px solid #000;">${{ number_format($item->balance) }}</td>
                    <td style="border: 1px solid #000;">${{ number_format($item->paid) }}</td>
                    <td style="border: 1px solid #000;">{{ $item->status == '1' ? 'Active' : 'Inactive' }}</td>
                </tr>
                @endforeach
                <tr role="row" class="odd">
                    <td colspan="7" style="border: 1px solid #000; text-align: right;">Total</td>
                    <td style="border: 1px solid #000;">${{$totalEarn}}</td>
                    <td style="border: 1px solid #000;">${{$totalReceived}}</td>
                    <td style="border: 1px solid #000;">${{$totalsalary}}</td>
                    <td style="border: 1px solid #000;">{{$totalDays}}</td>
                    <td style="border: 1px solid #000;">${{$total}}</td>
                    <td style="border: 1px solid #000;">${{$totalPaid}}</td>
                    <td style="border: 1px solid #000;"></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>