<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            {{-- <nav class="greedys sidebar-horizantal">
				<ul class="list-inline-item list-unstyled links">
					@can('Administration')
						<li class="menu-title"><span>Authorization</span></li>
						<li class="submenu">
							<a href="{{ route('permission.index') }}"><i class="fe fe-home"></i> <span> Permissions</span> <span class="menu-arrow"></span></a>
						</li>
						<li class="submenu">
							<a href="{{ route('role.index') }}"><i class="fe fe-home"></i> <span> Roles</span> <span class="menu-arrow"></span></a>
						</li>
						<li class="submenu">
							<a href="{{ route('users.index') }}"><i class="fe fe-home"></i> <span> Users</span> <span class="menu-arrow"></span></a>
						</li>
						<li class="submenu">
							<a href="{{ route('users.pin') }}"><i class="fe fe-key"></i> <span> PIN Number</span> <span class="menu-arrow"></span></a>
						</li>
					@endcan
				</ul>
				<!-- /Settings -->
			</nav> --}}
            <ul class="sidebar-vertical ">
                <!-- Main -->

                <li>
                    <a href="{{ route('index') }}"><i class="fe fe-grid"></i><span> Dashboard</span></a>
                </li>
                {{-- 			
				@can('Booking')
					<li class="menu-title"><span>Booking</span></li>
					<li>
						@php
							$pendingBooking = pendingBooking();
						@endphp
						<a href="{{ route('booking.index') }}" class="{{ Route::currentRouteName() == 'booking.index' ? 'active' : '' }}">
							<i class="fe fe-list"></i> 
							<span> Booking List</span> 
							@if ($pendingBooking)
								<span style="color:white; background:red; border-radius:50%; padding:3px 7px; display:inline-block; text-align:center;">
									{{$pendingBooking}}
								</span>
							@endif
						</a>									
					</li>
				@endcan

				@can('Service Management')
					<li class="menu-title"><span>Services</span></li>
					<li>
						<a href="{{ route('service.create') }}" class="{{ Route::currentRouteName() == 'service.create' ? 'active' : '' }}" ><i class="fe fe-plus-circle"></i> <span> Add Service</span></a>
						<a href="{{ route('service.index') }}" class="{{ Route::currentRouteName() == 'service.index' ? 'active' : '' }}"><i class="fe fe-refresh-cw"></i> <span>Pending Service</span></a>
						<a href="{{ route('service.complated') }}" class="{{ Route::currentRouteName() == 'service.complated' ? 'active' : '' }}"><i class="fe fe-check-square"></i> <span>Completed Service</span></a>
						<a href="{{ route('products.index',['type' => 'service']) }}" class="{{ Route::currentRouteName() == 'products.index' ? 'active' : '' }}"><i class="fe fe-server"></i> <span>Service List</span></a>									
					</li>
				@endcan --}}

                @can('Product Management')
                    <li class="menu-title "><span>Product Management</span></li>
                    <li class="">
                        {{-- <a href="{{ route('sales.create') }}" class="{{ Route::currentRouteName() == 'sales.create' ? 'active' : '' }}"><i class="fe fe-plus-circle"></i> <span> Add Sales</span></a>
						<a href="{{ route('sales.index') }}" class="{{ Route::currentRouteName() == 'sales.index' ? 'active' : '' }}"><i class="fe fe-list"></i> <span>Sales List</span></a> --}}
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
                                class="fe fe-list"></i> <span>Customer List</span></a>
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
                    </li>
                @endcan



                @can('Inventory Management')
                    <li class="menu-title "><span>Inventory Management</span></li>
                    <li class="">
                        <a href="{{ route('inventory.index') }}"
                            class="{{ Route::currentRouteName() == 'inventory.index' ? 'active' : '' }}"><i
                                class="fe fe-package"></i> <span> Inventory List</span></a>
                    </li>
                @endcan
                @can('Expense Management')
                    <li class="menu-title"><span>Expense Management</span></li>
                    <li>
                        <a href="{{ route('expense-categories.index') }}"
                            class="{{ Route::currentRouteName() == 'expense-categories.index' ? 'active' : '' }}"><i
                                class="fe fe-plus-circle"></i> <span>Expense Category</span></a>
                        <a href="{{ route('dailyExpenses.index') }}"
                            class="{{ Route::currentRouteName() == 'dailyExpenses.index' ? 'active' : '' }}"><i
                                class="fe fe-list"></i> <span>Expense List</span></a>
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
                @endcan
                {{-- 			
				<li class="menu-title"><span>Daily Sales</span></li>
				<li>
					<a href="{{ route('dailySales.create') }}" class="{{ Route::currentRouteName() == 'dailySales.create' ? 'active' : '' }}"><i class="fe fe-plus-circle"></i> <span> Add Daily Sales</span></a>
					<a href="{{ route('dailySales.index') }}" class="{{ Route::currentRouteName() == 'dailySales.index' ? 'active' : '' }}"><i class="fe fe-list"></i> <span>Daily Sales List</span></a>
					<a href="{{ route('salesTarget.create') }}" class="{{ Route::currentRouteName() == 'salesTarget.create' ? 'active' : '' }}"><i class="fe fe-plus-circle"></i> <span> Add Sales Target</span></a>
					<a href="{{ route('salesTarget.index') }}" class="{{ Route::currentRouteName() == 'salesTarget.index' ? 'active' : '' }}"><i class="fe fe-list"></i> <span>Sales Target List</span></a>									
				</li>

				<li class="menu-title"><span>Daily Expenses</span></li>
				<li>
					<a href="{{ route('dailyExpenses.create') }}" class="{{ Route::currentRouteName() == 'dailyExpenses.create' ? 'active' : '' }}"><i class="fe fe-plus-circle"></i> <span> Add Daily Expense</span></a>
					<a href="{{ route('dailyExpenses.index') }}" class="{{ Route::currentRouteName() == 'dailyExpenses.index' ? 'active' : '' }}"><i class="fe fe-list"></i> <span>Daily Expense List</span></a>
				</li>			
				<li class="menu-title"><span>All Reports</span></li>
				<li>
					<a href="{{ route('service.payments') }}" class="{{ Route::currentRouteName() == 'service.payments' ? 'active' : '' }}"><i class="fe fe-credit-card"></i> <span>Service Payment Report</span></a>
					<a href="{{ route('sales.payments') }}" class="{{ Route::currentRouteName() == 'sales.payments' ? 'active' : '' }}"><i class="fe fe-credit-card"></i> <span>Sales Payment Report</span></a>
				</li>
				<li class="menu-title"><span>Employee Management</span></li> --}}
                {{-- <li>
					<a href="{{ route('staff.index') }}" class="{{ Route::currentRouteName() == 'staff.index' ? 'active' : '' }}"><i class="fe fe-user"></i> <span> Employee List</span></a>
				</li>
				<li>
					<a href="{{ route('attendance.index') }}" class="{{ Route::currentRouteName() == 'attendance.index' ? 'active' : '' }}"><i class="fe fe-user"></i> <span> Attendance</span></a>
				</li>			 --}}
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
                    {{-- <li>
						<a href="{{ route('users.pin') }}" class="{{ Route::currentRouteName() == 'users.pin' ? 'active' : '' }}"><i class="fe fe-lock"></i> <span> PIN Number</span></a>
					</li> --}}
                @endcan
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
