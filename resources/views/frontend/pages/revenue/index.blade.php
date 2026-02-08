@extends('frontend.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3 pt-3">
            <h4>Monthly Revenue Summary</h4>
            <form method="POST" action="{{ route('revenues.generate') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-sync"></i> Generate This Month
                </button>
            </form>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Purchases</th>
                    <th>Sales</th>
                    <th>Expenses</th>
                    <th>Net Profit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($revenues as $rev)
                    <tr>
                        <td>{{ $rev->year }}</td>
                        <td>{{ $rev->month_name }}</td>
                        <td>{{ number_format($rev->total_purchases, 2) }}</td>
                        <td>{{ number_format($rev->total_sales, 2) }}</td>
                        <td>{{ number_format($rev->total_expenses, 2) }}</td>
                        <td><strong>{{ number_format($rev->net_profit, 2) }}</strong></td>
                        <td>
                            <a href="{{ route('revenues.export', $rev->id) }}" class="btn btn-sm">
                                <i class="fa fa-file-pdf"></i> PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No revenue data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
