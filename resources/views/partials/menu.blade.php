<div class="sidebar">
    <nav class="sidebar-nav">

        <ul class="nav">
            <li>
                <select class="searchable-field form-control">

                </select>
            </li>
            <li class="nav-item">
                <a href="{{ route("admin.home") }}" class="nav-link">
                    <i class="nav-icon fa fa-tachometer">
                    </i>
                    {{ trans('global.dashboard') }}
                </a>
            </li>
            @can('history_ship_access')
                <li class="nav-item">
                    <a href="{{ route("admin.history-ships.index") }}"
                       class="nav-link {{ request()->is('admin/history-ships') || request()->is('admin/history-ships/*') ? 'active' : '' }}">
                        <i class="fa fa-cogs nav-icon">

                        </i>
                        {{ trans('cruds.historyShip.title') }}
                    </a>
                </li>
            @endcan
            @can('manager_access')
                <li class="nav-item">
                    <a href="{{ route("admin.managers.index") }}"
                       class="nav-link {{ request()->is('admin/managers') || request()->is('admin/managers/*') ? 'active' : '' }}">
                        <i class="fa fa-cogs nav-icon">

                        </i>
                        {{ trans('cruds.manager.title') }}
                    </a>
                </li>
            @endcan
            @can('ship_access')
                <li class="nav-item">
                    <a href="{{ route("admin.ships.index") }}"
                       class="nav-link {{ request()->is('admin/ships') || request()->is('admin/ships/*') ? 'active' : '' }}">
                        <i class="fa fa-cogs nav-icon">

                        </i>
                        {{ trans('cruds.ship.title') }}
                    </a>
                </li>
            @endcan
            @can('terminal_access')
                <li class="nav-item">
                    <a href="{{ route("admin.terminals.index") }}"
                       class="nav-link {{ request()->is('admin/terminals') || request()->is('admin/terminals/*') ? 'active' : '' }}">
                        <i class="fa fa-cogs nav-icon">

                        </i>
                        {{ trans('cruds.terminal.title') }}
                    </a>
                </li>
            @endcan
            @can('terminal_ship_access')
                <li class="nav-item">
                    <a href="{{ route("admin.terminal-ships.index") }}"
                       class="nav-link {{ request()->is('admin/terminal-ships') || request()->is('admin/terminal-ships/*') ? 'active' : '' }}">
                        <i class="fa fa-cogs nav-icon">

                        </i>
                        {{ trans('cruds.terminalShip.title') }}
                    </a>
                </li>
            @endcan
            @can('user_management_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa fa-users nav-icon">

                        </i>
                        {{ trans('cruds.userManagement.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        @can('permission_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.permissions.index") }}"
                                   class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                    <i class="fa fa-unlock-alt nav-icon">

                                    </i>
                                    {{ trans('cruds.permission.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('role_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.roles.index") }}"
                                   class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                    <i class="fa fa-briefcase nav-icon">

                                    </i>
                                    {{ trans('cruds.role.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('user_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.users.index") }}"
                                   class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                    <i class="fa fa-user nav-icon">

                                    </i>
                                    {{ trans('cruds.user.title') }}
                                </a>
                            </li>
                        @endcan
                            <li class="nav-item">
                                <a href="{{ route("admin.change-password") }}"
                                   class="nav-link {{ request()->is('admin/change-password') || request()->is('admin/change-password/*') ? 'active' : '' }}">
                                    <i class="fa fa-user nav-icon">

                                    </i>
                                    {{ trans('cruds.user.fields.change_password') }}
                                </a>
                            </li>
                    </ul>
                </li>
            @endcan
            <li class="nav-item">
                <a href="#" class="nav-link"
                   onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fa fa-sign-out">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
        </ul>

    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
