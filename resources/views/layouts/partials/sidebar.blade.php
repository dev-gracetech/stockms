<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    {{-- <a href="{{ route('home') }}"><img src="{{ asset('assets/compiled/svg/logo.svg')}}" alt="Logo" srcset=""></a> --}}
                    <a href="#"><i class="bi bi-person-rolodex"></i>{{ __(' Stock')}}</a>
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
                        <li class="submenu-item">
                            <a href="{{route('stocks.index')}}" class="submenu-link">Stock</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{route('branches.index')}}" class="submenu-link">Branch</a>
                        </li>
                        {{-- <li class="submenu-item">
                            <a href="{{route('stock-requests.index')}}" class="submenu-link">Stock Requests</a>
                        </li> --}}
                        <li class="submenu-item">
                            <a href="{{route('stock-transfers.index')}}" class="submenu-link">Stock Transfers</a>
                        </li>
                        @haspermission('stock-create')
                        <li class="submenu-item">
                            <a href="{{route('stock-transfers.create')}}" class="submenu-link">Request Stock</a>
                        </li>
                        @endhaspermission
                    </ul>
                </li>
                <li class="sidebar-item  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-newspaper"></i>
                        <span>Reports</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="{{route('reports.issued-stocks')}}" class="submenu-link">Issued Stocks</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{route('reports.branch-stock')}}" class="submenu-link">Branch Stock</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{route('reports.stock-details')}}" class="submenu-link">Stock Details</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{route('reports.expiry-coming-stocks')}}" class="submenu-link">Expiry Coming Stocks</a>
                        </li>
                    </ul>
                </li>
                @haspermission('user-manage')
                <li class="sidebar-item  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-people"></i>
                        <span>User Management</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="{{route('users.index')}}" class="submenu-link">Users</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{route('roles.index')}}" class="submenu-link">Roles</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{route('permissions.index')}}" class="submenu-link">Permissions</a>
                        </li>
                        {{-- <li class="submenu-item  has-sub">
                            <a href='#' class='sidebar-link'>
                                <i class="bi bi-people"></i>
                                <span>Permissions</span></a>
                            <ul class="submenu">
                                <li class="submenu-item">
                                    <a href="#" class="submenu-link">Permissions</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="#" class="submenu-link">Roles-Permissions</a>
                                </li>
                            </ul>
                        </li> --}}
                    </ul>
                </li>
                @endhaspermission
            </ul>
        </div>
    </div>  
</div>