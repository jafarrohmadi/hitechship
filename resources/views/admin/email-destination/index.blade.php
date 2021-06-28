@extends('layouts.admin')
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.email-destination.create") }}">
                {{ trans('global.add') }} Email
            </a>
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-header">
            Email {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-HistoryShip">
                <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.historyShip.fields.id') }}
                    </th>
                    <th style="min-width: 100px;">
                        &nbsp;
                    </th>
                    <th>
                        Display To map
                    </th>
                    <th>
                        {{ trans('cruds.historyShip.fields.history_ids') }}
                    </th>
                    <th>
                        {{ trans('cruds.historyShip.fields.sin') }}
                    </th>
                    <th>
                        {{ trans('cruds.historyShip.fields.min') }}
                    </th>
                    <th>
                        {{ trans('cruds.historyShip.fields.region_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.historyShip.fields.receive_utc') }}
                    </th>
                    <th>
                        {{ trans('cruds.historyShip.fields.message_utc') }}
                    </th>
                    <th>
                        {{ trans('cruds.historyShip.fields.ship') }}
                    </th>
                    <th style="min-width: 80px;">
                        {{ trans('cruds.ship.fields.name') }}
                    </th>
                    <th style="min-width:750px;">
                        {{ trans('cruds.historyShip.fields.payload') }}
                    </th>
                    <th>
                        {{ trans('cruds.historyShip.fields.ota_message_size') }}
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
            @can('history_ship_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.history-ships.massDestroy') }}",
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
                ajax: "{{ route('admin.history-ships.index') }}",
                columns: [
                    {data: 'placeholder', name: 'placeholder'},
                    {data: 'id', name: 'id'},
                    {data: 'actions', name: '{{ trans('global.actions') }}', "searchable": false, "orderable": false,},
                    {data: 'display_to_map', name: 'display_to_map'},
                    {data: 'history_ids', name: 'history_ids'},
                    {data: 'sin', name: 'sin'},
                    {data: 'min', name: 'min'},
                    {data: 'region_name', name: 'region_name'},
                    {data: 'receive_utc', name: 'receive_utc'},
                    {data: 'message_utc', name: 'message_utc'},
                    {data: 'ship_ship_ids', name: 'ship.ship_ids'},
                    {data: 'ship.name', name: 'ship.name'},
                    {data: 'payload', name: 'payload'},
                    {data: 'ota_message_size', name: 'ota_message_size'}
                ],
                order: [[1, 'desc']],
                pageLength: 100,
            };
            $('.datatable-HistoryShip').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        });

    </script>
@endsection
