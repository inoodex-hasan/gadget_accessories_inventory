@extends('layouts.app')

@section('title', 'Quotations')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Quotation Management</h4>
                            <a href="{{ route('quotations.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Quotation
                            </a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row mb-4">
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Type</label>
                                    <select class="form-control" id="typeFilter">
                                        <option value="">All Types</option>
                                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Sales
                                            Challan</option>
                                        <option value="project" {{ request('type') == 'project' ? 'selected' : '' }}>
                                            Project
                                            Challan</option>
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Date From</label>
                                    <input type="date" class="form-control" id="dateFrom"
                                        value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Date To</label>
                                    <input type="date" class="form-control" id="dateTo"
                                        value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-end h-100">
                                    <button type="button" class="btn btn-primary me-2" id="applyFilters">
                                        Apply
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="resetFilters">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="table-fluid">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100"
                                id="quotations-datatable">
                                <thead>
                                    <tr>
                                        <th>Quotation No.</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Expiry Date</th>
                                        <th>Total Amount</th>
                                        {{-- <th>Status</th> --}}
                                        <th style="width: 125px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotations as $quotation)
                                        <tr>
                                            <td>
                                                <a href="{{ route('quotations.show', $quotation->id) }}"
                                                    class="text-body fw-bold">
                                                    {{ $quotation->quotation_number }}
                                                </a>
                                            </td>
                                            <td>{{ $quotation->client?->name ?? 'No Client' }}</td>
                                            <td>{{ $quotation->quotation_date->format('M d, Y') }}</td>
                                            <td>{{ $quotation->expiry_date->format('M d, Y') }}</td>
                                            <td>{{ number_format($quotation->total_amount, 2) }}</td>
                                            {{-- <td>
                                                    @if ($quotation->status == 'draft')
                                                        <span class="badge bg-warning">Draft</span>
                                                    @elseif($quotation->status == 'sent')
                                                        <span class="badge bg-info">Sent</span>
                                                    @elseif($quotation->status == 'accepted')
                                                        <span class="badge bg-success">Accepted</span>
                                                    @elseif($quotation->status == 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @else
                                                        <span class="badge bg-secondary">Expired</span>
                                                    @endif
                                                </td> --}}
                                            <td class="d-flex align-items-center">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <ul>
                                                            <li>
                                                                {{-- <a class="dropdown-item"
                                                                        href="{{ route('quotations.show', $quotation->id) }}">
                                                                        <i class="far fa-eye me-2"></i>Preview
                                                                    </a> --}}

                                                            </li>

                                                            <a class="dropdown-item"
                                                                href="{{ route('quotations.pdf', $quotation->id) }}">
                                                                <i class="fas fa-download me-2"></i>Download PDF
                                                            </a>

                                                            </li>
                                                            <li>
                                                                <a onclick="if (confirm('Are you sure to delete?')) { document.getElementById('serviceDelete{{ $quotation->id }}').submit(); }"
                                                                    class="dropdown-item" href="javascript:void(0)">
                                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                                                </a>
                                                                <form id="serviceDelete{{ $quotation->id }}"
                                                                    action="{{ route('quotations.destroy', $quotation->id) }}"
                                                                    method="POST" style="display:none;">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#quotations-datatable').DataTable({
                order: [
                    [0, 'desc']
                ],
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    }
                },
                drawCallback: function() {
                    $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                }
            });
        });
    </script>
@endpush
