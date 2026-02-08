<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">

            <ul class="sidebar-vertical ">

                <li>
                    <a href="{{ route('index') }}"><i class="fe fe-grid"></i><span> Dashboard</span></a>
                </li>


                @can('Product Management')
                    <li class="menu-title "><span>Product Management</span></li>
                    <li class="">

                        <a href="{{ route('brands.index') }}"
                            class="{{ Route::currentRouteName() == 'brand.index' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span> Brand List</span></a>
                        <a href="{{ route('products.index') }}"
                            class="{{ Route::currentRouteName() == 'products.index' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span> Product List</span></a>
                    </li>
                @endcan

                @can('Sales Management')
                    <li class="menu-title "><span>Sales Management</span></li>
                    <li class="">
                        <a href="{{ route('sales.create') }}"
                            class="{{ Route::currentRouteName() == 'sales.create' ? 'active' : '' }}"><i
                                class="fe fe-plus-circle"></i> <span> Add Sales</span></a>
                        <a href="{{ route('sales.index') }}"
                            class="{{ Route::currentRouteName() == 'sales.index' ? 'active' : '' }}"><i
                                class="fe fe-list"></i> <span>Sales List</span></a>
                    </li>
                @endcan

                @can('Customer Management')
                    <li class="menu-title "><span>Customer Management</span></li>
                    <li class="">
                        <a href="{{ route('customers.create') }}"
                            class="{{ Route::currentRouteName() == 'customers.create' ? 'active' : '' }}"><i
                                class="fe fe-plus-circle"></i> <span> Add Customer</span></a>
                        <a href="{{ route('customers.index') }}"
                            class="{{ Route::currentRouteName() == 'customers.index' ? 'active' : '' }}"><i
                                class="fe fe-plus-circle"></i> <span>Customers List</span></a>
                    </li>
                @endcan

                @can('Vendor Management')
                    <li class="menu-title "><span>Vendor Management</span></li>
                    <li class="">
                        <a href="{{ route('vendors.create') }}"
                            class="{{ Route::currentRouteName() == 'vendors.create' ? 'active' : '' }}"><i
                                class="fe fe-plus-circle"></i> <span> Add Vendor</span></a>
                        <a href="{{ route('vendors.index') }}"
                            class="{{ Route::currentRouteName() == 'vendors.index' ? 'active' : '' }}"><i
                                class="fe fe-list"></i> <span>Vendor List</span></a>
                    </li>
                @endcan

                @can('Purchase Management')
                    <li class="menu-title "><span>Purchase Management</span></li>
                    <li class="">
                        <a href="{{ route('purchase.index') }}"
                            class="{{ Route::currentRouteName() == 'purchase.index' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span> Purchase List</span></a>
                        {{-- <a href="{{ route('purchase.create') }}"
                            class="{{ Route::currentRouteName() == 'purchase.create' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span>Make Purchase</span></a> --}}
                    </li>
                @endcan

                @can('Company Management')
                    <li class="menu-title "><span>Company Management</span></li>
                    <li class="">
                        <a href="{{ route('company-details.index') }}"
                            class="{{ Route::currentRouteName() == 'company-details.index' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span> Company Details</span></a>
                    </li>
                @endcan

                @can('Payment Management')
                    <li class="menu-title"><span>Payment Management</span></li>
                    <li>
                        <a href="{{ route('due-payments.index') }}"
                            class="{{ Route::currentRouteName() == '' ? 'active' : '' }}"><i class="fe fe-plus-circle"></i>
                            <span>Due Payment</span></a>
                    </li>

                    <li>
                        <a href="{{ route('bills.index') }}"
                            class="{{ Route::currentRouteName() == '' ? 'active' : '' }}"><i class="fe fe-plus-circle"></i>
                            <span>Bill Generate</span></a>
                    </li>
                    <li>
                        <a href="{{ route('challans.index') }}"
                            class="{{ Route::currentRouteName() == '' ? 'active' : '' }}"><i class="fe fe-plus-circle"></i>
                            <span>Challan Generate</span></a>
                    </li>
                    <li>
                        <a href="{{ route('quotations.index') }}"
                            class="{{ Route::currentRouteName() == '' ? 'active' : '' }}"><i class="fe fe-plus-circle"></i>
                            <span>Quotation Generate</span></a>
                    </li>
                @endcan

                @can('Inventory Management')
                    <li class="menu-title "><span>Inventory Management</span></li>
                    <li class="">
                        <a href="{{ route('inventory.index') }}"
                            class="{{ Route::currentRouteName() == 'inventory.index' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span> Inventory List</span></a>
                        <a href="{{ route('inventory-items.index') }}"
                            class="{{ Route::currentRouteName() == 'inventory-items.index' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span> Inventory Items List</span></a>
                    </li>
                @endcan

                @can('Client Management')
                    <li class="menu-title "><span>Client Management</span></li>
                    <li class="">
                        <a href="{{ route('clients.index') }}"
                            class="{{ Route::currentRouteName() == 'clients.index' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span> Clients List</span></a>
                    </li>
                @endcan

                @can('Cost Management')
                    <li class="menu-title "><span>Cost Management</span></li>
                    <li class="">
                        <a href="{{ route('cost-categories.index') }}"
                            class="{{ Route::currentRouteName() == 'cost-categories.index' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span> Cost-Category List</span></a>
                        <a href="{{ route('cost-categories.create') }}"
                            class="{{ Route::currentRouteName() == 'cost-categories.create' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span>Add Cost-Category</span></a>
                    </li>
                @endcan

                {{-- @can('Employee Management')
                    <li class="menu-title "><span>Employee Management</span></li>
                    <li class="">
                        <a href="{{ route('employee.tada.index') }}"
                            class="{{ Route::currentRouteName() == 'employee.tada.index' ? 'active' : '' }}"><i
                                class="fe fe-list"></i> <span>TA/DA List</span></a>
                        <a href="{{ route('employee.tada.create') }}"
                            class="{{ Route::currentRouteName() == 'employee.tada.create' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span>Submit TA/DA</span></a>
                    </li>
                @endcan --}}

                {{-- @can('Project Management')
                    <li class="menu-title "><span>Project Management</span></li>
                    <li class="">
                        <a href="{{ route('projects.index') }}"
                            class="{{ Route::currentRouteName() == 'projects.index' ? 'active' : '' }}"><i
                                class="fe fe-list"></i> <span>Projects List</span></a>
                        <a href="{{ route('project-items.index') }}"
                            class="{{ Route::currentRouteName() == 'project-items.index' ? 'active' : '' }}">
                            <i class="fe fe-list"></i> <span>Add Projects Items</span>
                        </a>
                        <a href="{{ route('project-costs.index') }}"
                            class="{{ Route::currentRouteName() == 'project-costs.index' ? 'active' : '' }}">
                            <i class="fe fe-list"></i> <span>Projects Cost List</span>
                        </a>

                    </li>
                @endcan --}}

                @can('Accounts Management')
                    <li class="menu-title"><span>Accounts Management</span></li>
                    <li>
                        <a href="{{ route('expense-categories.index') }}"
                            class="{{ Route::currentRouteName() == 'expense-categories.index' ? 'active' : '' }}"><i
                                class="fe fe-plus-circle"></i> <span>Expense Category</span></a>
                        <a href="{{ route('dailyExpenses.index') }}"
                            class="{{ Route::currentRouteName() == 'dailyExpenses.index' ? 'active' : '' }}"><i
                                class="fe fe-list"></i> <span>Expense List</span></a>

                        {{-- <a href="{{ route('employees.index') }}"
                            class="{{ Route::currentRouteName() == 'employees.index' ? 'active' : '' }}"><i
                                class="fe fe-list"></i> <span>Employee List</span></a>
                        <a href="{{ route('ta-da.index') }}"
                            class="{{ Route::currentRouteName() == 'ta-da.index' ? 'active' : '' }}"><i
                                class="fe fe-list"></i> <span>TA/DA List</span></a>
                        <a href="{{ route('salary.index') }}"
                            class="{{ Route::currentRouteName() == 'salary.index' ? 'active' : '' }}"><i
                                class="fe fe-list"></i> <span>Salary Manage</span></a>
                        <a href="{{ route('bank-details.index') }}"
                            class="{{ Route::currentRouteName() == '' ? 'active' : '' }}"><i
                                class="fe fe-plus-circle"></i>
                            <span>Bank Details</span></a> --}}

                    </li>
                @endcan

                @can('Report Management')
                    <li class="menu-title "><span>Report Management</span></li>
                    <li class="">
                        <a href="{{ route('purchase.report') }}"
                            class="{{ Route::currentRouteName() == 'purchase.report' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span> Purchase Report</span></a>
                    </li>
                    <li class="">
                        <a href="{{ route('sales.report') }}"
                            class="{{ Route::currentRouteName() == 'sales.report' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span> Sales Report</span></a>
                    </li>
                    <li class="">
                        <a href="{{ route('revenues.index') }}"
                            class="{{ Route::currentRouteName() == 'revenues.index' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span> Revenue Report</span></a>
                        </a>
                    </li>
                @endcan

                @can('Administration')
                    <li class="menu-title"><span>Authorization</span></li>
                    <li>
                        <a href="{{ route('permission.index') }}"
                            class="{{ Route::currentRouteName() == 'permission.index' ? 'active' : '' }}"><i
                                class="fe fe-lock"></i> <span> Permissions</span></a>
                    </li>
                    <li>
                        <a href="{{ route('role.index') }}"
                            class="{{ Route::currentRouteName() == 'role.index' ? 'active' : '' }}"><i
                                class="fe fe-shield"></i> <span> Roles</span></a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}"
                            class="{{ Route::currentRouteName() == 'users.index' ? 'active' : '' }}"><i
                                class="fe fe-user"></i> <span> Users</span></a>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
