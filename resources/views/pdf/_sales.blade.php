<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales pdf</title>
</head>

<body>
    <div style="text-align:center;">
        <H3 style="text-align:center; margin:0;">Quick Phone Fix N More</H3>
        <p style="text-align:center; margin:0; font-size:14px;">Sales List</p>
        @if ($request->from && $request->to)
            <p style="text-align:center; margin:0; font-size:12px;">From: {{ $request->from }}</p>
            <p style="text-align:center; margin:0; font-size:12px;">To: {{ $request->to }}</p>
        @endif
        @if ($request->service_type)
            <p style="text-align:center; margin:0; font-size:12px; text-transform: capitalize">Service Type:
                {{ $request->service_type }}</p>
        @endif
        @if ($request->serach_by && $request->key)
            <p style="text-align:center; margin:0; font-size:12px; text-transform: capitalize;">
                {{ $request->serach_by }}: {{ $request->key }}</p>
        @endif
        <p style="text-align:center; margin:0; font-size:12px;">Date: {{ date('Y-m-d') }}</p>
    </div>
    <div style="display: flex; justify-content: center; margin-top: 10px; overflow-x: auto;">
        <table
            style="margin: 0 auto; text-align: center; font-size:10px; border-collapse: collapse; border: 1px solid #00; ">
            <thead>
                <tr role="row">
                    <th style="border: 1px solid #00;">#</th>
                    <th style="border: 1px solid #00;">Date</th>
                    <th style="border: 1px solid #00;">Name</th>
                    <th style="border: 1px solid #00;">Email</th>
                    <th style="border: 1px solid #00;">Phone</th>
                    <th style="border: 1px solid #00;">Product Name</th>
                    <th style="border: 1px solid #00;">Price</th>
                    <th style="border: 1px solid #00;">Qty</th>
                    <th style="border: 1px solid #00;">Total</th>
                    <th style="border: 1px solid #00;">Paid</th>
                    <th style="border: 1px solid #00;">Due</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                    <tr role="row" class="odd">
                        <td style="border: 1px solid #00;">{{ $loop->index + 1 }}</td>
                        <td style="border: 1px solid #00;">{{ $service->created_at->format('Y-m-d') }}</td>
                        <td style="border: 1px solid #00;">{{ $service->name }}</td>
                        <td style="border: 1px solid #00;">{{ $service->email }}</td>
                        <td style="border: 1px solid #00;">{{ $service->phone }}</td>
                        <td style="border: 1px solid #00;">{{ $service->product_name }}</td>
                        <td style="border: 1px solid #00;">${{ $service->price }}</td>
                        <td style="border: 1px solid #00;">${{ $service->qty }}</td>
                        <td style="border: 1px solid #00;">${{ $service->bill }}</td>
                        <td style="border: 1px solid #00;">{{ $service->paid_amount }}</td>
                        <td style="border: 1px solid #00;">{{ $service->due_amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
