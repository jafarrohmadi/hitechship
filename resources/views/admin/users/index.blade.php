@extends('layouts.admin')
@section('content')
    @can('user_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.users.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-User">
                <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.user.fields.id') }}
                    </th>
                    <th style="min-width: 100px">
                        &nbsp;
                    </th>
                    <th style="min-width: 150px;">
                        {{ trans('cruds.user.fields.name') }}
                    </th>
                    <th style="min-width: 100px;">
                        {{ trans('cruds.user.fields.username') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.roles') }}
                    </th>
                    <!--<th>
                        {{ trans('cruds.user.fields.terminal') }}
                    </th>-->
                    <th>
                        {{ trans('cruds.user.fields.ship') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.ship_id') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.destinasion-email') }}
                    </th>
                </tr>
                </thead>
            </table>


        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                @can('user_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.users.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({selected: true}).data(), function (entry) {
                        return entry.id
                    });

                    if (ids.length === 0) {
                        alert('{{ trans('global.datatables.zero_selected') }}')

                        return
                    }

                    if (confirm('{{ trans('global.areYouSure') }}')) {
                        $.ajax({
                            headers: {'x-csrf-token': _token},
                            method: 'POST',
                            url: config.url,
                            data: {ids: ids, _method: 'DELETE'}
                        })
                            .done(function () {
                                location.reload()
                            })
                    }
                }
            }
            dtButtons.push(deleteButton)
                @endcan

            let dtOverrideGlobals = {
                    buttons: dtButtons,
                    processing: true,
                    serverSide: true,
                    retrieve: true,
                    aaSorting: [],
                    ajax: "{{ route('admin.users.index') }}",
                    columns: [
                        {data: 'placeholder', name: 'placeholder'},
                        {data: 'id', name: 'id'},
                        {data: 'actions', name: '{{ trans('global.actions') }}'},
                        {data: 'name', name: 'name'},
                        {data: 'username', name: 'username'},
                        {data: 'roles', name: 'roles.title'},
                        //{data: 'terminal', name: 'terminals.name'},
                        {data: 'ship', name: 'ships.name'},
                        {data: 'ship_id', name: 'ships.ship_ids'},
                        {data: 'email', name: 'email.email'}
                    ],
                    order: [[1, 'desc']],
                    pageLength: 100,
                };
            $('.datatable-User').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        });

    </script>
@endsection
