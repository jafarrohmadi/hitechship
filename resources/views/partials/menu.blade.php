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
                <li class="nav-item">
                    <a href="{{ route("admin.shipps.index") }}"
                       class="nav-link {{ request()->is('admin/shipps') || request()->is('admin/shipps/*') ? 'active' : '' }}">
                        <i class="fa fa-cogs nav-icon">

                        </i>
                        {{ trans('cruds.shipp.title') }}
                    </a>
                </li>

<!--                <li class="nav-item">
                    <a href="{{ route("admin.history-ships.index") }}"
                       class="nav-link {{ request()->is('admin/history-ships') || request()->is('admin/history-ships/*') ? 'active' : '' }}">
                        <i class="fa fa-cogs nav-icon">

                        </i>
                        {{ trans('cruds.historyShip.title') }}
                    </a>
                </li>-->

                <li class="nav-item">
                    <a href="{{ route("admin.terminals.index") }}"
                       class="nav-link {{ request()->is('admin/terminals') || request()->is('admin/terminals/*') ? 'active' : '' }}">
                        <i class="fa fa-cogs nav-icon">

                        </i>
                        {{ trans('cruds.terminal.title') }}
                    </a>
                </li>

            @can('setting_access')
                <li class="nav-item">
                    <a href="{{ route("admin.settings.index") }}" class="nav-link {{ request()->is('admin/settings') || request()->is('admin/settings/*') ? 'active' : '' }}">
                        <i class="fa fa-cogs nav-icon">

                        </i>
                        {{ trans('cruds.setting.title') }}
                    </a>
                </li>
            @endcan

            @can('email_destination_access')
                <li class="nav-item">
                    <a href="{{ route("admin.email-destination.index") }}" class="nav-link {{ request()->is('admin/email-destinatio') || request()->is('admin/email-destinatio/*') ? 'active' : '' }}">
                        <i class="fa fa-cogs nav-icon">

                        </i>
                        Email Destination
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
                        @can('manager_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.managers.index") }}"
                                   class="nav-link {{ request()->is('admin/managers') || request()->is('admin/managers/*') ? 'active' : '' }}">
                                    <i class="fa fa-user nav-icon">

                                    </i>
                                    {{ trans('cruds.manager.title') }}
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
