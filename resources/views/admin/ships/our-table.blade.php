@extends('layouts.admin')
@section('content')
    <!--@can('ship_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.ships.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.ship.title_singular') }}
                </a>
            </div>
        </div>
    @endcan--><br>
    <div class="card">
        <div class="card-header">
            Our Table
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Ship">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.ship.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.ship.fields.ship_ids') }}
                        </th>
                        <th width="160">
                            {{ trans('cruds.ship.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.ship.fields.region_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.ship.fields.long') }}
                        </th>
                        <th width="160">
                            {{ trans('cruds.ship.fields.owner') }}
                        </th>
                        <th style="min-width: 120px;">
                            {{ trans('cruds.ship.fields.last_registration_utc') }}
                        </th>
                        <th>
                            {{ trans('cruds.ship.fields.call_sign') }}
                        </th>
                        <th>
                            {{ trans('cruds.ship.fields.type') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ships as $key => $ship)
                        <tr data-entry-id="{{ $ship->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $ship->id ?? '' }}
                            </td>
                            <td>
                                {{ $ship->ship_ids ?? '' }}
                            </td>
                            <td>
                                {{ $ship->name ?? '' }}
                            </td>
                            <td>
                                {{ $ship->region_name ?? '' }}
                            </td>
                            <td>
                                {{ $ship->long ?? '' }}
                            </td>
                            <td>
                                {{ $ship->owner ?? '' }}
                            </td>
                            <td>
                                {{ $ship->last_registration_utc ?? '' }}
                            </td>
                            <td>
                                {{ $ship->call_sign ?? '' }}
                            </td>
                            <td>
                                {{ App\Ship::TYPE_SELECT[$ship->type] ?? '' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                @can('ship_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.ships.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({selected: true}).nodes(), function (entry) {
                        return $(entry).data('entry-id')
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

            $.extend(true, $.fn.dataTable.defaults, {
                order: [[1, 'asc']],
                pageLength: 100,
            });
            $('.datatable-Ship:not(.ajaxTable)').DataTable({buttons: dtButtons})
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })

    </script>
@endsection
