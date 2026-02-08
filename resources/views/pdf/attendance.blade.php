<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance pdf</title>
</head>
<body>
    <div style="text-align:center;">
        <H3 style="text-align:center; margin:0;">Quick Phone Fix N More</H3>
        <p style="text-align:center; margin:0; font-size:14px;">Attendance List</p>
        @if($request->from && $request->to)
            <p style="text-align:center; margin:0; font-size:12px;">From: {{$request->from}}</p>
            <p style="text-align:center; margin:0; font-size:12px;">To: {{$request->to}}</p>
        @endif
        @if($request->user_id)
            <p style="text-align:center; margin:0; font-size:12px; text-transform: capitalize">attendance For: {{$user?->name}}</p>
        @endif
        <p style="text-align:center; margin:0; font-size:12px;">Date: {{date('Y-m-d')}}</p>
    </div>
    <div style="display:flex; justify-content:center; margin-top:10px;">
        <table style="width:100%; margin: 0 auto; text-align: center; font-size:10px; border-collapse: collapse; border: 1px solid #000; ">
            <thead>
                <tr role="row">
                    <th style="border: 1px solid #000;">#</th>
                    <th style="border: 1px solid #000;">Name</th>
                    <th style="border: 1px solid #000;">Date</th>
                    <th style="border: 1px solid #000;">Check In</th>
                    <th style="border: 1px solid #000;">Check Out</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $service)
                <tr role="row" class="odd">
                    <td style="border: 1px solid #000;">{{$loop->index+1}}</td>
                    <td style="border: 1px solid #000;">{{$service->name}}</td>
                    <td style="border: 1px solid #000;">{{$service->date}}</td>
                    <td style="border: 1px solid #000;">{{$service->check_in}}</td>
                    <td style="border: 1px solid #000;">{{$service->check_out}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>