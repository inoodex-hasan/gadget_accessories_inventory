@extends('frontend.layouts.app')

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">All Project Items</h3>
                </div>
                <div class="col-auto">
                    <a href="{{ route('project-items.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Item
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-fluid">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%"></th>
                                        <th>Project Name</th>
                                        <th>Items Summary</th>
                                        <th>Total Qty</th>
                                        <th>Total Amount</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grouped = $projectItems->groupBy('project_id');
                                    @endphp

                                    @foreach ($grouped as $projectId => $items)
                                        @php
                                            $project = $items->first()->project;
                                            $totalQty = $items->sum('quantity');
                                            $totalAmount = $items->sum('total');
                                            $itemCount = $items->count();
                                            $rowId = 'project-collapse-' . $projectId;
                                        @endphp

                                        <!-- Main Project Row (Collapsible) -->
                                        <tr class=" fw-bold" style="cursor: pointer;" data-bs-toggle="collapse"
                                            data-bs-target="#{{ $rowId }}" aria-expanded="false">
                                            <td>
                                                <i class="fas fa-chevron-right collapse-icon me-2"></i>
                                            </td>
                                            <td colspan="1">
                                                {{ $project->project_name }}
                                                <small class="text-muted d-block">{{ $itemCount }}
                                                    item{{ $itemCount > 1 ? 's' : '' }}</small>
                                            </td>
                                            <td colspan="1"></td>
                                            <td>{{ $totalQty }}</td>
                                            <td>{{ number_format($totalAmount, 2) }}</td>
                                            <td>
                                                <a href="{{ route('projects.show', $project->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                            </td>
                                        </tr>

                                        <!-- Collapsible Child Rows (Items) -->
                                        <tr class="collapse" id="{{ $rowId }}">
                                            <td colspan="6" class="p-0">
                                                <table class="table table-sm mb-0">
                                                    <thead>
                                                        <tr class="table-info">
                                                            <th width="5%"></th>
                                                            <th>Item Name (Model)</th>
                                                            <th>Unit Price</th>
                                                            <th>Qty</th>
                                                            <th>Total</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($items as $item)
                                                            <tr>
                                                                <td></td>
                                                                <td>
                                                                    <strong>{{ $item->product->name }}</strong>
                                                                    @if ($item->product->model)
                                                                        <br><small
                                                                            class="text-muted">{{ $item->product->model }}</small>
                                                                    @endif
                                                                </td>
                                                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                                                <td>{{ $item->quantity }}</td>
                                                                <td>{{ number_format($item->total, 2) }}</td>
                                                                <td class="d-flex align-items-center">
                                                                    <div class="dropdown dropdown-action">
                                                                        <a href="#" class="btn-action-icon"
                                                                            data-bs-toggle="dropdown">
                                                                            <i class="fas fa-ellipsis-v"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-end">
                                                                            <ul>
                                                                                {{-- <li>
                                                                                    <a class="dropdown-item"
                                                                                        href="{{ route('project-items.show', $item->id) }}">
                                                                                        <i class="far fa-eye me-2"></i>View
                                                                                    </a>
                                                                                </li> --}}
                                                                                <li>
                                                                                    <a class="dropdown-item"
                                                                                        href="{{ route('project-items.edit', $item->id) }}">
                                                                                        <i class="far fa-edit me-2"></i>Edit
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a onclick="if (confirm('Are you sure to delete?')) { document.getElementById('serviceDelete{{ $item->id }}').submit(); }"
                                                                                        class="dropdown-item"
                                                                                        href="javascript:void(0)">
                                                                                        <i
                                                                                            class="far fa-trash-alt me-2"></i>Delete
                                                                                    </a>
                                                                                    <form
                                                                                        id="serviceDelete{{ $item->id }}"
                                                                                        action="{{ route('project-items.destroy', $item->id) }}"
                                                                                        method="POST"
                                                                                        style="display:none;">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                    </form>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                {{-- <td>
                                                                    <div class="btn-group btn-group-sm">
                                                                        <a href="{{ route('project-items.edit', $item->id) }}"
                                                                            class="btn btn-outline-secondary"
                                                                            title="Edit">
                                                                            <i class="far fa-edit"></i>
                                                                        </a>
                                                                        <button type="button"
                                                                            onclick="confirmDelete({{ $item->id }})"
                                                                            class="btn btn-outline-danger" title="Delete">
                                                                            <i class="far fa-trash-alt"></i>
                                                                        </button>
                                                                    </div>

                                                                    <!-- Hidden Delete Form -->
                                                                    <form id="deleteForm{{ $item->id }}"
                                                                        action="{{ route('project-items.destroy', $item->id) }}"
                                                                        method="POST" style="display:none;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    </form>
                                                                </td> --}}
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $projectItems->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Rotate chevron when collapsed/expanded
            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(trigger => {
                trigger.addEventListener('click', function() {
                    const icon = this.querySelector('.collapse-icon');
                    const target = document.querySelector(this.getAttribute('data-bs-target'));
                    target.addEventListener('shown.bs.collapse', () => icon.style.transform = 'rotate(90deg)');
                    target.addEventListener('hidden.bs.collapse', () => icon.style.transform = 'rotate(0deg)');
                });
            });

            function confirmDelete(id) {
                if (confirm('Are you sure you want to delete this item?')) {
                    document.getElementById('deleteForm' + id).submit();
                }
            }
        </script>
    </div>
@endsection
