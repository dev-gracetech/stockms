<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    @if(App\Models\SystemSetting::first()->company_logo)
                        <img src="{{ asset('storage/' . App\Models\SystemSetting::first()->company_logo) }}" alt="Company Logo" class="img-fluid" style="width: 60px; height: 60px;">
                    @else
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 60px; height: 60px;">
                    @endif
                    <a href="#"> {{ App\Models\SystemSetting::first()->company_name }}</a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-clipboard-data"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-bank"></i>
                        <span>Transaction</span>
                    </a>
                    <ul class="submenu">
                        @can('warehouse_menu_access')
                        <li class="submenu-item">
                            <a href="{{route('stocks.index')}}" class="submenu-link">Warehouse Stock</a>
                        </li>
                        @endcan
                        @can('branch_menu_access')
                        <li class="submenu-item">
                            <a href="{{route('branches.inventory')}}" class="submenu-link">Branch Stock</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{route('branch-stock.track')}}" class="submenu-link">Dispensed Stock</a>
                        </li>
                        @endcan
                        <li class="submenu-item">
                            <a href="{{route('stock-requests.index')}}" class="submenu-link">Stock Requests</a>
                        </li>
                        @can('warehouse_menu_access')
                        <li class="submenu-item">
                            <a href="{{route('stock-movements.index')}}" class="submenu-link">Stock Movements</a>
                        </li>
                        @endcan
                        @can('stock_transfer')
                        <li class="submenu-item">
                            <a href="{{route('stock-transfers.index')}}" class="submenu-link">Stock Transfers</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @can('report_manage')
                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-newspaper"></i>
                        <span>Reports</span>
                    </a>
                    <ul class="submenu">
                        @can('warehouse_menu_access')
                        <li class="submenu-item">
                            <a href="{{route('reports.current-stocks')}}" class="submenu-link">Current Stock</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{route('reports.issued-stocks')}}" class="submenu-link">Issued Stocks</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{route('reports.disposed-stocks')}}" class="submenu-link">Disposed Stocks</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{route('reports.branch-stock')}}" class="submenu-link">Branch Stock</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{route('reports.stock-track')}}" class="submenu-link">Stocks Updates</a>
                        </li>
                        @endcan
                        @if(Auth::user()->can('branch_menu_access') || Auth::user()->can('warehouse_menu_access'))
                        <li class="submenu-item">
                            <a href="{{route('reports.stock-details')}}" class="submenu-link">Stock Status Details</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endcan
                @can('user_manage')
                <li class="sidebar-item  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-people"></i>
                        <span>User Management</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="{{route('users.index')}}" class="submenu-link">Users</a>
                        </li>
                        @can('role_manage')
                        <li class="submenu-item">
                            <a href="{{route('roles.index')}}" class="submenu-link">Roles</a>
                        </li>
                        @endcan
                        @can('permission_manage')
                        <li class="submenu-item">
                            <a href="{{route('permissions.index')}}" class="submenu-link">Permissions</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
            </ul>
        </div>
    </div>  
</div>