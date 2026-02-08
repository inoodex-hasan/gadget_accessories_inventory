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
        <p style="text-align:center; margin:0; font-size:14px;">Service List</p>
        @if($request->from && $request->to)
            <p style="text-align:center; margin:0; font-size:12px;">From: {{$request->from}}</p>
            <p style="text-align:center; margin:0; font-size:12px;">To: {{$request->to}}</p>
        @endif
        @if($request->service_type)
            <p style="text-align:center; margin:0; font-size:12px; text-transform: capitalize">Service Type: {{$request->service_type}}</p>
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
                    <th style="border: 1px solid #000;">Date</th>
                    <th style="border: 1px solid #000;">Name</th>
                    <th style="border: 1px solid #000;">Email</th>
                    <th style="border: 1px solid #000;">Phone</th>
                    <th style="border: 1px solid #000;">Product Name</th>
                    <th style="border: 1px solid #000;">EMEI</th>
                    <th style="border: 1px solid #000;">Price</th>
                    <th style="border: 1px solid #000;">Paid</th>
                    <th style="border: 1px solid #000;">Due</th>
                    <th style="border: 1px solid #000;">Warranty</th>
                    <th style="border: 1px solid #000;">Repaired By</th>
                    <th style="border: 1px solid #000;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                <tr role="row" class="odd">
                    <td style="border: 1px solid #000;">{{$loop->index+1}}</td>
                    <td style="border: 1px solid #000;">{{$service->status == '0' ? $service->created_at->format('Y-m-d') : $service->complated_date}}</td>
                    <td style="border: 1px solid #000;">{{$service->name}}</td>
                    <td style="border: 1px solid #000;">{{$service->email}}</td>
                    <td style="border: 1px solid #000;">{{$service->phone}}</td>
                    <td style="border: 1px solid #000;">{{$service->product_name}}</td>
                    <td style="border: 1px solid #000;">{{$service->product_number}}</td>
                    <td style="border: 1px solid #000;">${{$service->bill}}</td>
                    <td style="border: 1px solid #000;">${{$service->paid_amount}}</td>
                    <td style="border: 1px solid #000;">${{$service->due_amount}}</td>
                    <td style="border: 1px solid #000;">{{$service->warranty_duration}}</td>
                    <td style="border: 1px solid #000;">{{$service->repaired_by}}</td>
                    <td style="border: 1px solid #000;">{{$service->status == '0' ? 'Pending' : 'Completed'}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>