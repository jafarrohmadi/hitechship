@extends('layouts.admin')
@section('content')
@can('terminal_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.terminals.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.terminal.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.terminal.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Terminal">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.ship') }}
                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.air_comm_blocked') }}
                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.power_backup') }}
                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.power_main') }}
                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.sleep_schedule') }}
                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.battery_low') }}
                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.speeding_start') }}
                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.speeding_end') }}
                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.modem_registration') }}
                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.geofence_in') }}
                    </th>
                    <th>
                        {{ trans('cruds.terminal.fields.geofence_out') }}
                    </th>
                    <th>
                        &nbsp;
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
@can('terminal_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.terminals.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
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
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
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
    ajax: "{{ route('admin.terminals.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name', name: 'name' },
{ data: 'ship', name: 'ships.name' },
{ data: 'air_comm_blocked', name: 'air_comm_blocked' },
{ data: 'power_backup', name: 'power_backup' },
{ data: 'power_main', name: 'power_main' },
{ data: 'sleep_schedule', name: 'sleep_schedule' },
{ data: 'battery_low', name: 'battery_low' },
{ data: 'speeding_start', name: 'speeding_start' },
{ data: 'speeding_end', name: 'speeding_end' },
{ data: 'modem_registration', name: 'modem_registration' },
{ data: 'geofence_in', name: 'geofence_in' },
{ data: 'geofence_out', name: 'geofence_out' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  $('.datatable-Terminal').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection