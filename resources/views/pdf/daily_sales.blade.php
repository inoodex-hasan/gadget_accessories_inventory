<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Sales pdf</title>
</head>
<body>
    <div style="text-align:center;">
        <H3 style="text-align:center; margin:0;">Quick Phone Fix N More</H3>
        <p style="text-align:center; margin:0; font-size:14px;">Daily Sales List</p>
        @if($request->from && $request->to)
            <p style="text-align:center; margin:0; font-size:12px;">From: {{$request->from}}</p>
            <p style="text-align:center; margin:0; font-size:12px;">To: {{$request->to}}</p>
        @endif
        <p style="text-align:center; margin:0; font-size:12px;">Date: {{date('Y-m-d')}}</p>
    </div>
    <div style="display: flex; justify-content: center; margin-top: 10px; overflow-x: auto;">
        <table style="margin: 0 auto; text-align: center; font-size:10px; border-collapse: collapse; border: 1px solid #00; ">
            <thead>
                <tr role="row">
                    <th style="border: 1px solid #00;">#</th>
                    <th style="border: 1px solid #00;">Date</th>
                    <th style="border: 1px solid #00;">Card Amount</th>
                    <th style="border: 1px solid #00;">Cash Amount</th>
                    <th style="border: 1px solid #00;">Others Amount</th>
                    <th style="border: 1px solid #00;">Total Amount</th>
                    <th style="border: 1px solid #00;">Assign Person</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $cardTotal = 0;
                    $cashTotal = 0;
                    $otherTotal = 0;
                    $total=0;
                @endphp
                @foreach ($sales as $service)
                    @php 
                        $cardTotal += $service->card_amount;
                        $cashTotal += $service->cash_amount;
                        $otherTotal += $service->others_amount;
                        $total += $service->total_amount; 
                    @endphp
                    <tr role="row" class="odd">
                        <td style="border: 1px solid #000;">{{$loop->index+1}}</td>
                        <td style="border: 1px solid #000;">{{$service->date}}</td>
                        <td style="border: 1px solid #000;">{{$service->card_amount}}</td>
                        <td style="border: 1px solid #000;">{{$service->cash_amount}}</td>
                        <td style="border: 1px solid #000;">{{$service->others_amount}}</td>
                        <td style="border: 1px solid #000;">{{$service->total_amount}}</td>
                        <td style="border: 1px solid #000;">{{get_names($users,$service->assigned_person_id)}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="text-align:center; border-right: 1px solid #000;">Total</td>
                    <td colspan="1" style="text-align:center; border-left: 1px solid #000;">{{$cardTotal}}</td>
                    <td colspan="1" style="text-align:center; border-left: 1px solid #000;">{{$cashTotal}}</td>
                    <td colspan="1" style="text-align:center; border-left: 1px solid #000;">{{$otherTotal}}</td>
                    <td colspan="1" style="text-align:center; border-left: 1px solid #000;">{{$total}}</td>
                    <td colspan="1" style="text-align:center; border-left: 1px solid #000;"></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>