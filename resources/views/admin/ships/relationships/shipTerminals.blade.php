<!--@can('terminal_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.terminals.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.terminal.title_singular') }}
            </a>
        </div>
    </div>
@endcan-->

<div class="card">
    <div class="card-header">
        {{ trans('cruds.terminal.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Terminal">
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
                <tbody>
                    @foreach($terminals as $key => $terminal)
                        <tr data-entry-id="{{ $terminal->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $terminal->id ?? '' }}
                            </td>
                            <td>
                                {{ $terminal->name ?? '' }}
                            </td>
                            <td>
                                @foreach($terminal->ships as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span style="display:none">{{ $terminal->air_comm_blocked ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $terminal->air_comm_blocked ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $terminal->power_backup ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $terminal->power_backup ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $terminal->power_main ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $terminal->power_main ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $terminal->sleep_schedule ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $terminal->sleep_schedule ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $terminal->battery_low ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $terminal->battery_low ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $terminal->speeding_start ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $terminal->speeding_start ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $terminal->speeding_end ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $terminal->speeding_end ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $terminal->modem_registration ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $terminal->modem_registration ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $terminal->geofence_in ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $terminal->geofence_in ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $terminal->geofence_out ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $terminal->geofence_out ? 'checked' : '' }}>
                            </td>
                            <td>
                                @can('terminal_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.terminals.show', $terminal->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('terminal_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.terminals.edit', $terminal->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('terminal_delete')
                                    <form action="{{ route('admin.terminals.destroy', $terminal->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('terminal_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.terminals.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
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
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  });
  $('.datatable-Terminal:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection