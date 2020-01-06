<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
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
        @can('user_management_access')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                   aria-expanded="false">
                    <i class="fa fa-users nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @can('permission_access')
                        <a href="{{ route("admin.permissions.index") }}"
                           class="dropdown-item">
                            <i class="fa fa-unlock-alt nav-icon">

                            </i>
                            {{ trans('cruds.permission.title') }}
                        </a>
                    @endcan
                    @can('role_access')
                        <a href="{{ route("admin.roles.index") }}"
                           class="dropdown-item">
                            <i class="fa fa-briefcase nav-icon">

                            </i>
                            {{ trans('cruds.role.title') }}
                        </a>
                    @endcan
                    @can('user_access')

                        <a href="{{ route("admin.users.index") }}"
                           class="dropdown-item">
                            <i class="fa fa-user nav-icon">

                            </i>
                            {{ trans('cruds.user.title') }}
                        </a>

                    @endcan
                    @can('manager_access')

                        <a href="{{ route("admin.managers.index") }}"
                           class="dropdown-item">
                            <i class="fa fa-user nav-icon">

                            </i>
                            {{ trans('cruds.manager.title') }}
                        </a>

                    @endcan

                    <a href="{{ route("admin.change-password") }}"
                       class="dropdown-item">
                        <i class="fa fa-user nav-icon">

                        </i>
                        {{ trans('cruds.user.fields.change_password') }}
                    </a>
                </div>

            </li>
        @endcan
        @can('setting_access')
            <li class="nav-item">
                <a href="{{ route("admin.settings.index") }}"
                   class="nav-link {{ request()->is('admin/settings') || request()->is('admin/settings/*') ? 'active' : '' }}">
                    <i class="fa fa-cogs nav-icon">

                    </i>
                    {{ trans('cruds.setting.title') }}
                </a>
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
</div>
