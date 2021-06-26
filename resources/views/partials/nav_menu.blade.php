<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a href="{{ route("admin.home") }}" class="nav-link">
                {{ trans('global.dashboard') }}
            </a>
        </li>

        <!--@can('history_ship_access')
            <li class="nav-item active">
                <a href="#"
                   class="nav-link {{ request()->is('admin/history-ships') || request()->is('admin/history-ships/*') ? 'active' : '' }}">
                    {{ trans('cruds.historyShip.title') }}
                </a>
            </li>
        @endcan-->

        @can('ship_access')
            <li class="nav-item active">
                <a href="{{ route("admin.ships.index") }}"
                   class="nav-link {{ request()->is('admin/ships') || request()->is('admin/ships/*') ? 'active' : '' }}">
                    {{ trans('cruds.ship.title') }}
                </a>
            </li>
        @endcan

        @can('terminal_access')
            <li class="nav-item active">
                <a href="{{ route("admin.history-ships.index") }}"
                   class="nav-link {{ request()->is('admin/terminals') || request()->is('admin/terminals/*') ? 'active' : '' }}">
                    {{ trans('cruds.terminal.title') }}
                </a>
            </li>
        @endcan
        @can('user_management_access')
            <li class="nav-item dropdown active">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                   aria-expanded="false">
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
        @can('database_access')
            <li class="nav-item dropdown active">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                   aria-expanded="false" >
                    {{ trans('cruds.database.title') }}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @can('history_ship_access')
                        <a href="{{ route("admin.history-ships.index") }}"
                           class="dropdown-item">
                            <i class="fa fa-file-text nav-icon">

                            </i>
                            {{ trans('cruds.historyShip.title') }}
                        </a>
                    @endcan
                    <!--@can('history_ship_access')
                        <a href="{{ route("admin.our-table") }}"
                           class="dropdown-item">
                            <i class="fa fa-th-list nav-icon">

                            </i>
                            {{ trans('cruds.ourtable.title') }}
                        </a>
                    @endcan-->
                </div>
            </li>
         @endcan

       <!-- menu sub menu
            <li class="nav-item dropdown active">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-labelledby="navbarDropdown" aria-haspopup="true" aria-expanded="false">
              Dropdown link
            </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Submenu</a>
            <ul class="dropdown-menu">
              <a class="dropdown-item" href="#">Submenu action</a>
              <a class="dropdown-item" href="#">Another submenu action</a>
        </ul>
        </ul>
      </li>-->

        @can('setting_access')
            <li class="nav-item active">
                <a href="{{ route("admin.settings.index") }}"
                   class="nav-link {{ request()->is('admin/settings') || request()->is('admin/settings/*') ? 'active' : '' }}">
                    {{ trans('cruds.setting.title') }}
                </a>
            </li>
        @endcan

        <li class="nav-item active">
            <a href="#" class="nav-link"
               onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>
</div>
