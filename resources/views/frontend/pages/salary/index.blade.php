@extends('frontend.layouts.app')
@section('content')
    <div class="content container-fluid col-sm-10">
        <div class="content-page-header d-flex justify-content-between">
            <h5>Salary Manage</h5>
            <a href="{{ route('salary.create') }}" class="btn btn-primary">+ Add Salary</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Month</th>
                    <th>Basic</th>
                    <th>Advance</th>
                    <th>Allowance</th>
                    <th>Deduction</th>
                    <th>Net Salary</th>
                    <th>Status</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salaries as $key => $salary)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $salary->employee->name }}</td>
                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $salary->month)->format('M Y') }}</td>
                        <td>{{ $salary->basic_salary }}</td>
                        <td>{{ $salary->advance ?? 00 }}</td>
                        <td>{{ $salary->allowance ?? 00 }}</td>
                        <td>{{ $salary->deduction ?? 00 }}</td>
                        <td>{{ $salary->net_salary }}</td>
                        <td>
                            <span class="badge bg-{{ $salary->payment_status == 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($salary->payment_status) }}
                            </span>
                        </td>
                        <td class="d-flex align-items-center">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('salary.edit', $salary->id) }}">
                                                <i class="far fa-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a onclick="if (confirm('Are you sure to delete the salary?')) { document.getElementById('serviceDelete{{ $salary->id }}').submit(); }"
                                                class="dropdown-item" href="javascript:void(0)">
                                                <i class="far fa-trash-alt me-2"></i>Delete
                                            </a>
                                            <form id="serviceDelete{{ $salary->id }}"
                                                action="{{ route('salary.destroy', $salary->id) }}" method="POST"
                                                style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
